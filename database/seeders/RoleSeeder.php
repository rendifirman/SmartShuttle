<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{

    public function run()
    {
        // daftar role
        $roles = [
            'admin_pusat',
            'admin_cabang',
            'operator',
            'driver',
            'customer',
        ];

        // buat role jika belum ada
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'admin']);
        }

        // Assign permissions ke roles
        $rolePermissions = [
            'admin_pusat' => [
                // Dashboard
                'view_dashboard',

                // Master Data - Full access
                'view_master_data',
                'view_profile_perusahaan',
                'manage_profile_perusahaan',
                'view_cabang',
                'manage_cabang',
                'view_outlet',
                'manage_outlet',
                'view_promo',
                'manage_promo',
                'view_tarif',
                'manage_tarif',
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

                // Transaksi - Full access
                'view_transaksi',
                'view_smartsend_transaksi',
                'manage_smartsend_transaksi',
                'view_perjalanan_transaksi',
                'manage_perjalanan_transaksi',
                'view_armada_transaksi',
                'manage_armada_transaksi',

                // SmartSend - Full access
                'view_smartsend',
                'view_smartsend_tiket',
                'manage_smartsend_tiket',
                'view_smartsend_perjalanan',
                'manage_smartsend_perjalanan',
                'view_smartsend_armada',
                'manage_smartsend_armada',

                // SmartRent - Full access
                'view_smartrent',
                'manage_smartrent',

                // Laporan - Full access
                'view_laporan',
                'manage_laporan',

                // Pengaturan - Full access
                'view_pengaturan',
                'view_user',
                'manage_user',
                'view_menu',
                'manage_menu',
            ],
            'admin_cabang' => [
                // Dashboard
                'view_dashboard',

                // Master Data - Limited access (cabang-specific)
                'view_master_data',
                'view_outlet',
                'manage_outlet',
                'view_promo',
                'view_armada',
                'view_driver',
                'manage_driver',
                'view_jadwal',
                'manage_jadwal',

                // Transaksi - Limited access
                'view_transaksi',
                'view_smartsend_transaksi',
                'view_perjalanan_transaksi',
                'view_armada_transaksi',

                // SmartSend - Limited access
                'view_smartsend',
                'view_smartsend_tiket',
                'view_smartsend_perjalanan',
                'view_smartsend_armada',

                // SmartRent - Limited access (NEW - allow cabang to view/manage smartrent)
                'view_smartrent',
                'manage_smartrent',

                // Laporan - View only
                'view_laporan',
            ],
            'operator' => [
                // Dashboard
                'view_dashboard',

                // Master Data - Limited access
                'view_master_data',
                'view_outlet',
                'view_promo',
                'view_armada',
                'view_driver',
                'view_jadwal',
                'manage_jadwal',

                // Transaksi - Full access for operations
                'view_transaksi',
                'view_smartsend_transaksi',
                'manage_smartsend_transaksi',
                'view_perjalanan_transaksi',
                'manage_perjalanan_transaksi',
                'view_armada_transaksi',
                'manage_armada_transaksi',

                // SmartSend - Full access for operations
                'view_smartsend',
                'view_smartsend_tiket',
                'manage_smartsend_tiket',
                'view_smartsend_perjalanan',
                'manage_smartsend_perjalanan',
                'view_smartsend_armada',
                'manage_smartsend_armada',

                // SmartRent - Full access for operations (NEW)
                'view_smartrent',
                'manage_smartrent',

                // Laporan - View only
                'view_laporan',
            ],
            'driver' => [
                // Dashboard
                'view_dashboard',

                // Limited access for driver operations
                'view_jadwal',
            ],
            'customer' => [
                // Customer has no admin permissions
            ],
        ];

        // Assign permissions to roles
        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->where('guard_name', 'admin')->first();
            if ($role) {
                $role->syncPermissions($permissions);
            }
        }

        // Buat admin pusat utama
        $adminPusat = User::firstOrCreate(
            ['email' => 'admin@smartshuttle.test'],
            [
                'name' => 'Admin Pusat',
                'password' => Hash::make('admin123')
            ]
        );
        $adminPusat->syncRoles(['admin_pusat']);

        // Sample user untuk testing
        $users = [
            [
                'name' => 'Admin Cabang Jakarta',
                'email' => 'jakarta@smartshuttle.test',
                'role' => 'admin_cabang',
                'branch_code' => 'JKT-01'
            ],
            [
                'name' => 'Admin Cabang Bogor',
                'email' => 'bogor@smartshuttle.test',
                'role' => 'admin_cabang',
                'branch_code' => 'BDG-01'
            ],
            [
                'name' => 'Operator Example',
                'email' => 'operator@smartshuttle.test',
                'role' => 'operator'
            ],
            [
                'name' => 'Driver Example',
                'email' => 'driver@smartshuttle.test',
                'role' => 'driver'
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123')
                ]
            );

            $user->syncRoles([$data['role']]);

            // Assign branch for branch admins
            if (isset($data['branch_code']) && $data['role'] === 'admin_cabang') {
                $branch = \App\Models\Branch::where('kode_cabang', $data['branch_code'])->first();
                if ($branch) {
                    $user->branch_id = $branch->id;
                    $user->save();
                }
            }
        }
    }
}
