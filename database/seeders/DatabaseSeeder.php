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
        // ✅ URUTAN YANG BENAR:
        $seeders = [
            PermissionSeeder::class,     // 1. Permissions HARUS PERTAMA
            RoleSeeder::class,           // 2. Roles (butuh Permissions)
            BranchSeeder::class,         // 3. Infrastruktur
            OutletSeeder::class,         // 4. Outlet (butuh Branch)
            MLayananSeeder::class,       // 5. Jenis Layanan
            RuteSeeder::class,           // 6. Rute (butuh MLayanan)
            ShuttleSeeder::class,        // 7. Shuttle (butuh MLayanan)
            KursiSeeder::class,          // 8. Kursi (butuh Shuttle)
            SmartRentArmadaSeeder::class, // 9. SmartRent Armada
            KebijakanPrivasiSeeder::class,
            SyaratKetentuanSeeder::class,
            MProfilePerusahaanSeeder::class,
            PromoSeeder::class,
            MasterKontakSeeder::class,
            MetodePembayaranSeeder::class,
            ArtikelSeeder::class,
            MasterTarifSeeder::class,
            \Database\Seeders\AppSettingsSeeder::class,
        ];

        // Jalankan setiap seeder dengan try-catch agar jika gagal, yang lain tetap berjalan
        foreach ($seeders as $seeder) {
            try {
                $this->command->info("Running seeder: {$seeder}");
                $this->call($seeder);
                $this->command->info("Seeder {$seeder} completed successfully.");
            } catch (\Exception $e) {
                $this->command->error("Seeder {$seeder} failed: " . $e->getMessage());
                // Lanjutkan ke seeder berikutnya
            }
        }

        // Buat test user
        try {
            User::firstOrCreate(
                ['email' => 'test@example.com'],
                [
                    'name' => 'Test User',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );
            $this->command->info("Test user created successfully.");
        } catch (\Exception $e) {
            $this->command->error("Failed to create test user: " . $e->getMessage());
        }
    }
}
