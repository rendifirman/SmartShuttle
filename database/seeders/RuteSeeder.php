<?php
// File: database/seeders/RuteSeeder.php

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
                'layanan_id' => $smartShuttleService->id_layanan, // ← TAMBAHKAN INI
                'kode_rute' => 'JKT-BAL-001',
                'nama_rute' => 'Jakarta - Bali Via Yogyakarta',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Bali',
                'durasi' => '18:00',
                'jarak' => 850.00,
                'harga_dasar' => 350000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Jakarta',
                        'outlets' => ['Sudirman', 'Blok M'],
                        'durasi_singgah' => 15
                    ],
                    [
                        'kota' => 'Bekasi',
                        'outlets' => ['Bekasi Barat', 'Bekasi Timur'],
                        'durasi_singgah' => 20
                    ],
                    [
                        'kota' => 'Yogyakarta',
                        'outlets' => ['Malioboro'],
                        'durasi_singgah' => 30
                    ],
                    [
                        'kota' => 'Semarang',
                        'outlets' => ['Simpang Lima'],
                        'durasi_singgah' => 15
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan, // ← TAMBAHKAN INI
                'kode_rute' => 'BDG-JKT-001',
                'nama_rute' => 'Bandung - Jakarta Via Bekasi',
                'kota_asal' => 'Bandung',
                'kota_tujuan' => 'Jakarta',
                'durasi' => '04:30',
                'jarak' => 140.00,
                'harga_dasar' => 125000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Bandung',
                        'outlets' => ['Terminal Leuwi Panjang', 'Cihampelas Walk'],
                        'durasi_singgah' => 15
                    ],
                    [
                        'kota' => 'Bekasi',
                        'outlets' => ['Bekasi Timur'],
                        'durasi_singgah' => 20
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan, // ← TAMBAHKAN INI
                'kode_rute' => 'JKT-YOG-001',
                'nama_rute' => 'Jakarta - Yogyakarta Via Semarang',
                'kota_asal' => 'Jakarta',
                'kota_tujuan' => 'Yogyakarta',
                'durasi' => '10:00',
                'jarak' => 520.00,
                'harga_dasar' => 225000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Jakarta',
                        'outlets' => ['Sudirman', 'Jakarta Kota'],
                        'durasi_singgah' => 15
                    ],
                    [
                        'kota' => 'Depok',
                        'outlets' => ['Margonda'],
                        'durasi_singgah' => 15
                    ],
                    [
                        'kota' => 'Semarang',
                        'outlets' => ['Simpang Lima'],
                        'durasi_singgah' => 20
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan, // ← TAMBAHKAN INI
                'kode_rute' => 'TGR-DPK-001',
                'nama_rute' => 'Tangerang - Depok Via Jakarta',
                'kota_asal' => 'Tangerang',
                'kota_tujuan' => 'Depok',
                'durasi' => '03:00',
                'jarak' => 70.00,
                'harga_dasar' => 85000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Tangerang',
                        'outlets' => ['CBD'],
                        'durasi_singgah' => 10
                    ],
                    [
                        'kota' => 'Jakarta',
                        'outlets' => ['Blok M', 'Sudirman'],
                        'durasi_singgah' => 20
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan, // ← TAMBAHKAN INI
                'kode_rute' => 'BAL-SMG-001',
                'nama_rute' => 'Bali - Semarang Via Yogyakarta',
                'kota_asal' => 'Bali',
                'kota_tujuan' => 'Semarang',
                'durasi' => '16:00',
                'jarak' => 650.00,
                'harga_dasar' => 295000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Bali',
                        'outlets' => ['Kuta'],
                        'durasi_singgah' => 15
                    ],
                    [
                        'kota' => 'Yogyakarta',
                        'outlets' => ['Malioboro'],
                        'durasi_singgah' => 30
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan, // ← TAMBAHKAN INI
                'kode_rute' => 'BKS-BDG-002',
                'nama_rute' => 'Bekasi - Bandung Express',
                'kota_asal' => 'Bekasi',
                'kota_tujuan' => 'Bandung',
                'durasi' => '02:30',
                'jarak' => 115.00,
                'harga_dasar' => 95000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Bekasi',
                        'outlets' => ['Bekasi Barat', 'Bekasi Timur'],
                        'durasi_singgah' => 15
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan, // ← TAMBAHKAN INI
                'kode_rute' => 'YOG-BAL-001',
                'nama_rute' => 'Yogyakarta - Bali Direct',
                'kota_asal' => 'Yogyakarta',
                'kota_tujuan' => 'Bali',
                'durasi' => '12:00',
                'jarak' => 530.00,
                'harga_dasar' => 275000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Yogyakarta',
                        'outlets' => ['Malioboro'],
                        'durasi_singgah' => 20
                    ],
                    [
                        'kota' => 'Semarang',
                        'outlets' => ['Simpang Lima'],
                        'durasi_singgah' => 15
                    ]
                ]),
                'status' => 'aktif'
            ],
            [
                'layanan_id' => $smartShuttleService->id_layanan, // ← TAMBAHKAN INI
                'kode_rute' => 'DPK-BKS-001',
                'nama_rute' => 'Depok - Bekasi Via Jakarta',
                'kota_asal' => 'Depok',
                'kota_tujuan' => 'Bekasi',
                'durasi' => '02:15',
                'jarak' => 50.00,
                'harga_dasar' => 65000.00,
                'rute_pemberhentian' => json_encode([
                    [
                        'kota' => 'Depok',
                        'outlets' => ['Margonda'],
                        'durasi_singgah' => 10
                    ],
                    [
                        'kota' => 'Jakarta',
                        'outlets' => ['Sudirman'],
                        'durasi_singgah' => 15
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
    }
}
