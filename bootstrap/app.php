<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Spatie\Permission\Middlewares\PermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'admin.role' => \App\Http\Middleware\CheckAdminRole::class,
            'auth.customer' => \App\Http\Middleware\CheckCustomerSession::class,
            'guest.customer' => \App\Http\Middleware\RedirectIfAuthenticatedCustomer::class, // PERBAIKAN DI SINI
        ]);
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
