<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // =========================================================
        // Role-based Access Control Middleware
        // =========================================================
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // =========================================================
        // CSRF Token Exceptions (Webhook dari eksternal service)
        // =========================================================
        // Midtrans akan mengirim POST request dari server mereka
        // tanpa CSRF token, jadi kita exclude route tersebut
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',  // Midtrans webhook
            // Tambahkan route lain jika perlu
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
