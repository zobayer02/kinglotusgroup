<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach hardened security headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Remove PHP version disclosure if possible at runtime
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }

        // 2. Generate a cryptographically secure 128-bit per-request nonce
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);

        // Bind nonce to Laravel Vite facade and share with all Blade views
        Vite::useCspNonce($nonce);
        if (function_exists('view')) {
            view()->share('cspNonce', $nonce);
        }

        /** @var Response $response */
        $response = $next($request);

        // 3. Remove info disclosure headers from response
        $response->headers->remove('X-Powered-By');

        // 4. Set browser defensive headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // 5. Build Content Security Policy directives
        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}' https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: https: blob:",
            "media-src 'self' https: data: blob:",
            "frame-src 'self' https://www.google.com https://maps.google.com https://www.youtube.com https://www.youtube-nocookie.com",
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "report-uri /api/csp-report",
            "report-to csp-endpoint",
        ];
        $cspPolicy = implode('; ', $cspDirectives) . ';';

        $reportOnly = (bool) config('security.csp.report_only', env('CSP_REPORT_ONLY', false));
        $cspHeader = $reportOnly ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';
        $response->headers->set($cspHeader, $cspPolicy);

        // Modern Reporting API endpoint declaration
        $response->headers->set('Reporting-Endpoints', 'csp-endpoint="' . url('/api/csp-report') . '"');

        // 6. HTTP Strict Transport Security (HSTS)
        // Sent ONLY over secure HTTPS connections. Subdomains & preload are explicit decisions.
        if ($request->isSecure() && (bool) config('security.hsts.enabled', env('HSTS_ENABLED', true))) {
            $maxAge = (int) config('security.hsts.max_age', env('HSTS_MAX_AGE', 31536000));
            $hsts = "max-age={$maxAge}";

            if ((bool) config('security.hsts.include_subdomains', env('HSTS_INCLUDE_SUBDOMAINS', false))) {
                $hsts .= '; includeSubDomains';
            }

            if ((bool) config('security.hsts.preload', env('HSTS_PRELOAD', false))) {
                $hsts .= '; preload';
            }

            $response->headers->set('Strict-Transport-Security', $hsts);
        }

        return $response;
    }
}
