<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MMasterKontak; // Ubah ini dari MasterKontak ke MMasterKontak

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share masterKontak dengan semua views
        view()->composer('*', function ($view) {
            // Ganti MasterKontak dengan MMasterKontak
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