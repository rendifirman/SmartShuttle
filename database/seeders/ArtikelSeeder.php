<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artikel;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ArtikelSeeder extends Seeder
{
    public function run()
    {
        $artikels = [
            [
                'judul' => 'Tips Perjalanan Aman dengan Shuttle Selama Liburan',
                'konten' => '<h3>Persiapan Sebelum Perjalanan</h3>
                <p>Perjalanan dengan shuttle selama liburan memerlukan persiapan yang matang. Pastikan Anda memesan tiket jauh-jauh hari untuk mendapatkan harga terbaik dan kursi pilihan. Smart Shuttle menawarkan pemesanan online yang mudah melalui website atau aplikasi kami.</p>
                <h3>Packing yang Tepat</h3>
                <p>Bawalah barang secukupnya sesuai durasi perjalanan. Gunakan tas yang mudah disimpan di bagasi shuttle. Jangan lupa membawa charger ponsel, makanan ringan, dan baju hangat karena AC shuttle biasanya cukup dingin.</p>',
                'kategori' => 'Tips & Trik',
                'penulis' => 'Admin SmartShuttle',
                'tanggal_publikasi' => Carbon::parse('2024-03-15'),
                'meta_keywords' => 'Perjalanan, Tips, Liburan, Shuttle',
                'meta_description' => 'Pelajari cara mempersiapkan perjalanan shuttle yang aman dan nyaman selama musim liburan untuk pengalaman terbaik.'
            ],
            [
                'judul' => 'SmartShuttle Perkenalkan Fitur Baru: SmartSend',
                'konten' => '<h3>SmartSend - Solusi Pengiriman Paket</h3>
                <p>SmartSend adalah layanan terbaru dari Smart Shuttle yang memungkinkan Anda mengirim paket antar kota dengan mudah, cepat, dan aman. Dengan fitur tracking real-time, Anda dapat memantau perjalanan paket Anda kapan saja dan di mana saja.</p>
                <h3>Keunggulan SmartSend</h3>
                <p>1. <strong>Tracking Real-time</strong>: Pantau lokasi paket Anda secara real-time melalui aplikasi<br>
                2. <strong>Harga Terjangkau</strong>: Tarif yang kompetitif dengan kualitas terjamin<br>
                3. <strong>Pengiriman Cepat</strong>: Didukung oleh armada shuttle yang sudah terintegrasi</p>',
                'kategori' => 'Berita',
                'penulis' => 'Admin SmartShuttle',
                'tanggal_publikasi' => Carbon::parse('2024-03-10'),
                'meta_keywords' => 'Fitur Baru, Pengiriman, Inovasi, SmartSend',
                'meta_description' => 'Kami dengan bangga memperkenalkan layanan pengiriman paket antar kota dengan fitur tracking real-time.'
            ],
            [
                'judul' => 'Mengapa Memilih Shuttle untuk Perjalanan Bisnis?',
                'konten' => '<h3>Efisiensi Waktu dan Biaya</h3>
                <p>Untuk perjalanan bisnis, waktu adalah uang. Dengan menggunakan layanan shuttle, Anda dapat menghindari kemacetan dengan rute yang sudah teroptimasi.</p>
                <h3>Kenyamanan dan Produktivitas</h3>
                <p>Smart Shuttle menyediakan fasilitas yang mendukung produktivitas Anda seperti Wi-Fi gratis dan stop kontak untuk mengisi daya perangkat.</p>',
                'kategori' => 'Bisnis',
                'penulis' => 'Admin SmartShuttle',
                'tanggal_publikasi' => Carbon::parse('2024-03-05'),
                'meta_keywords' => 'Bisnis, Efisiensi, Perjalanan, Produktivitas',
                'meta_description' => 'Temukan keuntungan menggunakan layanan shuttle untuk kebutuhan perjalanan bisnis Anda. Efisien dan hemat waktu.'
            ],
            [
                'judul' => 'Promo Spesial: Nikmati Diskon 30% Shuttle',
                'konten' => '<h3>Syarat dan Ketentuan Promo</h3>
                <p>1. Promo berlaku untuk pemesanan tiket shuttle reguler<br>
                2. Minimal pemesanan 2 tiket<br>
                3. Berlaku untuk perjalanan dalam periode 1-31 Maret 2024</p>
                <h3>Cara Mendapatkan Promo</h3>
                <p>1. Pilih tujuan perjalanan Anda<br>
                2. Pilih jadwal yang tersedia<br>
                3. Masukkan kode promo: <strong>SHUTTLE30</strong></p>',
                'kategori' => 'Promo',
                'penulis' => 'Admin SmartShuttle',
                'tanggal_publikasi' => Carbon::parse('2024-02-25'),
                'meta_keywords' => 'Promo, Diskon, Spesial, Hemat',
                'meta_description' => 'Manfaatkan promo spesial kami untuk perjalanan shuttle antar kota dengan diskon hingga 30%.'
            ],
        ];

        foreach ($artikels as $artikel) {
            if (empty($artikel['slug'])) {
                $artikel['slug'] = Str::slug($artikel['judul']);
            }
            Artikel::create($artikel);
        }
    }
}
