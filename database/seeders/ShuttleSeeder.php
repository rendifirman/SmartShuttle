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
                'gambar_depan' => 'shuttle-front-1.jpg',
                'gambar_samping' => 'shuttle-side-1.jpg',
                'gambar_belakang' => 'shuttle-rear-1.jpg',
                'gambar_interior' => 'shuttle-interior-1.jpg',
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
            // Tambahkan shuttle untuk layanan lain (Smart Send, Smart Rent)
            [
                'layanan_id' => MLayanan::where('kode_layanan', 'SMARTSEND')->value('id_layanan'),
                'nama_shuttle' => 'Logistics Van 1',
                'tipe_shuttle' => 'Logistics',
                'kapasitas_kursi' => 3,
                'total_kursi' => 3,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(3),
                'fasilitas' => 'AC,GPS Tracking,Loading Ramp,Safety Box',
                'nomor_polisi' => 'B 3456 LS',
                'status' => 'aktif'
            ],
            [
                'layanan_id' => MLayanan::where('kode_layanan', 'SMARTRENT')->value('id_layanan'),
                'nama_shuttle' => 'Rental Car 1',
                'tipe_shuttle' => 'Premium',
                'kapasitas_kursi' => 6,
                'total_kursi' => 6,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(6),
                'fasilitas' => 'AC,Leather Seat,Entertainment System,Sunroof',
                'nomor_polisi' => 'B 7890 RC',
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

            echo "Created/Updated shuttle: {$shuttle->nama_shuttle} (Layanan: {$shuttle->layanan_id})\n";
        }

        echo "ShuttleSeeder completed successfully!\n";
    }
}
