<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Outlet;
use App\Models\Rute;
use App\Models\RuteSegment;

class RuteSegmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This creates sample route segments if none exist
     */
    public function run(): void
    {
        // Check if segments already exist
        if (RuteSegment::count() > 0) {
            $this->command->info('Route segments already exist. Skipping seeder.');
            return;
        }

        // Get some outlets
        $outlets = Outlet::whereRaw('LOWER(status) = ?', ['aktif'])->limit(10)->get();
        
        if ($outlets->count() < 3) {
            $this->command->warn('Not enough active outlets to create segments. Please create outlets first.');
            return;
        }

        // Get or create a sample route
        $rute = Rute::first();
        if (!$rute) {
            $this->command->warn('No routes found. Please create routes first.');
            return;
        }

        // Create sample segments
        // Route: Jakarta -> Bandung -> Cianjur
        $segments = [
            [
                'rute_id' => $rute->id,
                'urutan_segment' => 1,
                'outlet_id' => $outlets[0]->id,  // Jakarta
                'is_pickup_point' => true,
                'is_drop_point' => false,
                'jarak_kumulatif' => 0,
                'status_aktif' => true
            ],
            [
                'rute_id' => $rute->id,
                'urutan_segment' => 2,
                'outlet_id' => $outlets[1]->id,  // Bandung
                'is_pickup_point' => true,
                'is_drop_point' => true,
                'jarak_kumulatif' => 180,
                'status_aktif' => true
            ],
            [
                'rute_id' => $rute->id,
                'urutan_segment' => 3,
                'outlet_id' => $outlets[2]->id,  // Cianjur
                'is_pickup_point' => false,
                'is_drop_point' => true,
                'jarak_kumulatif' => 280,
                'status_aktif' => true
            ]
        ];

        foreach ($segments as $segment) {
            RuteSegment::create($segment);
        }

        $this->command->info('Route segments created successfully!');
    }
}
