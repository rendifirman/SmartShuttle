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
            Role::firstOrCreate(['name' => $role]);
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
                'name' => 'Admin Cabang',
                'email' => 'cabang@smartshuttle.test',
                'role' => 'admin_cabang'
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
        }
    }
}