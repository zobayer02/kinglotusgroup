<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        $userRole = strtolower(trim((string) ($admin->role ?? '')));

        // Deny by default: only explicitly allowed roles can proceed
        if (! in_array($userRole, $roles, true)) {
            abort(403, 'Unauthorized. You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
