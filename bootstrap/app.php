<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Tambahkan middleware global untuk inisialisasi session dan CSRF
        $middleware->web(append: [
            \App\Http\Middleware\InitializeSessionAndCsrf::class,
            \App\Http\Middleware\EnsureCsrfTokenInResponse::class,
        ]);

        $middleware->alias([
            'admin.role' => \App\Http\Middleware\CheckAdminRole::class,
            'branch.access' => \App\Http\Middleware\CheckBranchAccess::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'auth.customer' => \App\Http\Middleware\CheckCustomerSession::class,
            'guest.customer' => \App\Http\Middleware\RedirectIfAuthenticatedCustomer::class,
            'guest.driver' => \App\Http\Middleware\RedirectIfAuthenticatedDriver::class,
            'ensure.session' => \App\Http\Middleware\EnsureSessionStarted::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
