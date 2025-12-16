<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('branches')->truncate();
        
        $branches = [
            [
                'kode_cabang' => 'BDG-01',
                'nama_cabang' => 'Cabang Bandung Utara',
                'kota' => 'Bandung',
                'alamat' => 'Jl. Soekarno Hatta No. 123, Bandung, Jawa Barat 40111',
                'telepon' => '(022) 1234-5678',
                'email' => 'bandung@smartshuttle.com',
                'koordinat_gps' => '-6.917464,107.619125',
                'jam_buka' => '06:00:00',
                'jam_tutup' => '22:00:00',
                'status' => 'aktif'
            ],
            [
                'kode_cabang' => 'JKT-01',
                'nama_cabang' => 'Cabang Jakarta Pusat',
                'kota' => 'Jakarta',
                'alamat' => 'Jl. Thamrin No. 45, Jakarta Pusat 10110',
                'telepon' => '(021) 9876-5432',
                'email' => 'jakarta@smartshuttle.com',
                'koordinat_gps' => '-6.208763,106.845599',
                'jam_buka' => '05:00:00',
                'jam_tutup' => '23:00:00',
                'status' => 'aktif'
            ],
            [
                'kode_cabang' => 'BKS-01',
                'nama_cabang' => 'Cabang Bekasi Barat',
                'kota' => 'Bekasi',
                'alamat' => 'Jl. Ahmad Yani No. 78, Bekasi, Jawa Barat 17141',
                'telepon' => '(021) 4567-8901',
                'email' => 'bekasi@smartshuttle.com',
                'koordinat_gps' => '-6.234494,106.989615',
                'jam_buka' => '06:00:00',
                'jam_tutup' => '22:00:00',
                'status' => 'aktif'
            ],
            [
                'kode_cabang' => 'YOG-01',
                'nama_cabang' => 'Cabang Yogyakarta',
                'kota' => 'Yogyakarta',
                'alamat' => 'Jl. Malioboro No. 56, Yogyakarta 55271',
                'telepon' => '(0274) 2345-6789',
                'email' => 'yogyakarta@smartshuttle.com',
                'koordinat_gps' => '-7.795580,110.369492',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '21:00:00',
                'status' => 'aktif'
            ],
            [
                'kode_cabang' => 'BAL-01',
                'nama_cabang' => 'Cabang Bali',
                'kota' => 'Bali',
                'alamat' => 'Jl. Legian No. 89, Kuta, Badung, Bali 80361',
                'telepon' => '(0361) 3456-7890',
                'email' => 'bali@smartshuttle.com',
                'koordinat_gps' => '-8.722343,115.172371',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '22:00:00',
                'status' => 'aktif'
            ],
            [
                'kode_cabang' => 'SMG-01',
                'nama_cabang' => 'Cabang Semarang',
                'kota' => 'Semarang',
                'alamat' => 'Jl. Pandanaran No. 34, Semarang, Jawa Tengah 50136',
                'telepon' => '(024) 5678-9012',
                'email' => 'semarang@smartshuttle.com',
                'koordinat_gps' => '-6.982804,110.409439',
                'jam_buka' => '06:30:00',
                'jam_tutup' => '21:30:00',
                'status' => 'aktif'
            ],
            [
                'kode_cabang' => 'DPK-01',
                'nama_cabang' => 'Cabang Depok',
                'kota' => 'Depok',
                'alamat' => 'Jl. Margonda Raya No. 12, Depok, Jawa Barat 16431',
                'telepon' => '(021) 7777-8888',
                'email' => 'depok@smartshuttle.com',
                'koordinat_gps' => '-6.402484,106.794241',
                'jam_buka' => '06:00:00',
                'jam_tutup' => '22:00:00',
                'status' => 'aktif'
            ],
            [
                'kode_cabang' => 'TGR-01',
                'nama_cabang' => 'Cabang Tangerang',
                'kota' => 'Tangerang',
                'alamat' => 'Jl. Jenderal Sudirman No. 101, Tangerang, Banten 15111',
                'telepon' => '(021) 5555-6666',
                'email' => 'tangerang@smartshuttle.com',
                'koordinat_gps' => '-6.178305,106.631889',
                'jam_buka' => '05:30:00',
                'jam_tutup' => '22:30:00',
                'status' => 'aktif'
            ]
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
