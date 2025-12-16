<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        $promos = [
            [
                'kode_promo' => 'DISKON10',
                'nama_promo' => 'Diskon 10%',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 10.00,
                'maksimal_diskon' => 20000.00,
                'minimal_pembelian' => 100000.00,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(3),
                'kuota' => 100,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Dapatkan diskon 10% untuk pembelian minimal Rp 100.000'
            ],
            [
                'kode_promo' => 'WELCOME15',
                'nama_promo' => 'Welcome 15%',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 15.00,
                'maksimal_diskon' => 30000.00,
                'minimal_pembelian' => 150000.00,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(6),
                'kuota' => 50,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon khusus new customer 15%'
            ],
            [
                'kode_promo' => 'SHUTTLE20',
                'nama_promo' => 'Shuttle 20%',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 20.00,
                'maksimal_diskon' => 50000.00,
                'minimal_pembelian' => 200000.00,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(1),
                'kuota' => 30,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Promo khusus shuttle 20%'
            ],
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }
    }
}