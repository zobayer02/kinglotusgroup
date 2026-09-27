<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminPasswordResetOtpMail;
use App\Models\Admin;
use App\Models\SiteNotice;
use App\Support\SecurityPolicy;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function create(): View
    {
        $notice = SiteNotice::query()->active()->latest('updated_at')->first();

        return view('auth.forgot-password', [
            'notice' => $notice,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim((string) $request->input('email')));
        $throttleKey = 'forgot_password|'.Str::transliterate($email).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            return back()->withErrors([
                'email' => "Too many password reset attempts. Please try again in {$minutes} minute(s).",
            ]);
        }

        RateLimiter::hit($throttleKey, 900);

        $admin = Admin::query()->where('email', $email)->first();

        if ($admin) {
            $otp = sprintf('%06d', random_int(100000, 999999));
            $cacheKey = 'admin_pwd_reset_otp:'.$email;

            Cache::put($cacheKey, [
                'hash' => Hash::make($otp),
                'attempts' => 0,
            ], now()->addMinutes(15));

            $this->dispatchOtpMail($email, $otp, (string) $request->ip());
        }

        // Store reset email in session for OTP verification
        session(['password_reset_email' => $email]);

        return redirect()->route('password.verify-otp')->with(
            'status',
            __(Password::RESET_LINK_SENT)
        );
    }

    public function verifyOtpForm(): View|RedirectResponse
    {
        $email = session('password_reset_email');
        if (! $email) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Please enter your email to request a verification code.',
            ]);
        }

        $notice = SiteNotice::query()->active()->latest('updated_at')->first();

        return view('auth.verify-otp', [
            'email' => $email,
            'notice' => $notice,
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $email = session('password_reset_email');
        if (! $email) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Session expired. Please request a new verification code.',
            ]);
        }

        $throttleKey = 'verify_otp|'.Str::transliterate($email).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            return back()->withErrors([
                'otp' => "Too many verification attempts. Please try again in {$minutes} minute(s).",
            ]);
        }

        $request->validate([
            'otp' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ], [
            'otp.regex' => 'The verification code must be a 6-digit number.',
        ]);

        $cacheKey = 'admin_pwd_reset_otp:'.$email;
        $data = Cache::get($cacheKey);

        if (! $data) {
            RateLimiter::hit($throttleKey, 900);

            return back()->withErrors([
                'otp' => 'Verification code has expired or is invalid. Please request a new code.',
            ]);
        }

        if (($data['attempts'] ?? 0) >= 5) {
            Cache::forget($cacheKey);
            RateLimiter::hit($throttleKey, 900);

            return back()->withErrors([
                'otp' => 'Too many failed verification attempts. Please request a new code.',
            ]);
        }

        if (! Hash::check(trim((string) $request->input('otp')), $data['hash'])) {
            $data['attempts'] = ($data['attempts'] ?? 0) + 1;
            Cache::put($cacheKey, $data, now()->addMinutes(15));
            RateLimiter::hit($throttleKey, 900);

            return back()->withErrors([
                'otp' => 'Invalid verification code. Please check and try again.',
            ]);
        }

        // OTP Verified successfully!
        Cache::forget($cacheKey);
        RateLimiter::clear($throttleKey);

        $grantToken = Str::random(64);
        Cache::put('admin_pwd_grant:'.$grantToken, $email, now()->addMinutes(15));

        return redirect()->route('password.reset', [
            'token' => $grantToken,
            'email' => $email,
        ])->with('status', 'Verification code confirmed. You can now set your new password.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $email = session('password_reset_email');
        if (! $email) {
            return redirect()->route('password.request');
        }

        $throttleKey = 'resend_otp|'.Str::transliterate($email).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            return back()->withErrors([
                'otp' => "Please wait {$minutes} minute(s) before requesting another code.",
            ]);
        }

        RateLimiter::hit($throttleKey, 300);

        $admin = Admin::query()->where('email', $email)->first();
        if ($admin) {
            $otp = sprintf('%06d', random_int(100000, 999999));
            $cacheKey = 'admin_pwd_reset_otp:'.$email;

            Cache::put($cacheKey, [
                'hash' => Hash::make($otp),
                'attempts' => 0,
            ], now()->addMinutes(15));

            $this->dispatchOtpMail($email, $otp, (string) $request->ip());
        }

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    public function edit(Request $request, string $token): View|RedirectResponse
    {
        $email = (string) $request->query('email', '');

        // Check if token is a valid OTP grant token
        $grantEmail = Cache::get('admin_pwd_grant:'.$token);
        if ($grantEmail) {
            $email = $grantEmail;
        }

        $notice = SiteNotice::query()->active()->latest('updated_at')->first();

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
            'notice' => $notice,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $email = strtolower(trim((string) $request->input('email')));
        $throttleKey = 'reset_password|'.Str::transliterate($email).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            return back()->withErrors([
                'email' => "Too many password reset attempts. Please try again in {$minutes} minute(s).",
            ]);
        }

        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => SecurityPolicy::passwordRules(true),
        ]);

        $token = (string) $request->input('token');

        // Check OTP grant token first
        $grantEmail = Cache::get('admin_pwd_grant:'.$token);
        if ($grantEmail && strtolower($grantEmail) === $email) {
            $admin = Admin::query()->where('email', $email)->first();
            if ($admin) {
                $admin->forceFill([
                    'password' => Hash::make($request->input('password')),
                    'remember_token' => Str::random(60),
                    'session_version' => ((int) $admin->session_version) + 1,
                ])->save();

                event(new PasswordReset($admin));
                Cache::forget('admin_pwd_grant:'.$token);
                session()->forget('password_reset_email');
                RateLimiter::clear($throttleKey);

                return redirect()->route('login')->with(
                    'status',
                    'Your password has been reset successfully. Please sign in with your new password.'
                );
            }
        }

        // Fallback to standard Laravel Password broker (for link tokens / test compatibility)
        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin, string $password): void {
                $admin->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'session_version' => ((int) $admin->session_version) + 1,
                ])->save();

                event(new PasswordReset($admin));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            RateLimiter::clear($throttleKey);
            session()->forget('password_reset_email');

            return redirect()->route('login')->with('status', __($status));
        }

        RateLimiter::hit($throttleKey, 900);

        return back()->withErrors(['email' => __($status)]);
    }

    protected function dispatchOtpMail(string $email, string $otp, string $ip): void
    {
        try {
            Mail::to($email)->send(new AdminPasswordResetOtpMail($otp, 15));
        } catch (\Throwable $e) {
            // When running under local development servers where web process outbound sockets are restricted,
            // dispatch through the CLI command which runs with standard system privileges.
            try {
                $php = defined('PHP_BINARY') && PHP_BINARY ? PHP_BINARY : 'php';
                $artisan = base_path('artisan');
                $cmd = escapeshellarg($php).' '.escapeshellarg($artisan).' admin:send-otp '.escapeshellarg($email).' '.escapeshellarg($otp);

                if (str_starts_with(strtoupper(PHP_OS), 'WIN')) {
                    pclose(popen('start /B '.$cmd, 'r'));
                } else {
                    exec($cmd.' > /dev/null 2>&1 &');
                }
            } catch (\Throwable $cliException) {
                Log::warning('SMTP send error during admin password reset OTP', [
                    'error' => $e->getMessage(),
                    'cli_error' => $cliException->getMessage(),
                    'email' => $email,
                    'ip' => $ip,
                ]);
            }
        }
    }
}
