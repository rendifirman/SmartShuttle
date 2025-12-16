<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KebijakanPrivasi;

class KebijakanPrivasiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kp_kode' => 'kp_pengguna',
                'kp_judul' => 'Kebijakan Privasi Pengguna SmartShuttle',
                'kp_konten_html' => '<div class="prose max-w-none">
                    <p class="text-lg font-medium mb-4">SmartShuttle berkomitmen melindungi privasi dan data pribadi Anda.</p>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">1. Data yang Kami Kumpulkan</h4>
                        <ul class="space-y-2">
                            <li>✓ Data pribadi: nama, email, nomor telepon, alamat</li>
                            <li>✓ Data akun: username, password, foto profil</li>
                            <li>✓ Data transaksi: riwayat pemesanan, pembayaran</li>
                            <li>✓ Data lokasi: alamat jemput, tujuan, riwayat perjalanan</li>
                            <li>✓ Data teknis: IP address, browser, perangkat</li>
                            <li>✓ Data komunikasi: chat dengan driver, feedback</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">2. Penggunaan Data</h4>
                        <ul class="space-y-2">
                            <li>✓ Memproses pemesanan dan pembayaran</li>
                            <li>✓ Meningkatkan kualitas layanan dan pengalaman pengguna</li>
                            <li>✓ Komunikasi terkait transaksi dan promosi</li>
                            <li>✓ Analisis data untuk pengembangan sistem</li>
                            <li>✓ Pemenuhan kewajiban hukum dan regulasi</li>
                            <li>✓ Keamanan akun dan pencegahan fraud</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">3. Perlindungan Data</h4>
                        <ul class="space-y-2">
                            <li>✓ Data disimpan dengan enkripsi tingkat tinggi</li>
                            <li>✓ Akses data dibatasi hanya untuk yang berwenang</li>
                            <li>✓ Regular security audit dan update sistem</li>
                            <li>✓ Backup data dilakukan secara berkala</li>
                            <li>✓ Tidak menjual atau menyewakan data ke pihak ketiga</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">4. Berbagi Data</h4>
                        <ul class="space-y-2">
                            <li>✓ Driver: informasi untuk proses penjemputan</li>
                            <li>✓ Mitra pembayaran: untuk verifikasi transaksi</li>
                            <li>✓ Pihak berwenang: jika diwajibkan oleh hukum</li>
                            <li>✓ Mitra operasional: untuk kelancaran layanan</li>
                            <li>✓ Data anonim untuk keperluan analisis</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">5. Hak Anda</h4>
                        <ul class="space-y-2">
                            <li>✓ Hak akses dan koreksi data pribadi</li>
                            <li>✓ Hak penghapusan data (right to be forgotten)</li>
                            <li>✓ Hak menarik persetujuan pengolahan data</li>
                            <li>✓ Hak menerima informasi penggunaan data</li>
                            <li>✓ Hak mengajukan keluhan terkait privasi</li>
                        </ul>
                    </div>

                    <div class="mt-8 p-4 bg-green-50 rounded-lg border border-green-100">
                        <p class="text-sm text-green-700">
                            <strong>Kontak:</strong> Untuk pertanyaan terkait privasi, hubungi privacy@smartshuttle.com atau (021) 1234-5678
                        </p>
                        <p class="text-xs text-green-600 mt-2">
                            <em>Terakhir diperbarui: 1 Juni 2024</em>
                        </p>
                    </div>
                </div>',
                'kp_versi' => '3.0',
                'kp_tanggal_efektif' => '2024-06-01',
                'kp_status_aktif' => true,
            ],

            [
                'kp_kode' => 'kp_driver',
                'kp_judul' => 'Kebijakan Privasi Driver SmartShuttle',
                'kp_konten_html' => '<div class="prose max-w-none">
                    <p class="text-lg font-medium mb-4">Kebijakan privasi khusus untuk Driver Mitra SmartShuttle:</p>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Data Driver</h4>
                        <ul class="space-y-2">
                            <li>✓ Data pribadi: KTP, SIM, alamat, kontak darurat</li>
                            <li>✓ Data kendaraan: STNK, foto kendaraan, KIR</li>
                            <li>✓ Data bank: untuk pembayaran komisi</li>
                            <li>✓ Data operasional: rating, riwayat perjalanan</li>
                            <li>✓ Data lokasi real-time selama online</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Keamanan & Perlindungan</h4>
                        <ul class="space-y-2">
                            <li>✓ Data driver tidak dibagikan ke penumpang</li>
                            <li>✓ Sistem rating terproteksi dan fair</li>
                            <li>✓ Data keuangan dienkripsi dengan aman</li>
                            <li>✓ Akses log aktivitas untuk keamanan</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Hak Driver</h4>
                        <ul class="space-y-2">
                            <li>✓ Hak untuk mengetahui data yang disimpan</li>
                            <li>✓ Hak untuk mengupdate data pribadi</li>
                            <li>✓ Hak untuk menghapus akun dan data</li>
                            <li>✓ Hak untuk tidak membagikan data tertentu</li>
                        </ul>
                    </div>
                </div>',
                'kp_versi' => '2.0',
                'kp_tanggal_efektif' => '2024-05-15',
                'kp_status_aktif' => true,
            ],

            [
                'kp_kode' => 'kp_website',
                'kp_judul' => 'Kebijakan Privasi Website SmartShuttle',
                'kp_konten_html' => '<div class="prose max-w-none">
                    <p class="text-lg font-medium mb-4">Kebijakan privasi untuk pengunjung website SmartShuttle:</p>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Cookies & Tracking</h4>
                        <ul class="space-y-2">
                            <li>✓ Kami menggunakan cookies untuk pengalaman terbaik</li>
                            <li>✓ Cookies membantu mengingat preferensi Anda</li>
                            <li>✓ Analytics untuk memahami perilaku pengunjung</li>
                            <li>✓ Anda dapat menonaktifkan cookies di browser</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Data Pengunjung</h4>
                        <ul class="space-y-2">
                            <li>✓ Alamat IP, browser, sistem operasi</li>
                            <li>✓ Halaman yang dikunjungi dan durasi</li>
                            <li>✓ Data formulir kontak yang diisi</li>
                            <li>✓ Data newsletter jika berlangganan</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Keamanan Website</h4>
                        <ul class="space-y-2">
                            <li>✓ SSL encryption untuk data sensitif</li>
                            <li>✓ Firewall dan proteksi DDoS</li>
                            <li>✓ Regular security update</li>
                            <li>✓ Monitoring 24/7 untuk keamanan</li>
                        </ul>
                    </div>
                </div>',
                'kp_versi' => '1.5',
                'kp_tanggal_efektif' => '2024-04-10',
                'kp_status_aktif' => true,
            ],
        ];

        foreach ($data as $item) {
            KebijakanPrivasi::updateOrCreate(
                ['kp_kode' => $item['kp_kode']],
                $item
            );
        }

        $this->command->info('✅ Seeder KebijakanPrivasi berhasil dijalankan!');
        $this->command->info('📋 Total data: ' . count($data));
    }
}
