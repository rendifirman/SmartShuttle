<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterTarif;
use App\Models\Rute;
use Carbon\Carbon;

class MasterTarifSeeder extends Seeder
{
    public function run(): void
    {
        $tarifs = [
            // Tarif Penumpang
            [
                'kode_tarif' => 'TP-001',
                'nama_tarif' => 'Tarif Penumpang Ekonomi',
                'jenis_tarif' => 'penumpang',
                'sk_tarif' => 'SK/TP/2024/001',
                'harga_dasar' => 1000,
                'harga_minimum' => 500,
                'harga_maksimum' => 3000,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Tarif standar untuk penumpang ekonomi',
                'catatan' => 'Berlaku untuk rute dalam kota',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'TP-002',
                'nama_tarif' => 'Tarif Penumpang VIP',
                'jenis_tarif' => 'penumpang',
                'sk_tarif' => 'SK/TP/2024/002',
                'harga_dasar' => 2000,
                'harga_minimum' => 1500,
                'harga_maksimum' => 5000,
                'diskon_persentase' => 5,
                'diskon_nominal' => 0,
                'keterangan' => 'Tarif premium untuk penumpang VIP',
                'catatan' => 'Termasuk layanan prioritas dan snack',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'TP-003',
                'nama_tarif' => 'Tarif Penumpang Antar Kota',
                'jenis_tarif' => 'penumpang',
                'sk_tarif' => 'SK/TP/2024/003',
                'harga_dasar' => 3000,
                'harga_minimum' => 2000,
                'harga_maksimum' => 10000,
                'diskon_persentase' => 0,
                'diskon_nominal' => 500,
                'keterangan' => 'Tarif untuk perjalanan antar kota',
                'catatan' => 'Berlaku untuk jarak > 50km',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],

            // Tarif Paket
            [
                'kode_tarif' => 'TK-001',
                'nama_tarif' => 'Tarif Paket Ringan (< 5kg)',
                'jenis_tarif' => 'paket',
                'sk_tarif' => 'SK/TK/2024/001',
                'harga_dasar' => 500,
                'harga_minimum' => 300,
                'harga_maksimum' => 1500,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Tarif untuk paket ringan',
                'catatan' => 'Maksimal berat 5kg, dimensi 30x20x15cm',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'TK-002',
                'nama_tarif' => 'Tarif Paket Berat (5-20kg)',
                'jenis_tarif' => 'paket',
                'sk_tarif' => 'SK/TK/2024/002',
                'harga_dasar' => 1000,
                'harga_minimum' => 600,
                'harga_maksimum' => 3000,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Tarif untuk paket berat',
                'catatan' => 'Berat 5-20kg, dimensi maksimal 50x40x30cm',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'TK-003',
                'nama_tarif' => 'Tarif Paket Ekspres',
                'jenis_tarif' => 'paket',
                'sk_tarif' => 'SK/TK/2024/003',
                'harga_dasar' => 1500,
                'harga_minimum' => 1000,
                'harga_maksimum' => 4000,
                'diskon_persentase' => 10,
                'diskon_nominal' => 0,
                'keterangan' => 'Tarif ekspres dengan pengiriman cepat',
                'catatan' => 'Garansi pengiriman dalam 24 jam',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],

            // Tarif Cargo
            [
                'kode_tarif' => 'TC-001',
                'nama_tarif' => 'Tarif Cargo Kecil',
                'jenis_tarif' => 'cargo',
                'sk_tarif' => 'SK/TC/2024/001',
                'harga_dasar' => 1000,
                'harga_minimum' => 750,
                'harga_maksimum' => 3000,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Tarif untuk cargo kecil',
                'catatan' => 'Berat 20-100kg, volume maksimal 1m³',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'TC-002',
                'nama_tarif' => 'Tarif Cargo Sedang',
                'jenis_tarif' => 'cargo',
                'sk_tarif' => 'SK/TC/2024/002',
                'harga_dasar' => 2000,
                'harga_minimum' => 1500,
                'harga_maksimum' => 7000,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Tarif untuk cargo sedang',
                'catatan' => 'Berat 100-500kg, volume 1-5m³',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'TC-003',
                'nama_tarif' => 'Tarif Cargo Besar',
                'jenis_tarif' => 'cargo',
                'sk_tarif' => 'SK/TC/2024/003',
                'harga_dasar' => 3000,
                'harga_minimum' => 2000,
                'harga_maksimum' => 10000,
                'diskon_persentase' => 5,
                'diskon_nominal' => 0,
                'keterangan' => 'Tarif untuk cargo besar',
                'catatan' => 'Berat >500kg, volume >5m³',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],

            // Tarif Charter
            [
                'kode_tarif' => 'TCH-001',
                'nama_tarif' => 'Tarif Charter Harian',
                'jenis_tarif' => 'charter',
                'sk_tarif' => 'SK/TCH/2024/001',
                'harga_dasar' => 3000,
                'harga_minimum' => 2000,
                'harga_maksimum' => 10000,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Tarif charter per hari',
                'catatan' => 'Sewa shuttle per hari penuh, termasuk driver',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'TCH-002',
                'nama_tarif' => 'Tarif Charter Event',
                'jenis_tarif' => 'charter',
                'sk_tarif' => 'SK/TCH/2024/002',
                'harga_dasar' => 5000,
                'harga_minimum' => 3000,
                'harga_maksimum' => 15000,
                'diskon_persentase' => 10,
                'diskon_nominal' => 0,
                'keterangan' => 'Tarif charter untuk event khusus',
                'catatan' => 'Untuk acara perusahaan atau event besar',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'TCH-003',
                'nama_tarif' => 'Tarif Charter VIP',
                'jenis_tarif' => 'charter',
                'sk_tarif' => 'SK/TCH/2024/003',
                'harga_dasar' => 10000,
                'harga_minimum' => 5000,
                'harga_maksimum' => 30000,
                'diskon_persentase' => 0,
                'diskon_nominal' => 500,
                'keterangan' => 'Tarif charter premium',
                'catatan' => 'Layanan VIP dengan shuttle mewah',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],

            // Biaya Operasional (untuk perhitungan transaksi)
            [
                'kode_tarif' => 'OP-001',
                'nama_tarif' => 'Biaya Perawatan Shuttle',
                'jenis_tarif' => 'charter', // Menggunakan charter sebagai kategori operasional
                'sk_tarif' => 'SK/OP/2024/001',
                'harga_dasar' => 1000,
                'harga_minimum' => 500,
                'harga_maksimum' => 3000,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Biaya perawatan dan servis shuttle per trip',
                'catatan' => 'Biaya maintenance yang dibebankan per perjalanan',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'OP-002',
                'nama_tarif' => 'Biaya Driver',
                'jenis_tarif' => 'charter',
                'sk_tarif' => 'SK/OP/2024/002',
                'harga_dasar' => 1500,
                'harga_minimum' => 1000,
                'harga_maksimum' => 4000,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Biaya jasa driver per trip',
                'catatan' => 'Termasuk gaji driver dan insentif per perjalanan',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'OP-003',
                'nama_tarif' => 'Biaya Administrasi',
                'jenis_tarif' => 'charter',
                'sk_tarif' => 'SK/OP/2024/003',
                'harga_dasar' => 200,
                'harga_minimum' => 100,
                'harga_maksimum' => 500,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Biaya administrasi dan operasional',
                'catatan' => 'Biaya admin yang dibebankan per transaksi',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'OP-004',
                'nama_tarif' => 'Biaya Bahan Bakar',
                'jenis_tarif' => 'charter',
                'sk_tarif' => 'SK/OP/2024/004',
                'harga_dasar' => 500,
                'harga_minimum' => 300,
                'harga_maksimum' => 1500,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Biaya bahan bakar per trip',
                'catatan' => 'Estimasi biaya BBM berdasarkan jarak tempuh',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
            [
                'kode_tarif' => 'OP-005',
                'nama_tarif' => 'Biaya Asuransi',
                'jenis_tarif' => 'charter',
                'sk_tarif' => 'SK/OP/2024/005',
                'harga_dasar' => 300,
                'harga_minimum' => 100,
                'harga_maksimum' => 500,
                'diskon_persentase' => 0,
                'diskon_nominal' => 0,
                'keterangan' => 'Biaya asuransi per trip',
                'catatan' => 'Biaya premi asuransi yang dibebankan per perjalanan',
                'tanggal_berlaku' => Carbon::now()->startOfYear(),
                'tanggal_kadaluarsa' => Carbon::now()->endOfYear(),
                'status' => 'aktif',
            ],
        ];

        foreach ($tarifs as $tarif) {
            MasterTarif::firstOrCreate(
                ['kode_tarif' => $tarif['kode_tarif']],
                $tarif
            );
        }

        // Attach operational fees (Driver, Administrasi, Bahan Bakar) as default to every rute
        $opKode = ['OP-002', 'OP-003', 'OP-004'];
        $opTarifIds = MasterTarif::whereIn('kode_tarif', $opKode)->pluck('id')->toArray();

        if (!empty($opTarifIds)) {
            $rutes = Rute::all();
            foreach ($rutes as $rute) {
                $rute->masterTarifs()->syncWithoutDetaching($opTarifIds);
            }
        }

        $this->command->info('MasterTarifSeeder completed successfully. ' . count($tarifs) . ' tarif records created.');
    }
}
