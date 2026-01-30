<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MMasterKontak;

class MasterKontakSeeder extends Seeder
{
    public function run(): void
    {
        MMasterKontak::create([
            'nama_perusahaan' => 'Citra Solusi Komputama',
            'deskripsi_singkat' => 'Menghubungkan kota, menyatukan perjalanan – Solusi cerdas untuk mobilitas Anda',
            'email_utama' => 'rndcitrasolusi@gmail.com',
            'email_dukungan' => 'support@smartshuttle.com',
            'telepon_utama' => '0858-1122-4321',
            'telepon_dukungan' => '0858-1122-4321',
            'alamat_kantor_pusat' => 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434',
            'facebook_url' => 'https://facebook.com/smartshuttle',
            'instagram_url' => 'https://instagram.com/smartshuttle',
            'twitter_url' => 'https://twitter.com/smartshuttle',
            'jam_operasional' => json_encode([
                ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                ['hari' => 'Sabtu', 'jam' => '08:00 - 15:00'],
                ['hari' => 'Minggu', 'jam' => 'Tutup']
            ]),
            'link_kebijakan_privasi' => '#',
            'link_syarat_ketentuan' => '#',
            'status' => 'active',
        ]);
    }
}
