<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        /** @var Authenticatable&object $admin */
        $admin = Auth::guard('admin')->user();
        $sessionVersion = (int) $request->session()->get('admin_session_version', 0);
        $currentVersion = (int) ($admin->session_version ?? 1);

        if ($sessionVersion !== $currentVersion) {
            return $this->logoutAndRedirect($request, 'Your session was ended. Please sign in again.');
        }

        $timeoutMinutes = max(1, (int) config('admin.session_idle_timeout', 120));
        $lastActivity = (int) $request->session()->get('admin_last_activity_at', now()->timestamp);
        $expiresAt = Carbon::createFromTimestamp($lastActivity)->addMinutes($timeoutMinutes);

        if (now()->greaterThanOrEqualTo($expiresAt)) {
            return $this->logoutAndRedirect($request, 'Your session expired due to inactivity. Please sign in again.');
        }

        $request->session()->put('admin_last_activity_at', now()->timestamp);

        return $next($request);
    }

    protected function logoutAndRedirect(Request $request, string $message): Response
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('error', $message);
    }
}
