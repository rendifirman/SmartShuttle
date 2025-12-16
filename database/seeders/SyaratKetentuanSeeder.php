<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SyaratKetentuan;

class SyaratKetentuanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'sk_kode' => 'sk_pengguna',
                'sk_judul' => 'Syarat dan Ketentuan Pengguna SmartShuttle',
                'sk_tipe' => 'pengguna',
                'sk_konten_html' => '<div class="prose max-w-none">
                    <p class="text-lg font-medium mb-4">Dengan mendaftar akun SmartShuttle, Anda menyetujui ketentuan berikut:</p>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">1. Akun Pengguna</h4>
                        <ul class="space-y-2">
                            <li>✓ Anda bertanggung jawab atas keamanan akun dan password</li>
                            <li>✓ Informasi yang diberikan harus akurat dan terbaru</li>
                            <li>✓ SmartShuttle berhak menangguhkan akun yang melanggar ketentuan</li>
                            <li>✓ Minimal usia pengguna adalah 17 tahun</li>
                            <li>✓ Satu pengguna hanya boleh memiliki satu akun</li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">2. Penggunaan Layanan</h4>
                        <ul class="space-y-2">
                            <li>✓ Layanan digunakan sesuai tujuan yang telah ditentukan</li>
                            <li>✓ Dilarang melakukan aktivitas ilegal melalui layanan kami</li>
                            <li>✓ Menghormati semua pengguna, driver, dan staf SmartShuttle</li>
                            <li>✓ Tidak menyalahgunakan sistem untuk keuntungan pribadi</li>
                            <li>✓ Dilarang mengirimkan barang terlarang atau berbahaya</li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">3. Pembayaran & Pembatalan</h4>
                        <ul class="space-y-2">
                            <li>✓ Pembayaran dilakukan sesuai tarif yang berlaku saat pemesanan</li>
                            <li>✓ Pembatalan dapat dilakukan maksimal 2 jam sebelum keberangkatan</li>
                            <li>✓ Refund diproses dalam waktu 3-7 hari kerja</li>
                            <li>✓ Biaya pembatalan dikenakan sesuai kebijakan</li>
                            <li>✓ Metode pembayaran yang tersedia: transfer bank, e-wallet, QRIS</li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">4. Hak & Kewajiban</h4>
                        <ul class="space-y-2">
                            <li>✓ Pengguna berhak mendapatkan layanan sesuai yang dipesan</li>
                            <li>✓ Pengguna wajib memberikan informasi yang benar dan valid</li>
                            <li>✓ SmartShuttle berhak menolak layanan jika ditemukan pelanggaran</li>
                            <li>✓ Penyelesaian sengketa melalui jalur hukum yang berlaku</li>
                        </ul>
                    </div>
                    
                    <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <p class="text-sm text-blue-700">
                            <strong>Perhatian:</strong> Dengan mencentang persetujuan, Anda mengkonfirmasi telah membaca dan memahami seluruh ketentuan di atas.
                        </p>
                    </div>
                </div>',
                'sk_versi' => '2.0',
                'sk_tanggal_efektif' => '2024-06-01',
                'sk_status_aktif' => true,
            ],
            
            [
                'sk_kode' => 'sk_driver',
                'sk_judul' => 'Syarat dan Ketentuan Driver SmartShuttle',
                'sk_tipe' => 'driver',
                'sk_konten_html' => '<div class="prose max-w-none">
                    <p class="text-lg font-medium mb-4">Ketentuan untuk Driver Mitra SmartShuttle:</p>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Persyaratan Umum</h4>
                        <ul class="space-y-2">
                            <li>✓ Memiliki SIM A/C sesuai jenis kendaraan</li>
                            <li>✓ Usia minimal 21 tahun, maksimal 60 tahun</li>
                            <li>✓ Memiliki kendaraan dalam kondisi baik dan layak jalan</li>
                            <li>✓ Tidak memiliki catatan kriminal</li>
                            <li>✓ Bersedia mengikuti pelatihan dan briefing</li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Hak & Kewajiban Driver</h4>
                        <ul class="space-y-2">
                            <li>✓ Berhak mendapatkan komisi sesuai kesepakatan</li>
                            <li>✓ Wajib menjaga kondisi kendaraan dan kebersihan</li>
                            <li>✓ Wajib melayani penumpang dengan baik dan sopan</li>
                            <li>✓ Wajib mengikuti rute yang telah ditentukan</li>
                            <li>✓ Dilarang menaikkan tarif di luar sistem</li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Operasional & Safety</h4>
                        <ul class="space-y-2">
                            <li>✓ Wajib menggunakan seragam yang ditentukan</li>
                            <li>✓ Wajib menjaga keselamatan penumpang dan barang</li>
                            <li>✓ Wajib melaporkan insiden/kejadian selama perjalanan</li>
                            <li>✓ Dilarang membawa penumpang/barang ilegal</li>
                            <li>✓ Wajib memiliki asuransi yang berlaku</li>
                        </ul>
                    </div>
                </div>',
                'sk_versi' => '2.0',
                'sk_tanggal_efektif' => '2024-06-01',
                'sk_status_aktif' => true,
            ],
            
            [
                'sk_kode' => 'sk_mitra',
                'sk_judul' => 'Syarat dan Ketentuan Mitra Sewa Armada',
                'sk_tipe' => 'mitra',
                'sk_konten_html' => '<div class="prose max-w-none">
                    <p class="text-lg font-medium mb-4">Ketentuan untuk Mitra Sewa Armada:</p>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Persyaratan Kendaraan</h4>
                        <ul class="space-y-2">
                            <li>✓ Usia kendaraan maksimal 10 tahun</li>
                            <li>✓ Memiliki STNK dan pajak yang berlaku</li>
                            <li>✓ Kondisi kendaraan layak jalan dan nyaman</li>
                            <li>✓ Wajib memiliki KIR (jika diperlukan)</li>
                            <li>✓ Dilengkapi dengan perlengkapan keselamatan</li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Pembagian Hasil</h4>
                        <ul class="space-y-2">
                            <li>✓ Pembagian pendapatan 70:30 untuk mitra</li>
                            <li>✓ Pembayaran dilakukan setiap minggu</li>
                            <li>✓ Biaya operasional ditanggung mitra</li>
                            <li>✓ Biaya maintenance kendaraan ditanggung mitra</li>
                            <li>✓ Biaya asuransi ditanggung bersama</li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Kewajiban Mitra</h4>
                        <ul class="space-y-2">
                            <li>✓ Menyediakan kendaraan sesuai jadwal</li>
                            <li>✓ Melaporkan kondisi kendaraan secara berkala</li>
                            <li>✓ Memberikan pelayanan terbaik kepada penyewa</li>
                            <li>✓ Tidak menarik kendaraan sebelum kontrak berakhir</li>
                        </ul>
                    </div>
                </div>',
                'sk_versi' => '1.5',
                'sk_tanggal_efektif' => '2024-05-15',
                'sk_status_aktif' => true,
            ],
            
            [
                'sk_kode' => 'sk_pengiriman',
                'sk_judul' => 'Syarat dan Ketentuan Pengiriman Barang',
                'sk_tipe' => 'pengiriman',
                'sk_konten_html' => '<div class="prose max-w-none">
                    <p class="text-lg font-medium mb-4">Ketentuan khusus untuk layanan pengiriman barang:</p>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Jenis Barang</h4>
                        <ul class="space-y-2">
                            <li>✓ Maksimal berat per barang: 50 kg</li>
                            <li>✓ Maksimal dimensi: 200x100x150 cm</li>
                            <li>✓ Dilarang mengirimkan barang berbahaya</li>
                            <li>✓ Dilarang mengirimkan barang ilegal</li>
                            <li>✓ Barang pecah belah packing khusus</li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Asuransi & Tanggung Jawab</h4>
                        <ul class="space-y-2">
                            <li>✓ Asuransi standar untuk barang hingga Rp 5.000.000</li>
                            <li>✓ Nilai lebih dari itu perlu asuransi tambahan</li>
                            <li>✓ Barang hilang/rusak akan diganti sesuai ketentuan</li>
                            <li>✓ Klaim harus diajukan dalam 7 hari kerja</li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2">Proses Pengiriman</h4>
                        <ul class="space-y-2">
                            <li>✓ Estimasi waktu pengiriman sesuai kota tujuan</li>
                            <li>✓ Pengirim wajib verifikasi penerima</li>
                            <li>✓ Resi akan diberikan setelah pembayaran</li>
                            <li>✓ Tracking tersedia di aplikasi</li>
                        </ul>
                    </div>
                </div>',
                'sk_versi' => '1.8',
                'sk_tanggal_efektif' => '2024-04-20',
                'sk_status_aktif' => true,
            ],
        ];

        foreach ($data as $item) {
            SyaratKetentuan::updateOrCreate(
                ['sk_kode' => $item['sk_kode']],
                $item
            );
        }
        
        $this->command->info('✅ Seeder SyaratKetentuan berhasil dijalankan!');
        $this->command->info('📋 Total data: ' . count($data));
    }
}