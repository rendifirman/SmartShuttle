<?php
// Test endpoint untuk memverifikasi relationship many-to-many antara Rute dan MasterTarif

use Illuminate\Support\Facades\Route;
use App\Models\Rute;
use App\Models\MasterTarif;

Route::get('/test-tarif-relationship', function () {
    try {
        $output = "<h1>Testing Rute and MasterTarif Many-to-Many Relationship</h1>";

        // Test 1: Get all tarifs for a specific route
        $output .= "<h2>Test 1: Get all tarifs for routes</h2>";
        $rutes = Rute::limit(3)->get();
        foreach ($rutes as $rute) {
            $output .= "<p><strong>Route: " . $rute->nama_rute . "</strong><br>";
            $tarifs = $rute->masterTarifs;
            $output .= "Number of tarifs: " . $tarifs->count() . "<br>";
            if ($tarifs->count() > 0) {
                $output .= "Tarifs: ";
                foreach ($tarifs as $tarif) {
                    $output .= $tarif->nama_tarif . " (" . $tarif->kode_tarif . "), ";
                }
            }
            $output .= "</p>";
        }

        // Test 2: Get all routes for a specific tarif
        $output .= "<h2>Test 2: Get all routes for tarifs</h2>";
        $tarifs = MasterTarif::limit(3)->get();
        foreach ($tarifs as $tarif) {
            $output .= "<p><strong>Tarif: " . $tarif->nama_tarif . "</strong><br>";
            $rutes = $tarif->rutes;
            $output .= "Number of routes: " . $rutes->count() . "<br>";
            if ($rutes->count() > 0) {
                $output .= "Routes: ";
                foreach ($rutes as $rute) {
                    $output .= $rute->nama_rute . " (" . $rute->kode_rute . "), ";
                }
            }
            $output .= "</p>";
        }

        // Test 3: Check the pivot table
        $output .= "<h2>Test 3: Pivot Table Data</h2>";
        $pivotData = DB::table('rute_master_tarif')->get();
        $output .= "Total records in pivot table: " . count($pivotData) . "<br>";
        if (count($pivotData) > 0) {
            $output .= "<table border='1'><tr><th>Rute ID</th><th>Tarif ID</th></tr>";
            foreach ($pivotData->take(10) as $record) {
                $output .= "<tr><td>" . $record->rute_id . "</td><td>" . $record->master_tarif_id . "</td></tr>";
            }
            $output .= "</table>";
        }

        $output .= "<h2>✓ All tests completed successfully!</h2>";
        return $output;
    } catch (Exception $e) {
        return "<h1 style='color: red;'>ERROR</h1>" .
               "<p>Message: " . $e->getMessage() . "</p>" .
               "<p>File: " . $e->getFile() . ":" . $e->getLine() . "</p>" .
               "<pre>" . $e->getTraceAsString() . "</pre>";
    }
})->name('test.tarif.relationship');
