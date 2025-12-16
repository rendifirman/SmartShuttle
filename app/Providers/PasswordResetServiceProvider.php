<?php

namespace App\Providers;

use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Support\ServiceProvider;
use App\Services\CustomTokenRepository;

class PasswordResetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('auth.password.tokens', function ($app) {
            $config = $app['config']['auth.passwords.users'];
            
            return new CustomTokenRepository(
                $app['db']->connection($config['connection'] ?? null),
                $app['hash'],
                $config['table'],
                $app['config']['app.key'],
                $config['expire'] ?? 60,
                $config['throttle'] ?? 0
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}