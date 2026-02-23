<?php
// Test script untuk memverifikasi relationship many-to-many antara Rute dan MasterTarif

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Rute;
use App\Models\MasterTarif;

echo "=== Testing Rute and MasterTarif Many-to-Many Relationship ===\n\n";

try {
    // Test 1: Get all tarifs for a specific route
    echo "Test 1: Get all tarifs for route ID 1\n";
    $rute = Rute::find(1);
    if ($rute) {
        $tarifs = $rute->masterTarifs;
        echo "Route: " . $rute->nama_rute . "\n";
        echo "Number of tarifs: " . $tarifs->count() . "\n";
        if ($tarifs->count() > 0) {
            foreach ($tarifs as $tarif) {
                echo "  - " . $tarif->nama_tarif . " (" . $tarif->kode_tarif . ")\n";
            }
        }
    } else {
        echo "Route ID 1 not found\n";
    }
    echo "\n";

    // Test 2: Get all routes for a specific tarif
    echo "Test 2: Get all routes for tarif ID 1\n";
    $tarif = MasterTarif::find(1);
    if ($tarif) {
        $rutes = $tarif->rutes;
        echo "Tarif: " . $tarif->nama_tarif . "\n";
        echo "Number of routes: " . $rutes->count() . "\n";
        if ($rutes->count() > 0) {
            foreach ($rutes as $rute) {
                echo "  - " . $rute->nama_rute . " (" . $rute->kode_rute . ")\n";
            }
        }
    } else {
        echo "Tarif ID 1 not found\n";
    }
    echo "\n";

    // Test 3: Check the pivot table
    echo "Test 3: Check pivot table data\n";
    $connection = \Illuminate\Support\Facades\DB::table('rute_master_tarif')->get();
    echo "Total records in pivot table: " . count($connection) . "\n";
    if (count($connection) > 0) {
        echo "Sample records:\n";
        foreach ($connection->take(5) as $record) {
            echo "  - Rute ID: " . $record->rute_id . ", Tarif ID: " . $record->master_tarif_id . "\n";
        }
    }
    echo "\n";

    echo "✓ All tests completed successfully!\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
