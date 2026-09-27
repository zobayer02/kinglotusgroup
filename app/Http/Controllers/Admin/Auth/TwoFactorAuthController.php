<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SiteNotice;
use App\Support\TotpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorAuthController extends Controller
{
    private const MAX_CHALLENGE_ATTEMPTS = 5;

    private const LOCKOUT_SECONDS = 900;

    public function challenge(Request $request): View|RedirectResponse
    {
        $adminId = $request->session()->get('login.mfa_admin_id');
        $expiresAt = (int) $request->session()->get('login.mfa_expires_at', 0);

        if (! $adminId || now()->timestamp > $expiresAt) {
            $request->session()->forget(['login.mfa_admin_id', 'login.mfa_remember', 'login.mfa_expires_at']);

            return redirect()->route('login')->with('error', 'Your two-factor authentication session expired. Please sign in again.');
        }

        $notice = SiteNotice::query()->active()->latest('updated_at')->first();

        return view('auth.two-factor-challenge', [
            'notice' => $notice,
        ]);
    }

    public function verifyChallenge(Request $request): RedirectResponse
    {
        $adminId = $request->session()->get('login.mfa_admin_id');
        $expiresAt = (int) $request->session()->get('login.mfa_expires_at', 0);

        if (! $adminId || now()->timestamp > $expiresAt) {
            $request->session()->forget(['login.mfa_admin_id', 'login.mfa_remember', 'login.mfa_expires_at']);

            return redirect()->route('login')->with('error', 'Your two-factor authentication session expired. Please sign in again.');
        }

        /** @var Admin|null $admin */
        $admin = Admin::query()->find($adminId);
        if (! $admin || ! $admin->hasTwoFactorEnabled()) {
            $request->session()->forget(['login.mfa_admin_id', 'login.mfa_remember', 'login.mfa_expires_at']);

            return redirect()->route('login')->with('error', 'Invalid two-factor authentication session.');
        }

        $throttleKey = 'mfa_challenge|'.$admin->id.'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_CHALLENGE_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            throw ValidationException::withMessages([
                'code' => "Too many authentication attempts. Please try again in {$minutes} minute(s).",
            ]);
        }

        $validated = $request->validate([
            'code' => ['nullable', 'string', 'max:20'],
            'recovery_code' => ['nullable', 'string', 'max:30'],
        ]);

        $code = trim((string) ($validated['code'] ?? ''));
        $recoveryCode = trim((string) ($validated['recovery_code'] ?? ''));

        $authenticated = false;
        $usedRecoveryCode = false;

        if ($code !== '') {
            $authenticated = TotpService::verifyCode($admin->two_factor_secret, $code);
        } elseif ($recoveryCode !== '') {
            $normalizedInput = strtoupper(str_replace(['-', ' '], '', $recoveryCode));
            $recoveryCodes = is_array($admin->two_factor_recovery_codes) ? $admin->two_factor_recovery_codes : [];

            foreach ($recoveryCodes as $index => $storedCode) {
                $normalizedStored = strtoupper(str_replace(['-', ' '], '', (string) $storedCode));
                if (hash_equals($normalizedStored, $normalizedInput)) {
                    $authenticated = true;
                    $usedRecoveryCode = true;
                    unset($recoveryCodes[$index]);
                    $admin->two_factor_recovery_codes = array_values($recoveryCodes);
                    $admin->save();
                    break;
                }
            }
        }

        if (! $authenticated) {
            RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);

            Log::warning('Failed admin two-factor authentication attempt', [
                'admin_id' => $admin->id,
                'ip' => $request->ip(),
            ]);

            throw ValidationException::withMessages([
                'code' => 'The provided two-factor authentication code or recovery code was invalid.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $remember = (bool) $request->session()->get('login.mfa_remember', false);
        $request->session()->forget(['login.mfa_admin_id', 'login.mfa_remember', 'login.mfa_expires_at']);
        $request->session()->regenerate();

        Auth::guard('admin')->login($admin, $remember);

        if ($admin->last_login_ip && $admin->last_login_ip !== $request->ip()) {
            Log::warning('Admin login from new IP address', [
                'admin_id' => $admin->id,
                'email' => $admin->email,
                'previous_ip' => $admin->last_login_ip,
                'current_ip' => $request->ip(),
            ]);
        }

        $admin->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        $request->session()->put('admin_session_version', (int) $admin->session_version);
        $request->session()->put('admin_last_activity_at', now()->timestamp);

        if ($usedRecoveryCode) {
            Log::warning('Admin authenticated using MFA recovery code', [
                'admin_id' => $admin->id,
                'ip' => $request->ip(),
            ]);

            return redirect()->intended(route('admin.dashboard'))
                ->with('status', 'Signed in successfully using a recovery code. Please check your remaining recovery codes in your profile.');
        }

        Log::info('Successful admin login with MFA', [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'ip' => $request->ip(),
        ]);

        return redirect()->intended(route('admin.dashboard'));
    }
}
