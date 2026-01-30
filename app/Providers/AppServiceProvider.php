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

        // Share masterKontak dengan semua views dari database
        View::composer('*', function ($view) {
            $masterKontak = MMasterKontak::where('status', 'active')->first();

            // Jika tidak ada data, buat data default
            if (!$masterKontak) {
                $masterKontak = (object) [
                    'nama_perusahaan' => 'Smart Shuttle',
                    'telepon_utama' => '0858-1122-4321',
                    'email_utama' => 'mdcitrasolusi@gmail.com',
                    'alamat_kantor_pusat' => 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434',
                    'jam_operasional' => [
                        ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                        ['hari' => 'Sabtu', 'jam' => '08:00 - 15:00'],
                        ['hari' => 'Minggu', 'jam' => '09:00 - 12:00']
                    ]
                ];
            }

            $view->with('masterKontak', $masterKontak);
        });
    }
}
