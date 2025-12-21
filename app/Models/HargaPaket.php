<?php

namespace Database\Seeders;

use App\Models\HargaPaket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HargaPaketSeeder extends Seeder
{
    public function run(): void
    {
        $dataHarga = [
            // Rute Jakarta - Bandung
            [
                'kode_harga' => 'HP-JKT-BDG-001',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Bandung',
                'harga_per_kg' => 15000,
                'harga_minimum' => 20000,
                'harga_volume_per_cm3' => 0.0025,
                'estimasi_hari_min' => 1,
                'estimasi_hari_max' => 2,
                'status' => 'aktif',
                'keterangan' => 'Reguler - Estimasi 1-2 hari kerja'
            ],
            [
                'kode_harga' => 'HP-JKT-BDG-002',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Bandung',
                'harga_per_kg' => 20000,
                'harga_minimum' => 30000,
                'harga_volume_per_cm3' => 0.0033,
                'estimasi_hari_min' => 1,
                'estimasi_hari_max' => 1,
                'status' => 'aktif',
                'keterangan' => 'Express - Estimasi 1 hari kerja'
            ],
            // Rute Bandung - Jakarta
            [
                'kode_harga' => 'HP-BDG-JKT-001',
                'kota_asal' => 'Bandung',
                'kota_tujuan' => 'Jakarta',
                'harga_per_kg' => 15000,
                'harga_minimum' => 20000,
                'harga_volume_per_cm3' => 0.0025,
                'estimasi_hari_min' => 1,
                'estimasi_hari_max' => 2,
                'status' => 'aktif',
                'keterangan' => 'Reguler - Estimasi 1-2 hari kerja'
            ],
            // Rute Jakarta - Bekasi
            [
                'kode_harga' => 'HP-JKT-BKS-001',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Bekasi',
                'harga_per_kg' => 10000,
                'harga_minimum' => 15000,
                'harga_volume_per_cm3' => 0.0017,
                'estimasi_hari_min' => 1,
                'estimasi_hari_max' => 1,
                'status' => 'aktif',
                'keterangan' => 'Same Day - Estimasi 1 hari kerja'
            ],
            // Rute Jakarta - Yogyakarta
            [
                'kode_harga' => 'HP-JKT-YOG-001',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Yogyakarta',
                'harga_per_kg' => 25000,
                'harga_minimum' => 35000,
                'harga_volume_per_cm3' => 0.0042,
                'estimasi_hari_min' => 2,
                'estimasi_hari_max' => 3,
                'status' => 'aktif',
                'keterangan' => 'Reguler - Estimasi 2-3 hari kerja'
            ],
            // Rute Jakarta - Bali
            [
                'kode_harga' => 'HP-JKT-BAL-001',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Bali',
                'harga_per_kg' => 35000,
                'harga_minimum' => 50000,
                'harga_volume_per_cm3' => 0.0058,
                'estimasi_hari_min' => 3,
                'estimasi_hari_max' => 4,
                'status' => 'aktif',
                'keterangan' => 'Reguler - Estimasi 3-4 hari kerja'
            ],
            // Rute Depok - Tangerang
            [
                'kode_harga' => 'HP-DPK-TGR-001',
                'kota_asal' => 'Depok',
                'kota_tujuan' => 'Tangerang',
                'harga_per_kg' => 12000,
                'harga_minimum' => 18000,
                'harga_volume_per_cm3' => 0.0020,
                'estimasi_hari_min' => 1,
                'estimasi_hari_max' => 2,
                'status' => 'aktif',
                'keterangan' => 'Reguler - Estimasi 1-2 hari kerja'
            ],
            // Rute Semarang - Yogyakarta
            [
                'kode_harga' => 'HP-SMG-YOG-001',
                'kota_asal' => 'Semarang',
                'kota_tujuan' => 'Yogyakarta',
                'harga_per_kg' => 18000,
                'harga_minimum' => 25000,
                'harga_volume_per_cm3' => 0.0030,
                'estimasi_hari_min' => 1,
                'estimasi_hari_max' => 2,
                'status' => 'aktif',
                'keterangan' => 'Reguler - Estimasi 1-2 hari kerja'
            ],
            // Rute Bandung - Yogyakarta
            [
                'kode_harga' => 'HP-BDG-YOG-001',
                'kota_asal' => 'Bandung',
                'kota_tujuan' => 'Yogyakarta',
                'harga_per_kg' => 28000,
                'harga_minimum' => 40000,
                'harga_volume_per_cm3' => 0.0047,
                'estimasi_hari_min' => 2,
                'estimasi_hari_max' => 3,
                'status' => 'aktif',
                'keterangan' => 'Reguler - Estimasi 2-3 hari kerja'
            ],
            // Rute Bekasi - Bandung
            [
                'kode_harga' => 'HP-BKS-BDG-001',
                'kota_asal' => 'Bekasi',
                'kota_tujuan' => 'Bandung',
                'harga_per_kg' => 14000,
                'harga_minimum' => 20000,
                'harga_volume_per_cm3' => 0.0023,
                'estimasi_hari_min' => 1,
                'estimasi_hari_max' => 2,
                'status' => 'aktif',
                'keterangan' => 'Reguler - Estimasi 1-2 hari kerja'
            ],
        ];

        foreach ($dataHarga as $harga) {
            HargaPaket::create($harga);
        }
    }
}
