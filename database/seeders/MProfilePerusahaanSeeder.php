<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MProfilePerusahaan;

class MProfilePerusahaanSeeder extends Seeder
{
    public function run()
    {
        MProfilePerusahaan::create([
            // 1. Informasi Dasar Perusahaan
            'nama_perusahaan' => 'PT. Citra Solusi Indonesia',
            'nama_dagang' => 'Smart Shuttle',
            'logo_perusahaan' => 'images/smartshuttlelogo.png',
            'deskripsi_singkat' => 'Layanan transportasi cerdas yang siap mengantarkan Anda menjelajahi keindahan Jawa Barat dengan harga terbaik dan kenyamanan maksimal.',
            'visi' => 'Menjadi penyedia layanan transportasi terdepan yang menghubungkan seluruh kota di Indonesia dengan solusi cerdas dan terpercaya.',
            'misi' => '1. Memberikan pelayanan transportasi yang aman, nyaman, dan tepat waktu. 2. Mengembangkan teknologi untuk kemudahan akses pelanggan. 3. Memberikan harga yang kompetitif dengan kualitas terbaik.',

            // 2. Informasi Kontak
            'alamat_kantor_pusat' => 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434',
            'telepon' => '+62 858-1122-4321',
            'email' => 'mdcitrasolusi@gmail.com',
            'website' => 'https://citrasolusi.id',
            'background_website' => 'images/bg.png',
            'jam_operasional' => 'Senin - Minggu, 24 Jam',

            // 3. Informasi Legal / Administratif
            'npwp' => '01.234.567.8-912.000',
            'nib' => '012345678901234',
            'siup' => '503/1234/SIUP/PMDN/2023',
            'tdp' => '123456789012345',
            'nomor_sertifikat_transportasi' => 'STD-2023-001234',
            'kode_izin_penyelenggaraan' => 'DISHUB-JABAR-2023-0567',

            // 4. Informasi Pembentukan Perusahaan
            'tanggal_berdiri' => '2020-01-15',
            'nama_pendiri' => 'Budi Santoso',
            'penanggung_jawab_utama' => 'Budi Santoso',
            'struktur_organisasi_file' => 'uploads/docs/struktur-organisasi.pdf',
            'struktur_organisasi_text' => 'Struktur organisasi perusahaan terdiri dari Direktur Utama, Manajer Operasional, Manajer Pemasaran, dan tim support.',

            // 5. Brand & Unit Bisnis
            'brand_smartshuttle' => 'SmartShuttle',
            'brand_smartsent' => 'SmartSent',
            'brand_smartrent' => 'SmartRent',
            'deskripsi_unit_bisnis' => '1. SmartShuttle: Layanan tiket shuttle antarkota. 2. SmartSent: Layanan pengiriman barang. 3. SmartRent: Layanan penyewaan armada.',

            // 6. Subtitle untuk Services Section
            'services_subtitle' => 'Nikmati tiga layanan unggulan kami – SmartShuttle, SmartRent, dan SmartSend. Dirancang untuk memenuhi kebutuhan perjalanan dan pengiriman Anda dengan mudah dan cepat.',

            // 7. Data untuk Features Section (BARU)
            'features_title' => 'SIAP MENEMANI SETIAP PERJALANANMU!',
            'features' => json_encode([
                [
                    'icon' => 'fas fa-route',
                    'title' => 'Perjalanan Tanpa Ribet',
                    'description' => 'Pesan tiket antar kota secara online dengan cepat dan nyaman, semua urusan perjalanan kamu lebih mudah!'
                ],
                [
                    'icon' => 'fas fa-hand-holding-usd',
                    'title' => 'Harga Bersahabat',
                    'description' => 'Nikmati perjalanan nyaman dengan tarif terjangkau tanpa kompromi kualitas.'
                ],
                [
                    'icon' => 'fas fa-car-alt',
                    'title' => 'Sewa Fleksibel',
                    'description' => 'Butuh kendaraan pribadi atau bisnis? SmartRent siap kapan pun kamu butuh.'
                ],
                [
                    'icon' => 'fas fa-shipping-fast',
                    'title' => 'Kirim Cepat & Aman',
                    'description' => 'SmartSend bantu antar paketmu tepat waktu, dengan pelacakan real-time.'
                ],
                [
                    'icon' => 'fas fa-mobile-alt',
                    'title' => 'Satu Aplikasi, Semua Bisa!',
                    'description' => 'Perjalanan, sewa, dan kirim barang – semua dalam satu platform SmartShuttle.'
                ],
                [
                    'icon' => 'fas fa-headset',
                    'title' => 'Bantuan 24/7',
                    'description' => 'Tim kami selalu siap membantu setiap langkah perjalananmu.'
                ]
            ]),

            // 8. Data untuk Sosial Media (BARU)
            'facebook_url' => 'https://facebook.com/smartshuttle',
            'instagram_url' => 'https://instagram.com/smartshuttle',
            'twitter_url' => 'https://twitter.com/smartshuttle',
            'footer_description' => 'Dengan layanan unggulan yang kami hadirkan, kami berkomitmen untuk menjadikan setiap momen perjalanan Anda lebih istimewa.',

            // 9. Data untuk Review Section (BARU)
            'reviews' => json_encode([
                [
                    'avatar' => 'https://randomuser.me/api/portraits/women/32.jpg',
                    'name' => 'Luna Ayna',
                    'stars' => 5,
                    'text' => 'Servisnya bagus, drivernya sopan dan nyetirnya halus jadi bisa tidur selama perjalanan. Tracking lokasinya juga akurat. Bakal jadi langganan.'
                ],
                [
                    'avatar' => 'https://randomuser.me/api/portraits/men/54.jpg',
                    'name' => 'Rizky Pratama',
                    'stars' => 4,
                    'text' => 'Pertama kali coba SmartShuttle dan langsung puas. Mobilnya bersih, AC dingin, kursinya empuk. Berangkat juga sesuai jadwal. Recommended banget buat yang sering PP Jakarta–Bandung!'
                ],
                [
                    'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg',
                    'name' => 'Sari Dewi',
                    'stars' => 5,
                    'text' => 'Harganya menurut saya cukup murah dibanding shuttle lain, tapi kualitas layanannya tetap bagus. Pemesanan lewat aplikasi juga gampang.'
                ]
            ]),

            // 10. Dokumen Pendukung
            'sop_layanan_customer_file' => 'uploads/docs/sop-customer.pdf',
            'sop_layanan_customer_text' => 'Standard Operating Procedure untuk pelayanan customer 24/7.',
            'kebijakan_refund_file' => 'uploads/docs/kebijakan-refund.pdf',
            'kebijakan_refund_text' => 'Kebijakan refund berlaku dalam 24 jam sebelum keberangkatan dengan biaya administrasi 10%.',
            'kebijakan_privasi_file' => 'uploads/docs/kebijakan-privasi.pdf',
            'kebijakan_privasi_text' => 'Kami menjaga kerahasiaan data pelanggan dengan sistem keamanan terenkripsi.',
            'syarat_ketentuan_file' => 'uploads/docs/syarat-ketentuan.pdf',
            'syarat_ketentuan_text' => 'Syarat dan ketentuan berlaku untuk semua layanan Smart Shuttle.',

            // Links
            'link_kebijakan_refund' => '/kebijakan/refund',
            'link_kebijakan_privasi' => '/kebijakan/privasi',
            'link_syarat_ketentuan' => '/syarat-ketentuan',

            // Status
            'status' => 'active',
            'created_by' => null,
        ]);
    }
}
