<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
    RoleSeeder::class,
]);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            BranchSeeder::class,
            OutletSeeder::class,
            ShuttleSeeder::class,
            RuteSeeder::class,
            JadwalSeeder::class,
            KursiSeeder::class,
            KebijakanPrivasiSeeder::class,
            SyaratKetentuanSeeder::class,
            MProfilePerusahaanSeeder::class,
            MLayananSeeder::class,
            PromoSeeder::class,
            MasterKontakSeeder::class,
            MetodePembayaranSeeder::class,
        ]);
    }
}
