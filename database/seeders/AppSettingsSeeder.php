<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('app_settings')->updateOrInsert(
            ['key' => 'jadwal_flow_mode'],
            ['value' => 'driver_confirmation', 'updated_at' => now(), 'created_at' => now()]
        );
    }
}
