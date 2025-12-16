<?php
namespace Database\Seeders;

use App\Models\Shuttle;
use App\Models\KursiTerpesan;
use Illuminate\Database\Seeder;

class ShuttleSeeder extends Seeder
{
    public function run(): void
    {
        // Data shuttle dengan layout FIX 9 kursi
        $shuttles = [
            [
                'nama_shuttle' => 'Smart Shuttle Hiace 1',
                'tipe_shuttle' => 'Standard',
                'kapasitas_kursi' => 9,
                'total_kursi' => 9,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(9), // LAYOUT FIX
                'fasilitas' => 'AC Double,WiFi High Speed,Charger USB-C,TV LED 32",Snack Premium,Mineral Water,Toilet,Bagasi Luas',
                'nomor_polisi' => 'B 1234 SS',
                'gambar_depan' => 'shuttle-front-1.jpg',
                'gambar_samping' => 'shuttle-side-1.jpg',
                'gambar_belakang' => 'shuttle-rear-1.jpg',
                'gambar_interior' => 'shuttle-interior-1.jpg',
                'status' => 'aktif'
            ],
            [
                'nama_shuttle' => 'Smart Shuttle Hiace 2',
                'tipe_shuttle' => 'Standard',
                'kapasitas_kursi' => 9,
                'total_kursi' => 9,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(9), // LAYOUT FIX
                'fasilitas' => 'AC Double,WiFi High Speed,Charger USB-C,TV LED 32",Snack Premium,Mineral Water,Toilet,Bagasi Luas',
                'nomor_polisi' => 'B 5678 SS',
                'gambar_depan' => 'shuttle-front-2.jpg',
                'gambar_samping' => 'shuttle-side-2.jpg',
                'gambar_belakang' => 'shuttle-rear-2.jpg',
                'gambar_interior' => 'shuttle-interior-2.jpg',
                'status' => 'aktif'
            ],
            [
                'nama_shuttle' => 'Smart Shuttle Elf 1',
                'tipe_shuttle' => 'Standard',
                'kapasitas_kursi' => 9,
                'total_kursi' => 9,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(9), // LAYOUT FIX
                'fasilitas' => 'AC Double,WiFi High Speed,Charger USB-C,TV LED 32",Snack Premium,Mineral Water,Toilet,Bagasi Luas',
                'nomor_polisi' => 'B 9012 SS',
                'gambar_depan' => 'shuttle-front-3.jpg',
                'gambar_samping' => 'shuttle-side-3.jpg',
                'gambar_belakang' => 'shuttle-rear-3.jpg',
                'gambar_interior' => 'shuttle-interior-3.jpg',
                'status' => 'aktif'
            ],
        ];

        foreach ($shuttles as $shuttleData) {
            $shuttle = Shuttle::updateOrCreate(
                ['nama_shuttle' => $shuttleData['nama_shuttle']],
                $shuttleData
            );

            // Pastikan layout sudah tersimpan dengan benar
            $shuttle->initLayoutIfEmpty();

            echo "Created/Updated shuttle: {$shuttle->nama_shuttle} with layout\n";
        }

        echo "ShuttleSeeder completed successfully!\n";
    }
}
