<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Render terminates TLS at its reverse proxy and forwards the original
        // scheme in X-Forwarded-Proto. Trust that proxy so generated URLs,
        // redirects, CSRF and secure cookies use HTTPS externally.
        $middleware->trustProxies(at: '*');

        // Loại trừ CSRF cho MoMo IPN
        $middleware->validateCsrfTokens(except: [
            'payment/momo/ipn',
            'shipping/ghn/webhook/*',
        ]);

        // Đăng ký Alias cho Route Middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'roles' => \App\Http\Middleware\CheckRoles::class,
            'role'  => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
