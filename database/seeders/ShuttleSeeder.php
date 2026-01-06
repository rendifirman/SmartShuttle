<?php
namespace Database\Seeders;

use App\Models\Shuttle;
use App\Models\MLayanan;
use App\Models\KursiTerpesan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShuttleSeeder extends Seeder
{
    public function run(): void
    {
        // Cari layanan Smart Shuttle
        $smartShuttle = MLayanan::where('kode_layanan', 'SMARTSHUTTLE')->first();

        if (!$smartShuttle) {
            echo "Layanan Smart Shuttle tidak ditemukan! Pastikan sudah run MLayananSeeder.\n";
            return;
        }

        // Data shuttle dengan layanan_id
        $shuttles = [
            [
                'layanan_id' => $smartShuttle->id_layanan,
                'nama_shuttle' => 'Smart Shuttle Hiace 1',
                'tipe_shuttle' => 'Standard',
                'kapasitas_kursi' => 9,
                'total_kursi' => 9,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(9),
                'fasilitas' => 'AC Double,WiFi High Speed,Charger USB-C,TV LED 32",Snack Premium,Mineral Water,Toilet,Bagasi Luas',
                'nomor_polisi' => 'B 1234 SS',
                'gambar_depan' => 'pandu.png',
                'gambar_samping' => 'pandu2.jfif',
                'gambar_belakang' => 'pandu3.jfif',
                'gambar_interior' => 'pandu4.jfif',
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttle->id_layanan,
                'nama_shuttle' => 'Smart Shuttle Hiace 1000',
                'tipe_shuttle' => 'Standard',
                'kapasitas_kursi' => 9,
                'total_kursi' => 9,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(9),
                'fasilitas' => 'AC Double,WiFi High Speed,Charger USB-C,TV LED 32",Snack Premium,Mineral Water,Toilet,Bagasi Luas',
                'nomor_polisi' => 'B 5678 SS',
                'gambar_depan' => 'shuttle-front-2.jpg',
                'gambar_samping' => 'shuttle-side-2.jpg',
                'gambar_belakang' => 'shuttle-rear-2.jpg',
                'gambar_interior' => 'shuttle-interior-2.jpg',
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttle->id_layanan,
                'nama_shuttle' => 'Smart Shuttle Elf 1',
                'tipe_shuttle' => 'Standard',
                'kapasitas_kursi' => 9,
                'total_kursi' => 9,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(9),
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
                [
                    'nama_shuttle' => $shuttleData['nama_shuttle'],
                    'nomor_polisi' => $shuttleData['nomor_polisi']
                ],
                $shuttleData
            );

            echo "Created/Updated shuttle: {$shuttle->nama_shuttle}\n";
        }

        echo "ShuttleSeeder completed successfully!\n";
    }
}
