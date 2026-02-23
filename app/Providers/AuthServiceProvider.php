<?php

namespace App\Providers;

// PERBAIKAN: Gunakan AuthServiceProvider yang benar
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Services\CustomTokenRepository;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register permission gates for Spatie Permission package
        $this->registerPolicies();

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
}
