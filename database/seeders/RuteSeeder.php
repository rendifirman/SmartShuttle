<?php

namespace Database\Seeders;

use App\Models\Rute;
use App\Models\MLayanan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuteSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        DB::table('rutes')->truncate();

        // Cari layanan Smart Shuttle
        $smartShuttleService = MLayanan::where('kode_layanan', 'SMARTSHUTTLE')->first();

        if (!$smartShuttleService) {
            $this->command->error('Layanan Smart Shuttle tidak ditemukan! Jalankan MLayananSeeder dulu.');
            return;
        }

        $rutes = [
            [
                'layanan_id' => $smartShuttleService->id_layanan,
                'kode_rute' => 'JKT-BDG-001',
                'nama_rute' => 'Jakarta - Bandung Via Bekasi',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Bandung',
                'durasi' => '03:30',
                'jarak' => 150.00,
                'harga_dasar' => 120000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Jakarta',
                        'outlets' => ['Sudirman', 'Blok M', 'Kota'],
                        'durasi_singgah' => 15,
                        'jarak_segment' => 0
                    ],
                    [
                        'kota' => 'Bekasi',
                        'outlets' => ['Bekasi Barat', 'Bekasi Timur', 'Cikarang'],
                        'durasi_singgah' => 20,
                        'jarak_segment' => 30
                    ],
                    [
                        'kota' => 'Cimahi',
                        'outlets' => ['Cimahi Selatan', 'Cimahi Tengah'],
                        'durasi_singgah' => 15,
                        'jarak_segment' => 60
                    ],
                    [
                        'kota' => 'Bandung',
                        'outlets' => ['Dago', 'Cihampelas', 'Setiabudi'],
                        'durasi_singgah' => 30,
                        'jarak_segment' => 40
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan,
                'kode_rute' => 'BDG-JKT-002',
                'nama_rute' => 'Bandung - Jakarta Via Cikampek',
                'kota_asal' => 'Bandung',
                'kota_tujuan' => 'Jakarta',
                'durasi' => '03:00',
                'jarak' => 140.00,
                'harga_dasar' => 115000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Bandung',
                        'outlets' => ['Dago', 'Cihampelas', 'Setiabudi'],
                        'durasi_singgah' => 15,
                        'jarak_segment' => 0
                    ],
                    [
                        'kota' => 'Cimahi',
                        'outlets' => ['Cimahi Selatan', 'Cimahi Tengah'],
                        'durasi_singgah' => 10,
                        'jarak_segment' => 15
                    ],
                    [
                        'kota' => 'Purwakarta',
                        'outlets' => ['Purwakarta Kota', 'Cikampek'],
                        'durasi_singgah' => 15,
                        'jarak_segment' => 50
                    ],
                    [
                        'kota' => 'Bekasi',
                        'outlets' => ['Bekasi Barat', 'Bekasi Timur'],
                        'durasi_singgah' => 20,
                        'jarak_segment' => 40
                    ],
                    [
                        'kota' => 'Jakarta',
                        'outlets' => ['Sudirman', 'Blok M', 'Kota'],
                        'durasi_singgah' => 30,
                        'jarak_segment' => 35
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan,
                'kode_rute' => 'JKT-YOG-001',
                'nama_rute' => 'Jakarta - Yogyakarta Via Semarang',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Yogyakarta',
                'durasi' => '08:00',
                'jarak' => 550.00,
                'harga_dasar' => 250000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Jakarta',
                        'outlets' => ['Sudirman', 'Kota'],
                        'durasi_singgah' => 15,
                        'jarak_segment' => 0
                    ],
                    [
                        'kota' => 'Cirebon',
                        'outlets' => ['Cirebon Kota', 'Cirebon Barat'],
                        'durasi_singgah' => 20,
                        'jarak_segment' => 200
                    ],
                    [
                        'kota' => 'Tegal',
                        'outlets' => ['Tegal Kota', 'Slawi'],
                        'durasi_singgah' => 15,
                        'jarak_segment' => 100
                    ],
                    [
                        'kota' => 'Pekalongan',
                        'outlets' => ['Pekalongan Kota'],
                        'durasi_singgah' => 10,
                        'jarak_segment' => 60
                    ],
                    [
                        'kota' => 'Semarang',
                        'outlets' => ['Semarang Tengah', 'Simpang Lima', 'Ungaran'],
                        'durasi_singgah' => 25,
                        'jarak_segment' => 90
                    ],
                    [
                        'kota' => 'Yogyakarta',
                        'outlets' => ['Malioboro', 'Sleman', 'Bantul'],
                        'durasi_singgah' => 30,
                        'jarak_segment' => 100
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan,
                'kode_rute' => 'BDG-SMG-001',
                'nama_rute' => 'Bandung - Semarang Via Tasikmalaya',
                'kota_asal' => 'Bandung',
                'kota_tujuan' => 'Semarang',
                'durasi' => '06:00',
                'jarak' => 350.00,
                'harga_dasar' => 180000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Bandung',
                        'outlets' => ['Dago', 'Cihampelas'],
                        'durasi_singgah' => 15,
                        'jarak_segment' => 0
                    ],
                    [
                        'kota' => 'Tasikmalaya',
                        'outlets' => ['Tasik Kota', 'Cihideung'],
                        'durasi_singgah' => 20,
                        'jarak_segment' => 80
                    ],
                    [
                        'kota' => 'Banjar',
                        'outlets' => ['Banjar Kota'],
                        'durasi_singgah' => 10,
                        'jarak_segment' => 70
                    ],
                    [
                        'kota' => 'Cilacap',
                        'outlets' => ['Cilacap Kota', 'Kroya'],
                        'durasi_singgah' => 15,
                        'jarak_segment' => 100
                    ],
                    [
                        'kota' => 'Purwokerto',
                        'outlets' => ['Purwokerto Kota'],
                        'durasi_singgah' => 15,
                        'jarak_segment' => 60
                    ],
                    [
                        'kota' => 'Semarang',
                        'outlets' => ['Semarang Tengah', 'Simpang Lima'],
                        'durasi_singgah' => 30,
                        'jarak_segment' => 120
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan,
                'kode_rute' => 'JKT-BKS-LOCAL',
                'nama_rute' => 'Jakarta - Bekasi Local',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Bekasi',
                'durasi' => '01:30',
                'jarak' => 35.00,
                'harga_dasar' => 40000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Jakarta',
                        'outlets' => ['Sudirman', 'Blok M'],
                        'durasi_singgah' => 10,
                        'jarak_segment' => 0
                    ],
                    [
                        'kota' => 'Cakung',
                        'outlets' => ['Cakung Barat', 'Cakung Timur'],
                        'durasi_singgah' => 5,
                        'jarak_segment' => 10
                    ],
                    [
                        'kota' => 'Cibitung',
                        'outlets' => ['Cibitung Kota'],
                        'durasi_singgah' => 5,
                        'jarak_segment' => 8
                    ],
                    [
                        'kota' => 'Bekasi',
                        'outlets' => ['Bekasi Barat', 'Bekasi Timur'],
                        'durasi_singgah' => 15,
                        'jarak_segment' => 12
                    ]
                ]),
                'status' => 'aktif'
            ]
        ];

        foreach ($rutes as $rute) {
            Rute::create($rute);
        }

        $this->command->info('RuteSeeder berhasil! Total: ' . count($rutes) . ' rute dibuat.');
        $this->command->info('Semua rute dikaitkan dengan layanan Smart Shuttle.');
        
        // Tampilkan info rute yang dibuat
        foreach ($rutes as $rute) {
            $this->command->info('- ' . $rute['nama_rute'] . ' (' . $rute['kota_asal'] . ' → ' . $rute['kota_tujuan'] . ')');
        }
    }
}