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
        // Seguridad global en todas las respuestas (igual que HE-5)
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Excluir webhook de Stripe de la verificación CSRF (igual que HE-5)
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);

        // Alias de middlewares personalizados
        $middleware->alias([
            'resolve.tenant' => \App\Http\Middleware\ResolveTenant::class,
            'role'           => \App\Http\Middleware\EnsureRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
