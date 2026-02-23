<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\DriverJadwal;
use App\Models\Rute;

try {
    echo "Starting to populate rute_id in driver_jadwals...\n\n";
    
    // Get all driver_jadwals without rute_id
    $driverJadwals = DriverJadwal::whereNull('rute_id')->get();
    echo "Found " . count($driverJadwals) . " driver_jadwals without rute_id\n\n";
    
    $updated = 0;
    $skipped = 0;
    
    foreach ($driverJadwals as $dj) {
        echo "Processing driver_jadwal ID: {$dj->id_jadwal_driver}, rute: {$dj->rute}\n";
        
        if (empty($dj->rute)) {
            echo "  → SKIPPED: No rute value\n";
            $skipped++;
            continue;
        }
        
        // Try to match by exact rute name
        $route = Rute::where('nama_rute', 'LIKE', '%' . trim($dj->rute) . '%')->first();
        
        if (!$route) {
            // Try matching by cities if rute contains arrow format (e.g., "Jakarta → Bandung")
            $parts = preg_split('/→|->/', $dj->rute);
            if (count($parts) >= 2) {
                $asal = trim($parts[0]);
                $tujuan = trim($parts[1]);
                
                $route = Rute::where('kota_asal', 'LIKE', '%' . $asal . '%')
                            ->where('kota_tujuan', 'LIKE', '%' . $tujuan . '%')
                            ->first();
            }
        }
        
        if ($route) {
            $dj->update(['rute_id' => $route->id]);
            echo "  → UPDATED: Matched with Rute ID {$route->id} ({$route->nama_rute})\n";
            $updated++;
        } else {
            echo "  → SKIPPED: No matching rute found\n";
            $skipped++;
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Updated: $updated\n";
    echo "Skipped: $skipped\n";
    echo "\nPopulation complete!\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
