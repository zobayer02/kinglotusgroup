<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;
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
            'password' => ['required', 'string'],
        ]);

        $identifier = trim((string) $validated['email']);
        $throttleKey = $this->throttleKey($identifier, $request->ip());

        $this->ensureIsNotRateLimited($throttleKey);

        $loginColumn = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        $admin = Admin::query()
            ->where($loginColumn, $identifier)
            ->first();

        $credentials = [
            'email' => $admin?->email ?? $identifier,
            'password' => $validated['password'],
        ];

        $remember = $request->boolean('remember');

        if (! Auth::guard('admin')->attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);

            throw ValidationException::withMessages([
                'email' => 'The provided login credentials do not match our records.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        $admin = Auth::guard('admin')->user();
        $admin->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();
        $request->session()->put('admin_session_version', (int) $admin->session_version);
        $request->session()->put('admin_last_activity_at', now()->timestamp);

        return redirect()->intended(route('admin.dashboard'));
    }

    protected function ensureIsNotRateLimited(string $throttleKey): void
    {
        if (! RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            return;
        }

        $seconds = RateLimiter::availableIn($throttleKey);
        $minutes = (int) ceil($seconds / 60);

        throw ValidationException::withMessages([
            'email' => "Too many login attempts. Please try again in {$minutes} minute(s).",
        ]);
    }

    protected function throttleKey(string $identifier, ?string $ipAddress): string
    {
        return Str::transliterate(Str::lower($identifier)).'|'.$ipAddress;
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
