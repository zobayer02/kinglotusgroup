<?php

use App\Http\Middleware\EnsureAdminAuthenticated;
use App\Http\Middleware\EnsureAdminRole;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustHosts(at: fn () => array_filter([
            'localhost',
            '127.0.0.1',
            '^(.+\.)?kinglotusgroup\.com$',
            parse_url(config('app.url'), PHP_URL_HOST) ? '^(.+\.)?'.preg_quote((string) parse_url(config('app.url'), PHP_URL_HOST)).'$' : null,
        ]), subdomains: true);

        $trustedProxies = env('TRUSTED_PROXIES');
        $proxies = is_string($trustedProxies) && trim($trustedProxies) !== ''
            ? array_filter(array_map('trim', explode(',', $trustedProxies)))
            : [];
        $middleware->trustProxies(at: $proxies);

        $middleware->append(SecurityHeaders::class);

        $middleware->validateCsrfTokens(except: [
            'api/csp-report',
        ]);

        $middleware->alias([
            'admin.auth' => EnsureAdminAuthenticated::class,
            'admin.role' => EnsureAdminRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Too many requests. Please slow down and try again later.',
                ], 429);
            }

            return response()->view('errors.429', [
                'exception' => $e,
            ], 429);
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if (app()->isProduction() && ! config('app.debug')) {
                if ($e instanceof HttpExceptionInterface) {
                    return null;
                }

                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => 'An unexpected server error occurred.',
                    ], 500);
                }
            }
        });
    })->create();

if ($storagePath = env('APP_STORAGE_PATH')) {
    $app->useStoragePath($storagePath);
}

return $app;
