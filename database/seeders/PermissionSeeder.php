<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Daftar permissions berdasarkan menu admin
        $permissions = [
            // Dashboard
            'view_dashboard',

            // Master Data
            'view_master_data',
            'view_profile_perusahaan',
            'manage_profile_perusahaan',
            'view_cabang',
            'manage_cabang',
            'view_outlet',
            'manage_outlet',
            'view_promo',
            'manage_promo',
            'view_kontak',
            'manage_kontak',
            'view_artikel',
            'manage_artikel',
            'view_armada',
            'manage_armada',
            'view_driver',
            'manage_driver',
            'view_pegawai',
            'manage_pegawai',
            'view_rute',
            'manage_rute',
            'view_jadwal',
            'manage_jadwal',

            // Transaksi
            'view_transaksi',
            'view_smartsend_transaksi',
            'manage_smartsend_transaksi',
            'view_perjalanan_transaksi',
            'manage_perjalanan_transaksi',
            'view_armada_transaksi',
            'manage_armada_transaksi',

            // SmartSend
            'view_smartsend',
            'view_smartsend_tiket',
            'manage_smartsend_tiket',
            'view_smartsend_perjalanan',
            'manage_smartsend_perjalanan',
            'view_smartsend_armada',
            'manage_smartsend_armada',

            // SmartRent
            'view_smartrent',
            'manage_smartrent',

            // Laporan
            'view_laporan',
            'manage_laporan',

            // Pengaturan
            'view_pengaturan',
            'view_user',
            'manage_user',
            'view_menu',
            'manage_menu',
        ];

        // Buat permission jika belum ada
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }
    }
}
