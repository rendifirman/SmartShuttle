<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterHarga;

class MasterHargaSeeder extends Seeder
{
    public function run()
    {
        // Data tarif berat paket
        MasterHarga::create([
            'kode_harga' => 'TARIF-BERAT-001',
            'nama_harga' => 'Tarif Berat Paket',
            'jenis_harga' => 'berat',
            'berat_pertama' => 5,
            'harga_berat_pertama' => 7000,
            'harga_berat_berikutnya' => 2000,
            'kelipatan_jarak' => null,
            'harga_per_kelipatan' => null,
            'tanggal_berlaku' => now(),
            'tanggal_kadaluarsa' => null,
            'status_aktif' => true,
        ]);

        // Data tarif jarak
        MasterHarga::create([
            'kode_harga' => 'TARIF-JARAK-001',
            'nama_harga' => 'Tarif Jarak Per 10km',
            'jenis_harga' => 'jarak',
            'berat_pertama' => null,
            'harga_berat_pertama' => null,
            'harga_berat_berikutnya' => null,
            'kelipatan_jarak' => 10,
            'harga_per_kelipatan' => 2000,
            'tanggal_berlaku' => now(),
            'tanggal_kadaluarsa' => null,
            'status_aktif' => true,
        ]);
    }
}