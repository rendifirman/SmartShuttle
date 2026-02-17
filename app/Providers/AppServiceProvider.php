<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\MMasterKontak;
use App\Services\KontakService;
use App\Services\PaylabsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register kontak service
        $this->app->singleton('kontakService', function () {
            return new KontakService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share kontak data ke semua views dari kontakService
        View::composer('*', function ($view) {
            $kontakService = app('kontakService');
            $globalKontak = $kontakService->getKontak();
            $view->with('globalKontak', $globalKontak);
            $view->with('kontakService', $kontakService);
        });

        // Share masterKontak dengan semua views menggunakan method yang sudah ada caching
        View::composer('*', function ($view) {
            $masterKontak = MMasterKontak::getDataKontak();
            $view->with('masterKontak', $masterKontak);
        });

        // Share user session data ke semua views untuk navbar consistency
        View::composer('*', function ($view) {
            $userSession = session()->get('user', null);
            $view->with('userSession', $userSession);
        });
    }
}
