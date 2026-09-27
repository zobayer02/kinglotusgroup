<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;

    private const MAX_ACCOUNT_ATTEMPTS = 15;

    private const MAX_IP_ATTEMPTS = 20;

    private const LOCKOUT_SECONDS = 900;

    public function create(): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:100'],
        ]);

        $identifier = trim((string) $validated['email']);
        $throttleKey = $this->throttleKey($identifier, $request->ip());
        $accountThrottleKey = $this->accountThrottleKey($identifier);
        $ipThrottleKey = $this->ipThrottleKey($request->ip());

        $this->ensureIsNotRateLimited($throttleKey, self::MAX_LOGIN_ATTEMPTS);
        $this->ensureIsNotRateLimited(
            $accountThrottleKey,
            self::MAX_ACCOUNT_ATTEMPTS,
            'Too many failed attempts on this account. Please try again later.'
        );
        $this->ensureIsNotRateLimited(
            $ipThrottleKey,
            self::MAX_IP_ATTEMPTS,
            'Too many failed login attempts from this network location. Please try again later.'
        );

        $loginColumn = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        /** @var Admin|null $admin */
        $admin = Admin::query()
            ->where($loginColumn, $identifier)
            ->first();

        $credentials = [
            'email' => $admin?->email ?? $identifier,
            'password' => $validated['password'],
        ];

        if (! Auth::guard('admin')->validate($credentials)) {
            RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);
            RateLimiter::hit($accountThrottleKey, self::LOCKOUT_SECONDS);
            RateLimiter::hit($ipThrottleKey, self::LOCKOUT_SECONDS);

            Log::warning('Failed admin login attempt', [
                'identifier' => $identifier,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            throw ValidationException::withMessages([
                'email' => 'The provided login credentials do not match our records.',
            ]);
        }

        $admin = $admin ?? Admin::query()->where('email', $credentials['email'])->first();

        RateLimiter::clear($throttleKey);
        RateLimiter::clear($accountThrottleKey);
        RateLimiter::clear($ipThrottleKey);

        if ($admin && $admin->hasTwoFactorEnabled()) {
            $request->session()->put('login.mfa_admin_id', $admin->id);
            $request->session()->put('login.mfa_remember', $request->boolean('remember'));
            $request->session()->put('login.mfa_expires_at', now()->addMinutes(10)->timestamp);

            return redirect()->route('login.challenge');
        }

        if ($admin) {
            Auth::guard('admin')->login($admin, $request->boolean('remember'));
        } else {
            Auth::guard('admin')->attempt($credentials, $request->boolean('remember'));
            /** @var Admin $admin */
            $admin = Auth::guard('admin')->user();
        }

        $request->session()->regenerate();

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

        Log::info('Successful admin login', [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'ip' => $request->ip(),
        ]);

        return redirect()->intended(route('admin.dashboard'));
    }

    protected function ensureIsNotRateLimited(
        string $throttleKey,
        int $maxAttempts = self::MAX_LOGIN_ATTEMPTS,
        ?string $customMessage = null
    ): void {
        if (! RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            return;
        }

        $seconds = RateLimiter::availableIn($throttleKey);
        $minutes = (int) ceil($seconds / 60);

        throw ValidationException::withMessages([
            'email' => $customMessage ?? "Too many login attempts. Please try again in {$minutes} minute(s).",
        ]);
    }

    protected function throttleKey(string $identifier, ?string $ipAddress): string
    {
        return Str::transliterate(Str::lower($identifier)).'|'.$ipAddress;
    }

    protected function accountThrottleKey(string $identifier): string
    {
        return 'admin_account|'.Str::transliterate(Str::lower($identifier));
    }

    protected function ipThrottleKey(?string $ipAddress): string
    {
        return 'admin_ip|'.($ipAddress ?? '127.0.0.1');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
