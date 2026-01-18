<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

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
                'email_verified_at' => now(),
            ]
        );

        // ✅ URUTAN YANG BENAR:
        $this->call([
            BranchSeeder::class,          // 1. Infrastruktur
            OutletSeeder::class,          // 2. Outlet (butuh Branch)
            MLayananSeeder::class,        // 3. Jenis Layanan - HARUS PERTAMA untuk layanan
            RuteSeeder::class,            // 4. Rute (butuh MLayanan) ← SEBELUM SHUTTLE!
            ShuttleSeeder::class,         // 5. Shuttle (butuh MLayanan)
            KursiSeeder::class,           // 6. Kursi (butuh Shuttle)
            KebijakanPrivasiSeeder::class,
            SyaratKetentuanSeeder::class,
            MProfilePerusahaanSeeder::class,
            PromoSeeder::class,
            MasterKontakSeeder::class,
            MetodePembayaranSeeder::class,
            JadwalSeeder::class,
            ArtikelSeeder::class,
            RoleSeeder::class,
   // TERAKHIR! (butuh Rute & Shuttle)
        ]);
    }
}
