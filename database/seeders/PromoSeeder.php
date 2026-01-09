<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PromoSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        $promos = [
            // 1. PROMO KELUARGA - hanya aktif jika jumlah tiket ≥ 4
            [
                'kode_promo' => 'PROMO2',
                'nama_promo' => 'Paket Keluarga',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 25.00,
                'maksimal_diskon' => 75000.00,
                'minimal_pembelian' => 150000.00,
                'min_tiket' => 4, // Minimal 4 tiket untuk promo keluarga
                'khusus_member' => false, // Bisa digunakan non-member
                'kategori_promo' => 'keluarga', // Kategori keluarga
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(6),
                'kuota' => 50,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 25% untuk pemesanan tiket shuttle minimal 4 orang',
                'pesan_error' => 'Promo keluarga hanya berlaku untuk minimal 4 tiket',
                'gambar' => 'images/promo/promo2.jpg',
                'tipe_promo' => 'shuttle'
            ],

            // 2. PROMO MEMBERSHIP - hanya untuk member
            [
                'kode_promo' => 'PROMO3',
                'nama_promo' => 'Diskon Member',
                'jenis_diskon' => 'nominal',
                'nilai_diskon' => 20000.00,
                'maksimal_diskon' => 20000.00,
                'minimal_pembelian' => 0,
                'min_tiket' => null, // Tidak ada minimal tiket
                'khusus_member' => true, // Hanya untuk member
                'kategori_promo' => 'membership', // Kategori membership
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addYear(),
                'kuota' => null, // Unlimited
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Potongan Rp 20.000 khusus untuk member aktif',
                'pesan_error' => 'Promo membership hanya dapat digunakan oleh member',
                'gambar' => 'images/promo/promo3.jpg',
                'tipe_promo' => 'all'
            ],

            // 3. PROMO KELUARGA MEMBER - kombinasi keluarga + member
            [
                'kode_promo' => 'MEMBERKELUARGA',
                'nama_promo' => 'Promo Keluarga Member',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 30.00,
                'maksimal_diskon' => 100000.00,
                'minimal_pembelian' => 200000.00,
                'min_tiket' => 3, // Minimal 3 tiket
                'khusus_member' => true, // Hanya untuk member
                'kategori_promo' => 'keluarga', // Kategori keluarga
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(6),
                'kuota' => 100,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 30% khusus member dengan minimal 3 tiket',
                'pesan_error' => 'Promo ini khusus member dengan minimal 3 tiket',
                'gambar' => 'images/promo/bali.jpg',
                'tipe_promo' => 'shuttle'
            ],

            // 4. PROMO UMUM - bisa digunakan semua orang
            [
                'kode_promo' => 'PROMO1',
                'nama_promo' => 'Promo Awal Tahun',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 30.00,
                'maksimal_diskon' => 50000.00,
                'minimal_pembelian' => 100000.00,
                'min_tiket' => null, // Tidak ada minimal tiket
                'khusus_member' => false, // Bisa digunakan non-member
                'kategori_promo' => 'umum', // Kategori umum
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(3),
                'kuota' => 100,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 30% untuk semua layanan shuttle selama bulan Januari',
                'pesan_error' => 'Minimal pembelian Rp 100.000',
                'gambar' => 'images/promo/bali.jpg',
                'tipe_promo' => 'shuttle'
            ],

            // 5. PROMO UMUM - weekend special
            [
                'kode_promo' => 'PROMO4',
                'nama_promo' => 'Weekend Special',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 20.00,
                'maksimal_diskon' => 30000.00,
                'minimal_pembelian' => 50000.00,
                'min_tiket' => null,
                'khusus_member' => false,
                'kategori_promo' => 'umum',
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(12),
                'kuota' => 200,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 20% untuk perjalanan hari Sabtu dan Minggu',
                'pesan_error' => 'Minimal pembelian Rp 50.000',
                'gambar' => 'images/promo/promo4.jpg',
                'tipe_promo' => 'shuttle'
            ],

            // 6. PROMO KIRIM PAKET (umum)
            [
                'kode_promo' => 'PROMO5',
                'nama_promo' => 'Kirim Paket Murah',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 15.00,
                'maksimal_diskon' => 25000.00,
                'minimal_pembelian' => 50000.00,
                'min_tiket' => null,
                'khusus_member' => false,
                'kategori_promo' => 'umum',
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(2),
                'kuota' => 150,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 15% untuk pengiriman paket pertama kali',
                'pesan_error' => 'Minimal pembelian Rp 50.000',
                'gambar' => 'images/promo/promo5.jpg',
                'tipe_promo' => 'paket'
            ],

            // 7. PROMO SEWA ARMADA (umum)
            [
                'kode_promo' => 'PROMO6',
                'nama_promo' => 'Sewa Armada Hemat',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 10.00,
                'maksimal_diskon' => 100000.00,
                'minimal_pembelian' => 500000.00,
                'min_tiket' => null,
                'khusus_member' => false,
                'kategori_promo' => 'umum',
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(4),
                'kuota' => 30,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'Diskon 10% untuk sewa armada minimal 3 hari',
                'pesan_error' => 'Minimal pembelian Rp 500.000',
                'gambar' => 'images/promo/bali.jpg',
                'tipe_promo' => 'sewa'
            ],

            // 8. PROMO MEMBERSHIP DISCOUNT (hanya member)
            [
                'kode_promo' => 'MEMBER15',
                'nama_promo' => 'Member 15% Off',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 15.00,
                'maksimal_diskon' => 50000.00,
                'minimal_pembelian' => 0,
                'min_tiket' => null,
                'khusus_member' => true,
                'kategori_promo' => 'membership',
                'tanggal_mulai' => now()->subDays(10),
                'tanggal_berakhir' => now()->addMonths(6),
                'kuota' => 200,
                'terpakai' => 45,
                'status' => true,
                'deskripsi' => 'Diskon 15% untuk semua transaksi member',
                'pesan_error' => 'Promo ini hanya untuk member aktif',
                'gambar' => 'images/promo/promo2.jpg',
                'tipe_promo' => 'all'
            ],

            // 9. PROMO KELUARGA BESAR (min 5 tiket)
            [
                'kode_promo' => 'KELUARGABESAR',
                'nama_promo' => 'Promo Keluarga Besar',
                'jenis_diskon' => 'nominal',
                'nilai_diskon' => 50000.00,
                'maksimal_diskon' => 50000.00,
                'minimal_pembelian' => 250000.00,
                'min_tiket' => 5,
                'khusus_member' => false,
                'kategori_promo' => 'keluarga',
                'tanggal_mulai' => now()->subDays(15),
                'tanggal_berakhir' => now()->addMonths(3),
                'kuota' => 80,
                'terpakai' => 22,
                'status' => true,
                'deskripsi' => 'Potongan Rp 50.000 untuk pemesanan minimal 5 tiket',
                'pesan_error' => 'Promo keluarga besar minimal 5 tiket',
                'gambar' => 'images/promo/promo4.jpg',
                'tipe_promo' => 'shuttle'
            ],
        ];

        foreach ($promos as $promo) {
            Promo::updateOrCreate(
                ['kode_promo' => $promo['kode_promo']],
                $promo
            );
        }

        // Tambahkan data untuk testing berbagai skenario
        $this->createTestPromos();
    }

    /**
     * Buat promo khusus untuk testing
     */
    private function createTestPromos(): void
    {
        $testPromos = [
            // Promo untuk testing keluarga dengan minimal 3 tiket
            [
                'kode_promo' => 'TESTKELUARGA3',
                'nama_promo' => 'Test Promo Keluarga 3',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 20.00,
                'maksimal_diskon' => 40000.00,
                'minimal_pembelian' => 0,
                'min_tiket' => 3,
                'khusus_member' => false,
                'kategori_promo' => 'keluarga',
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addYear(),
                'kuota' => 1000,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'TEST: Promo keluarga aktif jika pesan ≥ 3 tiket',
                'pesan_error' => 'Promo keluarga hanya berlaku untuk minimal 3 tiket',
                'gambar' => null,
                'tipe_promo' => 'shuttle'
            ],

            // Promo untuk testing membership only
            [
                'kode_promo' => 'TESTMEMBER',
                'nama_promo' => 'Test Promo Membership',
                'jenis_diskon' => 'nominal',
                'nilai_diskon' => 15000.00,
                'maksimal_diskon' => 15000.00,
                'minimal_pembelian' => 0,
                'min_tiket' => null,
                'khusus_member' => true,
                'kategori_promo' => 'membership',
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addYear(),
                'kuota' => 1000,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'TEST: Promo hanya untuk member',
                'pesan_error' => 'Promo membership hanya dapat digunakan oleh member',
                'gambar' => null,
                'tipe_promo' => 'shuttle'
            ],

            // Promo untuk testing umum tanpa syarat
            [
                'kode_promo' => 'TESTUMUM',
                'nama_promo' => 'Test Promo Umum',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 10.00,
                'maksimal_diskon' => 20000.00,
                'minimal_pembelian' => 50000.00,
                'min_tiket' => null,
                'khusus_member' => false,
                'kategori_promo' => 'umum',
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addYear(),
                'kuota' => 1000,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'TEST: Promo umum bisa digunakan semua orang',
                'pesan_error' => 'Minimal pembelian Rp 50.000',
                'gambar' => null,
                'tipe_promo' => 'all'
            ],

            // Promo expired untuk testing
            [
                'kode_promo' => 'TESTEXPIRED',
                'nama_promo' => 'Test Promo Expired',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 50.00,
                'maksimal_diskon' => 100000.00,
                'minimal_pembelian' => 0,
                'min_tiket' => null,
                'khusus_member' => false,
                'kategori_promo' => 'umum',
                'tanggal_mulai' => now()->subMonths(3),
                'tanggal_berakhir' => now()->subDays(1), // Sudah expired
                'kuota' => 100,
                'terpakai' => 0,
                'status' => true,
                'deskripsi' => 'TEST: Promo sudah kadaluarsa',
                'pesan_error' => 'Promo sudah kadaluarsa',
                'gambar' => null,
                'tipe_promo' => 'shuttle'
            ],

            // Promo kuota habis untuk testing
            [
                'kode_promo' => 'TESTKUOTAHABIS',
                'nama_promo' => 'Test Promo Kuota Habis',
                'jenis_diskon' => 'persentase',
                'nilai_diskon' => 25.00,
                'maksimal_diskon' => 50000.00,
                'minimal_pembelian' => 0,
                'min_tiket' => null,
                'khusus_member' => false,
                'kategori_promo' => 'umum',
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addMonths(3),
                'kuota' => 10,
                'terpakai' => 10, // Kuota sudah terpakai semua
                'status' => true,
                'deskripsi' => 'TEST: Promo kuota sudah habis',
                'pesan_error' => 'Kuota promo sudah habis',
                'gambar' => null,
                'tipe_promo' => 'shuttle'
            ],
        ];

        foreach ($testPromos as $promo) {
            Promo::updateOrCreate(
                ['kode_promo' => $promo['kode_promo']],
                $promo
            );
        }
    }
}