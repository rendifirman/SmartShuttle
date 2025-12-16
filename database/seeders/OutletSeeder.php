<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Outlet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OutletSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('outlets')->truncate();

        // Ambil semua cabang untuk relasi
        $bandungBranch = Branch::where('kota', 'Bandung')->first();
        $jakartaBranch = Branch::where('kota', 'Jakarta')->first();
        $bekasiBranch = Branch::where('kota', 'Bekasi')->first();
        $yogyaBranch = Branch::where('kota', 'Yogyakarta')->first();
        $baliBranch = Branch::where('kota', 'Bali')->first();
        $semarangBranch = Branch::where('kota', 'Semarang')->first();
        $depokBranch = Branch::where('kota', 'Depok')->first();
        $tangerangBranch = Branch::where('kota', 'Tangerang')->first();

        $outlets = [
            // Outlet di Cabang Bandung
            [
                'branch_id' => $bandungBranch->id,
                'nama_outlet' => 'Terminal Leuwi Panjang',
                'alamat_lengkap' => 'Terminal Leuwi Panjang, Jl. Soekarno Hatta No. 123, Bandung',
                'telepon' => '(022) 1234-5001',
                'email' => 'leuwipanjang@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu VIP,Toilet,Parkir Luas,WiFi Gratis,Lounge,Cafe',
                'jam_operasional' => '24 Jam',
                'foto_outlet' => 'images/outlets/leuwi.jpg', // DIUBAH
                'tipe_outlet' => 'Terminal',
                'kapasitas_parkir' => 50,
                'tersedia_toilet' => true,
                'tersedia_musholla' => true,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Bandung Utara, Bandung Timur',
                'status' => 'aktif'
            ],
            [
                'branch_id' => $bandungBranch->id,
                'nama_outlet' => 'Cihampelas Walk',
                'alamat_lengkap' => 'Cihampelas Walk Lt. 1, Jl. Cihampelas No. 160, Bandung',
                'telepon' => '(022) 1234-5002',
                'email' => 'cihampelas@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu,Toilet,AC,WiFi,Food Court',
                'jam_operasional' => '10:00 - 22:00',
                'foto_outlet' => 'images/outlets/bandung-cihampelas.jpg', // DIUBAH
                'tipe_outlet' => 'Pusat Perbelanjaan',
                'kapasitas_parkir' => 30,
                'tersedia_toilet' => true,
                'tersedia_musholla' => false,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Bandung Barat, Cihampelas',
                'status' => 'aktif'
            ],

            // Outlet di Cabang Jakarta
            [
                'branch_id' => $jakartaBranch->id,
                'nama_outlet' => 'Sudirman',
                'alamat_lengkap' => 'Gedung Sudirman Plaza Lt. 1, Jl. Sudirman No. 45, Jakarta',
                'telepon' => '(021) 9876-5001',
                'email' => 'sudirman@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu Executive,Toilet VIP,Parkir Valet,WiFi Premium,Cafe,Business Center',
                'jam_operasional' => '05:00 - 23:00',
                'foto_outlet' => 'images/outlets/sudirman.jpg', // DIUBAH
                'tipe_outlet' => 'Perkantoran',
                'kapasitas_parkir' => 40,
                'tersedia_toilet' => true,
                'tersedia_musholla' => true,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Jakarta Pusat, Sudirman, Thamrin',
                'status' => 'aktif'
            ],
            [
                'branch_id' => $jakartaBranch->id,
                'nama_outlet' => 'Blok M',
                'alamat_lengkap' => 'Plaza Blok M Lt. GF, Jl. Bulungan No. 1, Jakarta Selatan',
                'telepon' => '(021) 9876-5002',
                'email' => 'blokm@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu,Toilet,Parkir,WiFi,Mini Market',
                'jam_operasional' => '06:00 - 22:00',
                'foto_outlet' => 'images/outlets/blok-m.jpg', // DIUBAH
                'tipe_outlet' => 'Pusat Perbelanjaan',
                'kapasitas_parkir' => 25,
                'tersedia_toilet' => true,
                'tersedia_musholla' => true,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Jakarta Selatan, Blok M, Kebayoran',
                'status' => 'aktif'
            ],
            [
                'branch_id' => $jakartaBranch->id,
                'nama_outlet' => 'Jakarta Kota',
                'alamat_lengkap' => 'Stasiun Jakarta Kota, Jl. Stasiun No. 1, Jakarta Barat',
                'telepon' => '(021) 9876-5003',
                'email' => 'kotajakarta@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu,Toilet,Parkir,WiFi,Informasi Turis',
                'jam_operasional' => '24 Jam',
                'foto_outlet' => 'images/outlets/stasiun.jpg', // DIUBAH
                'tipe_outlet' => 'Stasiun',
                'kapasitas_parkir' => 35,
                'tersedia_toilet' => true,
                'tersedia_musholla' => true,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Jakarta Barat, Kota Tua',
                'status' => 'aktif'
            ],

            // Outlet di Cabang Bekasi
            [
                'branch_id' => $bekasiBranch->id,
                'nama_outlet' => 'Bekasi Barat',
                'alamat_lengkap' => 'Ruko Bekasi Barat, Jl. Ahmad Yani No. 78, Bekasi',
                'telepon' => '(021) 4567-5001',
                'email' => 'bekasibarat@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu,Toilet,Parkir,AC,Cafe',
                'jam_operasional' => '06:00 - 22:00',
                'foto_outlet' => 'images/outlets/rukobekasi.jpeg', // DIUBAH
                'tipe_outlet' => 'Ruko',
                'kapasitas_parkir' => 20,
                'tersedia_toilet' => true,
                'tersedia_musholla' => true,
                'tersedia_atm' => false,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Bekasi Barat, Bintara',
                'status' => 'aktif'
            ],
            [
                'branch_id' => $bekasiBranch->id,
                'nama_outlet' => 'Bekasi Timur',
                'alamat_lengkap' => 'Mall Bekasi Timur, Jl. Cut Mutiah No. 12, Bekasi',
                'telepon' => '(021) 4567-5002',
                'email' => 'bekasitimur@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu,Toilet,Parkir Bawah Tanah,WiFi,Food Court',
                'jam_operasional' => '10:00 - 22:00',
                'foto_outlet' => 'images/outlets/mallbekasi.jpg', // DIUBAH
                'tipe_outlet' => 'Mall',
                'kapasitas_parkir' => 30,
                'tersedia_toilet' => true,
                'tersedia_musholla' => true,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Bekasi Timur, Pondok Gede',
                'status' => 'aktif'
            ],

            // Outlet di Cabang Yogyakarta
            [
                'branch_id' => $yogyaBranch->id,
                'nama_outlet' => 'Malioboro',
                'alamat_lengkap' => 'Jl. Malioboro No. 56, Yogyakarta',
                'telepon' => '(0274) 2345-5001',
                'email' => 'malioboro@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu,Toilet,Parkir,Tour Guide,Cultural Info,Souvenir Shop',
                'jam_operasional' => '07:00 - 21:00',
                'foto_outlet' => 'images/outlets/malioboro.jpg', // DIUBAH
                'tipe_outlet' => 'Jalan Utama',
                'kapasitas_parkir' => 15,
                'tersedia_toilet' => true,
                'tersedia_musholla' => true,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Malioboro, Kota Yogyakarta',
                'status' => 'aktif'
            ],

            // Outlet di Cabang Bali
            [
                'branch_id' => $baliBranch->id,
                'nama_outlet' => 'Kuta',
                'alamat_lengkap' => 'Jl. Legian No. 89, Kuta, Bali',
                'telepon' => '(0361) 3456-5001',
                'email' => 'kuta@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu,Toilet,Parkir,Beach Access,Tour Desk,Currency Exchange',
                'jam_operasional' => '08:00 - 22:00',
                'foto_outlet' => 'images/outlets/kuta.jpg', // DIUBAH
                'tipe_outlet' => 'Wisata',
                'kapasitas_parkir' => 25,
                'tersedia_toilet' => true,
                'tersedia_musholla' => false,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Kuta, Legian, Seminyak',
                'status' => 'aktif'
            ],

            // Outlet di Cabang Semarang
            [
                'branch_id' => $semarangBranch->id,
                'nama_outlet' => 'Simpang Lima',
                'alamat_lengkap' => 'Simpang Lima Square Lt. 1, Jl. Pandanaran No. 34, Semarang',
                'telepon' => '(024) 5678-5001',
                'email' => 'simpanglima@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu,Toilet,Parkir,Meeting Room,Business Center,Printing Service',
                'jam_operasional' => '06:30 - 21:30',
                'foto_outlet' => 'images/outlets/simpanglima.jpg', // DIUBAH
                'tipe_outlet' => 'Pusat Kota',
                'kapasitas_parkir' => 35,
                'tersedia_toilet' => true,
                'tersedia_musholla' => true,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Simpang Lima, Semarang Tengah',
                'status' => 'aktif'
            ],

            // Outlet di Cabang Depok
            [
                'branch_id' => $depokBranch->id,
                'nama_outlet' => 'Margonda',
                'alamat_lengkap' => 'Jl. Margonda Raya No. 12, Depok',
                'telepon' => '(021) 7777-5001',
                'email' => 'margonda@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu,Toilet,Parkir,WiFi,Cafe,Study Area',
                'jam_operasional' => '06:00 - 22:00',
                'foto_outlet' => 'images/outlets/margo.jpg', // DIUBAH
                'tipe_outlet' => 'Jalan Utama',
                'kapasitas_parkir' => 20,
                'tersedia_toilet' => true,
                'tersedia_musholla' => true,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'Margonda, Depok Pusat',
                'status' => 'aktif'
            ],

            // Outlet di Cabang Tangerang
            [
                'branch_id' => $tangerangBranch->id,
                'nama_outlet' => 'CBD',
                'alamat_lengkap' => 'CBD Tangerang Office Tower Lt. 1, Jl. Jenderal Sudirman No. 101',
                'telepon' => '(021) 5555-5001',
                'email' => 'tangerangcbd@smartshuttle.com',
                'fasilitas' => 'Ruang Tunggu Executive,Toilet,Parkir Basement,WiFi,Business Lounge',
                'jam_operasional' => '05:30 - 22:30',
                'foto_outlet' => 'images/outlets/rukotanggerang.jpg', // DIUBAH
                'tipe_outlet' => 'Perkantoran',
                'kapasitas_parkir' => 40,
                'tersedia_toilet' => true,
                'tersedia_musholla' => true,
                'tersedia_atm' => true,
                'tersedia_wifi' => true,
                'zona_pelayanan' => 'CBD Tangerang, Karawaci',
                'status' => 'aktif'
            ]
        ];

        foreach ($outlets as $outlet) {
            Outlet::create($outlet);
        }
    }
}