<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MLayanan;

class MLayananSeeder extends Seeder
{
    public function run()
    {
        // Jika menggunakan MySQL dan ada foreign key constraints, 
        // uncomment dua baris DB::statement() berikut sebelum truncate:
        //
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Kosongkan tabel dahulu (lebih aman untuk seeder).
        DB::table('m_layanan')->truncate();

        $services = [
            [
                'kode_layanan' => 'SMARTSHUTTLE',
                'nama_layanan' => 'Smart Shuttle',
                'slug' => 'smart-shuttle',
                'deskripsi_singkat' => 'Layanan transportasi shuttle yang cerdas dan terjadwal',
                'deskripsi_panjang' => 'Smart Shuttle adalah layanan transportasi shuttle yang menyediakan perjalanan terjadwal dengan rute tetap. Layanan ini cocok untuk perjalanan harian ke kantor, kampus, atau bandara.',
                'icon' => 'shuttle-icon.png',
                'logo' => 'images/smartshuttlelogo.png',
                'kategori_layanan' => 'transport',
                'status_aktif' => true,
                'urutan_tampilan' => 1,
                'meta' => ['has_schedule' => true, 'max_passengers' => 12]
            ],
            [
                'kode_layanan' => 'SMARTSEND',
                'nama_layanan' => 'Smart Send',
                'slug' => 'smart-send',
                'deskripsi_singkat' => 'Layanan pengiriman barang cepat dan terpercaya',
                'deskripsi_panjang' => 'Smart Send adalah layanan pengiriman barang door-to-door dengan sistem tracking real-time. Mendukung pengiriman dokumen, paket, dan barang-barang lainnya.',
                'icon' => 'send-icon.png',
                'logo' => 'images/lgsmartsend.png',
                'kategori_layanan' => 'logistics',
                'status_aktif' => true,
                'urutan_tampilan' => 2,
                'meta' => ['has_tracking' => true, 'supports_cod' => true]
            ],
            [
                'kode_layanan' => 'SMARTRENT',
                'nama_layanan' => 'Smart Rent',
                'slug' => 'smart-rent',
                'deskripsi_singkat' => 'Layanan penyewaan kendaraan dengan sistem fleksibel',
                'deskripsi_panjang' => 'Smart Rent menyediakan layanan penyewaan kendaraan dengan pilihan durasi yang fleksibel, mulai dari harian hingga bulanan. Tersedia berbagai jenis kendaraan sesuai kebutuhan.',
                'icon' => 'rent-icon.png',
                'logo' => 'images/lgsmartrent.png',
                'kategori_layanan' => 'rental',
                'status_aktif' => true,
                'urutan_tampilan' => 3,
                'meta' => ['min_rent_days' => 1, 'requires_deposit' => true]
            ]
        ];

        foreach ($services as $data) {
            MLayanan::create($data);
        }

        $this->command->info('Master data layanan berhasil di-seed!');
    }
}
