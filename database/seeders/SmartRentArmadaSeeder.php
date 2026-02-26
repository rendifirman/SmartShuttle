<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SmartRentArmada;

class SmartRentArmadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $armadas = [
            [
                'nama' => 'Toyota Avanza',
                'tipe' => 'MPV',
                'kapasitas' => 7,
                'nomor_polisi' => 'B 1234 CD',
                'tahun' => 2019,
                'bahan_bakar' => 'Bensin',
                'deskripsi' => 'Kendaraan MPV yang nyaman dan andal untuk perjalanan keluarga.',
                'harga_dasar' => 350000,
                'harga_dengan_sopir' => 150000,
                'fasilitas' => ['AC', 'Audio', 'Charger', 'Airbag'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Honda Brio',
                'tipe' => 'Hatchback',
                'kapasitas' => 5,
                'nomor_polisi' => 'B 5678 EF',
                'tahun' => 2020,
                'bahan_bakar' => 'Bensin',
                'deskripsi' => 'Mobil hatchback praktis dengan konsumsi bahan bakar ekonomis.',
                'harga_dasar' => 250000,
                'harga_dengan_sopir' => 120000,
                'fasilitas' => ['AC', 'Audio', 'Airbag'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Mitsubishi Xpander',
                'tipe' => 'MPV',
                'kapasitas' => 7,
                'nomor_polisi' => 'B 9012 GH',
                'tahun' => 2021,
                'bahan_bakar' => 'Bensin',
                'deskripsi' => 'MPV modern dengan desain futuristik dan fitur lengkap.',
                'harga_dasar' => 450000,
                'harga_dengan_sopir' => 180000,
                'fasilitas' => ['AC Double', 'WiFi', 'USB Charger', 'Airbag'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Toyota Innova',
                'tipe' => 'MPV',
                'kapasitas' => 7,
                'nomor_polisi' => 'B 3456 IJ',
                'tahun' => 2022,
                'bahan_bakar' => 'Diesel',
                'deskripsi' => 'MPV premium dengan performa diesel yang hemat bahan bakar.',
                'harga_dasar' => 550000,
                'harga_dengan_sopir' => 200000,
                'fasilitas' => ['AC Double', 'Audio', 'USB Charger', 'Airbag', 'TV'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Daihatsu Xenia',
                'tipe' => 'MPV',
                'kapasitas' => 7,
                'nomor_polisi' => 'B 7890 KL',
                'tahun' => 2020,
                'bahan_bakar' => 'Bensin',
                'deskripsi' => 'MPV terjangkau dengan ruang interior yang luas.',
                'harga_dasar' => 300000,
                'harga_dengan_sopir' => 130000,
                'fasilitas' => ['AC', 'Audio', 'Charger'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Honda CR-V',
                'tipe' => 'SUV',
                'kapasitas' => 7,
                'nomor_polisi' => 'B 2468 MN',
                'tahun' => 2021,
                'bahan_bakar' => 'Bensin',
                'deskripsi' => 'SUV tangguh dengan teknologi terdepan dan keamanan maksimal.',
                'harga_dasar' => 550000,
                'harga_dengan_sopir' => 200000,
                'fasilitas' => ['AC Double', 'Audio System', 'USB Charger', 'Airbag', 'WiFi'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Toyota Sedan Camry',
                'tipe' => 'Sedan',
                'kapasitas' => 5,
                'nomor_polisi' => 'B 3579 OP',
                'tahun' => 2020,
                'bahan_bakar' => 'Bensin',
                'deskripsi' => 'Sedan mewah dengan interior premium dan performa handal.',
                'harga_dasar' => 450000,
                'harga_dengan_sopir' => 180000,
                'fasilitas' => ['AC Double', 'Audio System', 'USB Charger', 'Airbag', 'TV'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Minibus Hiace 12 Seat',
                'tipe' => 'Minibus',
                'kapasitas' => 12,
                'nomor_polisi' => 'B 4681 QR',
                'tahun' => 2019,
                'bahan_bakar' => 'Diesel',
                'deskripsi' => 'Minibus besar cocok untuk perjalanan grup dengan kenyamanan maksimal.',
                'harga_dasar' => 750000,
                'harga_dengan_sopir' => 250000,
                'fasilitas' => ['AC Double', 'Audio System', 'USB Charger', 'Airbag', 'TV', 'WiFi'],
                'status' => 'aktif',
            ],
        ];

        foreach ($armadas as $armada) {
            SmartRentArmada::create($armada);
        }

        $this->command->info('SmartRent Armada seeder berhasil dijalankan!');
    }
}
