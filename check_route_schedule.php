<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING SAMPLE ROUTE AND SCHEDULES ===\n\n";

try {
    // Get first route with its schedules
    $rute = \App\Models\Rute::with('ruteJadwals.jadwal')->first();

    if ($rute) {
        echo "Route: " . $rute->nama_rute . " (" . $rute->kota_asal . " -> " . $rute->kota_tujuan . ")\n";
        echo "Route ID: " . $rute->id . "\n";
        echo "Status: " . $rute->status . "\n";
        echo "Duration: " . $rute->durasi . " hours\n";
        echo "Distance: " . $rute->jarak . " km\n\n";

        echo "Route Schedules (" . $rute->ruteJadwals->count() . " schedules):\n";

        foreach ($rute->ruteJadwals as $rj) {
            $jadwal = $rj->jadwal;
            if ($jadwal) {
                echo "- Date: " . $jadwal->tanggal_keberangkatan . "\n";
                echo "  Time: " . $jadwal->waktu_keberangkatan . " - " . $jadwal->waktu_kedatangan . "\n";
                echo "  Status: " . $jadwal->status . "\n";
                echo "  Available Seats: " . $jadwal->kursi_tersedia . "\n";
                echo "  Total Price: Rp " . number_format($jadwal->harga_total, 0, ',', '.') . "\n\n";
            }
        }
    } else {
        echo "No routes found in database\n";
    }

    // Check for any issues
    echo "=== CHECKING FOR POTENTIAL ISSUES ===\n\n";

    // Check schedules without routes
    $orphanedSchedules = \App\Models\Jadwal::doesntHave('rutes')->count();
    echo "Schedules without routes: " . $orphanedSchedules . "\n";

    // Check routes without schedules
    $routesWithoutSchedules = \App\Models\Rute::doesntHave('ruteJadwals')->count();
    echo "Routes without schedules: " . $routesWithoutSchedules . "\n";

    // Check future schedules
    $futureSchedules = \App\Models\Jadwal::whereDate('tanggal_keberangkatan', '>=', now())->count();
    echo "Future schedules: " . $futureSchedules . "\n";

    // Check past schedules
    $pastSchedules = \App\Models\Jadwal::whereDate('tanggal_keberangkatan', '<', now())->count();
    echo "Past schedules: " . $pastSchedules . "\n";

    echo "\n=== CHECK COMPLETED ===\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
