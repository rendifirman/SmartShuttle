<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodePembayaranSeeder extends Seeder
{
    public function run()
    {
        $methods = [
            [
                'kode' => 'bca',
                'nama' => 'Bank BCA',
                'jenis' => 'transfer_bank',
                'nomor_rekening' => '1234567890',
                'nama_rekening' => 'PT Smart Shuttle',
                'gambar' => 'bca.png',
                'deskripsi' => 'Transfer ke Bank BCA',
                'biaya_admin' => 0,
                'estimasi_waktu' => 15,
                'aktif' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'bni',
                'nama' => 'Bank BNI',
                'jenis' => 'transfer_bank',
                'nomor_rekening' => '0987654321',
                'nama_rekening' => 'PT Smart Shuttle',
                'gambar' => 'bni.png',
                'deskripsi' => 'Transfer ke Bank BNI',
                'biaya_admin' => 0,
                'estimasi_waktu' => 15,
                'aktif' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'bri',
                'nama' => 'Bank BRI',
                'jenis' => 'transfer_bank',
                'nomor_rekening' => '1122334455',
                'nama_rekening' => 'PT Smart Shuttle',
                'gambar' => 'bri.png',
                'deskripsi' => 'Transfer ke Bank BRI',
                'biaya_admin' => 0,
                'estimasi_waktu' => 15,
                'aktif' => true,
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'mandiri',
                'nama' => 'Bank Mandiri',
                'jenis' => 'transfer_bank',
                'nomor_rekening' => '5566778899',
                'nama_rekening' => 'PT Smart Shuttle',
                'gambar' => 'mandiri.png',
                'deskripsi' => 'Transfer ke Bank Mandiri',
                'biaya_admin' => 0,
                'estimasi_waktu' => 15,
                'aktif' => true,
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('metode_pembayaran')->insert($methods);
    }
}
