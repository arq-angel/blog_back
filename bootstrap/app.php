<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\API\V1\EnsureEmailIsVerified::class,
            'check.public.token' => \App\Http\Middleware\API\V1\CheckPublicToken::class,
            'check.auth.token' => \App\Http\Middleware\API\V1\CheckAuthToken::class,
        ]);
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

    })->create();
