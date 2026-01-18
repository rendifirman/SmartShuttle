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
                'kode' => 'ARM 01',
                'merk' => 'Toyota',
                'model' => 'Hiace',
                'tipe_shuttle' => 'standar',
                'tahun' => 2022,
                'warna' => 'Putih',
                'kapasitas_kursi' => 9,
                'total_kursi' => 9,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(9),
                'fasilitas' => 'AC Double,WiFi High Speed,Charger USB-C,TV LED 32",Snack Premium,Mineral Water,Toilet,Bagasi Luas',
                'nomor_polisi' => 'B 1234 SS',
                'no_stnk' => '1234567890',
                'masa_stnk' => '2025-12-31',
                'no_kir' => 'KIR789012',
                'masa_kir' => '2025-12-31',
                'jenis_kepemilikan' => 'milik-perusahaan',
                'nama_pemilik' => 'PT Pandu Transit',
                'tanggal_masuk' => '2022-01-15',
                'nilai_asset' => 300000000.00,
                'asuransi' => 'Comprehensive',
                'masa_asuransi' => '2025-12-31',
                'masa_kontrak' => '15-01-2022 s/d 14-01-2025',
                'kelengkapan' => json_encode([
                    ['name' => 'Dongkrak', 'checked' => true],
                    ['name' => 'P3K', 'checked' => true],
                    ['name' => 'Ban Cadangan', 'checked' => true],
                    ['name' => 'Spare Tire', 'checked' => true],
                    ['name' => 'Jumper Cable', 'checked' => true],
                    ['name' => 'APAR', 'checked' => true],
                    ['name' => 'Tools Kit', 'checked' => true]
                ]),
                'gambar_depan' => 'pandu.png',
                'gambar_samping' => 'pandu2.jfif',
                'gambar_belakang' => 'pandu3.jfif',
                'gambar_interior' => 'pandu4.jfif',
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttle->id_layanan,
                'kode' => 'ARM 02',
                'nama_shuttle' => 'Smart Shuttle Hiace 1000',
                'merk' => 'Honda',
                'model' => 'Jazz',
                'tipe_shuttle' => 'premium',
                'tahun' => 2019,
                'warna' => 'Putih',
                'kapasitas_kursi' => 9,
                'total_kursi' => 9,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(9),
                'fasilitas' => 'AC Double,WiFi High Speed,Charger USB-C,TV LED 32",Snack Premium,Mineral Water,Toilet,Bagasi Luas',
                'nomor_polisi' => 'B 5678 SS',
                'no_stnk' => '9876543210',
                'masa_stnk' => '2025-06-30',
                'no_kir' => 'KIR123456',
                'masa_kir' => '2025-06-30',
                'jenis_kepemilikan' => 'sewa',
                'nama_pemilik' => 'CV Sejahtera',
                'tanggal_masuk' => '2022-03-15',
                'nilai_asset' => 250000000.00,
                'asuransi' => 'TLO',
                'masa_asuransi' => '2025-06-30',
                'masa_kontrak' => '15-03-2022 s/d 14-03-2024',
                'kelengkapan' => json_encode([
                    ['name' => 'Dongkrak', 'checked' => true],
                    ['name' => 'P3K', 'checked' => true],
                    ['name' => 'Ban Cadangan', 'checked' => true],
                    ['name' => 'Spare Tire', 'checked' => true],
                    ['name' => 'Jumper Cable', 'checked' => false],
                    ['name' => 'APAR', 'checked' => true],
                    ['name' => 'Tools Kit', 'checked' => true]
                ]),
                'gambar_depan' => 'shuttle-front-2.jpg',
                'gambar_samping' => 'shuttle-side-2.jpg',
                'gambar_belakang' => 'shuttle-rear-2.jpg',
                'gambar_interior' => 'shuttle-interior-2.jpg',
                'status' => 'nonaktif'
            ],
            [
                'layanan_id' => $smartShuttle->id_layanan,
                'kode' => 'ARM 03',
                'nama_shuttle' => 'Smart Shuttle Elf 1',
                'merk' => 'Mitsubishi',
                'model' => 'L300',
                'tipe_shuttle' => 'luxury',
                'tahun' => 2017,
                'warna' => 'Hitam',
                'kapasitas_kursi' => 9,
                'total_kursi' => 9,
                'layout_kursi' => KursiTerpesan::generateLayoutKursi(9),
                'fasilitas' => 'AC Double,WiFi High Speed,Charger USB-C,TV LED 32",Snack Premium,Mineral Water,Toilet,Bagasi Luas',
                'nomor_polisi' => 'B 9012 SS',
                'no_stnk' => '5555555555',
                'masa_stnk' => '2024-09-30',
                'no_kir' => 'KIR777777',
                'masa_kir' => '2024-09-30',
                'jenis_kepemilikan' => 'vendor',
                'nama_pemilik' => 'PT Jaya Abadi',
                'tanggal_masuk' => '2021-08-10',
                'nilai_asset' => 180000000.00,
                'asuransi' => 'All Risk',
                'masa_asuransi' => '2024-09-30',
                'masa_kontrak' => '10-08-2021 s/d 09-08-2023',
                'kelengkapan' => json_encode([
                    ['name' => 'Dongkrak', 'checked' => true],
                    ['name' => 'P3K', 'checked' => true],
                    ['name' => 'APAR', 'checked' => true],
                    ['name' => 'Tools Kit', 'checked' => true],
                    ['name' => 'Emergency Light', 'checked' => true],
                    ['name' => 'Ban Cadangan', 'checked' => true],
                    ['name' => 'Spare Tire', 'checked' => true]
                ]),
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
