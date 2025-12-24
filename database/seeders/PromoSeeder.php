<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PromoSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        $promos = [
            [
                'kode_promo' => 'PROMO1',
                'nama_promo' => 'Promo Awal Tahun',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 30.00,
                'maksimal_diskon' => 50000.00,
                'minimal_pembelian' => 100000.00,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(3),
                'kuota' => 100,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 30% untuk semua layanan shuttle selama bulan Januari',
                'gambar' => 'images/promo/bali.jpg',
                'tipe_promo' => 'shuttle'
            ],
            [
                'kode_promo' => 'PROMO2',
                'nama_promo' => 'Paket Keluarga',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 25.00,
                'maksimal_diskon' => 75000.00,
                'minimal_pembelian' => 150000.00,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(6),
                'kuota' => 50,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 25% untuk pemesanan tiket shuttle minimal 4 orang',
                'gambar' => 'images/promo/promo2.jpg',
                'tipe_promo' => 'shuttle'
            ],
            [
                'kode_promo' => 'PROMO3',
                'nama_promo' => 'Member Baru',
                'jenis_diskon' => 'nominal',
                'nilai_diskon' => 20000.00, // 2 tiket gratis dihitung sebagai nominal
                'maksimal_diskon' => 20000.00,
                'minimal_pembelian' => 0,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addYear(),
                'kuota' => null, // Unlimited
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Dapatkan 2 tiket gratis untuk pendaftaran member baru',
                'gambar' => 'images/promo/promo3.jpg',
                'tipe_promo' => 'all'
            ],
            [
                'kode_promo' => 'PROMO4',
                'nama_promo' => 'Weekend Special',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 20.00,
                'maksimal_diskon' => 30000.00,
                'minimal_pembelian' => 50000.00,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(12),
                'kuota' => 200,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 20% untuk perjalanan hari Sabtu dan Minggu',
                'gambar' => 'images/promo/promo4.jpg',
                'tipe_promo' => 'shuttle'
            ],
            [
                'kode_promo' => 'PROMO5',
                'nama_promo' => 'Kirim Paket Murah',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 15.00,
                'maksimal_diskon' => 25000.00,
                'minimal_pembelian' => 50000.00,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(2),
                'kuota' => 150,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 15% untuk pengiriman paket pertama kali',
                'gambar' => 'images/promo/promo5.jpg',
                'tipe_promo' => 'paket'
            ],
            [
                'kode_promo' => 'PROMO6',
                'nama_promo' => 'Sewa Armada Hemat',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 10.00,
                'maksimal_diskon' => 100000.00,
                'minimal_pembelian' => 500000.00,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(4),
                'kuota' => 30,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 10% untuk sewa armada minimal 3 hari',
                'gambar' => 'images/promo/bali.jpg',
                'tipe_promo' => 'sewa'
            ],
        ];

        foreach ($promos as $promo) {
            Promo::updateOrCreate(
                ['kode_promo' => $promo['kode_promo']],
                $promo
            );
        }
    }
}
