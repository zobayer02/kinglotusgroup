<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureAdminAuthenticated;

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

        $middleware->trustProxies(at: '*');

        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->alias([
            'admin.auth' => EnsureAdminAuthenticated::class,
            'admin.role' => \App\Http\Middleware\EnsureAdminRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

if ($storagePath = env('APP_STORAGE_PATH')) {
    $app->useStoragePath($storagePath);
}

return $app;
