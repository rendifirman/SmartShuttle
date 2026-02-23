<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipment;
use Carbon\Carbon;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shipments = [
            [
                'kode_resi' => 'SS-' . date('Ymd') . '-00001',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Bandung',
                'panjang' => 30.5,
                'lebar' => 20.0,
                'tinggi' => 15.0,
                'harga' => 30.5 * 20.0 * 15.0 * 6000,
                'nama_pengirim' => 'Budi Santoso',
                'telepon_pengirim' => '081234567890',
                'nama_penerima' => 'Siti Rahayu',
                'telepon_penerima' => '081298765432',
                'alamat_tujuan' => 'Jl. Merdeka No. 123, Bandung',
                'status' => 'diproses',
                'catatan' => 'Hati-hati, barang pecah belah',
                'tanggal_dibuat' => Carbon::now()->subDays(2),
            ],
            [
                'kode_resi' => 'SS-' . date('Ymd') . '-00002',
                'kota_asal' => 'Bandung',
                'kota_tujuan' => 'Surabaya',
                'panjang' => 50.0,
                'lebar' => 40.0,
                'tinggi' => 30.0,
                'harga' => 50.0 * 40.0 * 30.0 * 6000,
                'nama_pengirim' => 'Andi Wijaya',
                'telepon_pengirim' => '082112345678',
                'nama_penerima' => 'Dewi Lestari',
                'telepon_penerima' => '082176543210',
                'alamat_tujuan' => 'Jl. Sudirman No. 45, Surabaya',
                'status' => 'dalam_perjalanan',
                'catatan' => 'Prioritas 1',
                'tanggal_dibuat' => Carbon::now()->subDays(1),
                'tanggal_dikirim' => Carbon::now()->subHours(12),
            ],
            [
                'kode_resi' => 'SS-' . date('Ymd') . '-00003',
                'kota_asal' => 'Surabaya',
                'kota_tujuan' => 'Jakarta',
                'panjang' => 25.0,
                'lebar' => 15.0,
                'tinggi' => 10.0,
                'harga' => 25.0 * 15.0 * 10.0 * 6000,
                'nama_pengirim' => 'Rina Melati',
                'telepon_pengirim' => '083812345678',
                'nama_penerima' => 'Fajar Setiawan',
                'telepon_penerima' => '083876543210',
                'alamat_tujuan' => 'Jl. Thamrin No. 78, Jakarta',
                'status' => 'terkirim',
                'catatan' => 'Sudah dibayar lunas',
                'tanggal_dibuat' => Carbon::now()->subDays(3),
                'tanggal_dikirim' => Carbon::now()->subDays(2),
                'tanggal_diterima' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($shipments as $shipment) {
            Shipment::create($shipment);
        }
    }
}