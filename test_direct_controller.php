<?php
/**
 * Direct test of ScheduleController::search method
 * Tidak perlu via HTTP, langsung test logik Di dalam controller
 */

use App\Models\Jadwal;
use App\Models\Outlet;
use App\Http\Controllers\API\ScheduleController;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Bootstrap Laravel
require __DIR__ . '/bootstrap/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "===============================\n";
echo "DIRECT CONTROLLER TEST\n";
echo "===============================\n\n";

// Check jika ada outlets dan rutes dengan segments
try {
    $outletCount = Outlet::count();
    echo "Total Outlets dalam database: " . $outletCount . "\n";

    if ($outletCount < 2) {
        echo "⚠ Minimal 2 outlets diperlukan untuk test\n";
        exit;
    }

    // Get first 2 outlets
    $outlet1 = Outlet::first();
    $outlet2 = Outlet::skip(1)->first();

    echo "Outlet 1: " . $outlet1->nama_outlet . " (ID: " . $outlet1->id . ")\n";
    echo "Outlet 2: " . $outlet2->nama_outlet . " (ID: " . $outlet2->id . ")\n\n";

    // Check rutes dengan segments
    $ruteWithSegments = \App\Models\Rute::with('segments')->whereHas('segments')->first();

    if ($ruteWithSegments) {
        echo "Found Rute dengan segments: " . $ruteWithSegments->nama_rute . "\n";
        echo "Segments count: " . $ruteWithSegments->segments->count() . "\n\n";

        foreach ($ruteWithSegments->segments as $segment) {
            echo "  Segment " . $segment->urutan_segment . ":\n";
            echo "    Outlet ID: " . $segment->outlet_id . "\n";
            echo "    Jarak Kumulatif: " . $segment->jarak_kumulatif . "\n";
            echo "    Pickup: " . ($segment->is_pickup_point ? 'Yes' : 'No') . "\n";
            echo "    Drop: " . ($segment->is_drop_point ? 'Yes' : 'No') . "\n";
        }
    } else {
        echo "⚠ Tidak ada rute dengan segments dalam database\n";
        echo "Silakan setup test data terlebih dahulu\n";
    }

    // Check schedules
    $scheduleCount = Jadwal::where('status', 'tersedia')->count();
    echo "\nJadwal tersedia: " . $scheduleCount . "\n";

    if ($scheduleCount > 0) {
        $schedule = Jadwal::with('rutes.segments.outlet')->where('status', 'tersedia')->first();
        if ($schedule) {
            echo "\nSample Schedule:\n";
            echo "ID: " . $schedule->id . "\n";
            echo "Tanggal: " . $schedule->tanggal_keberangkatan . "\n";
            echo "Routes count: " . $schedule->rutes->count() . "\n";

            foreach ($schedule->rutes as $rute) {
                echo "\n  Rute: " . $rute->nama_rute . "\n";
                if ($rute->relationLoaded('segments')) {
                    echo "  Segments loaded: " . $rute->segments->count() . "\n";
                    foreach ($rute->segments as $segment) {
                        echo "    - Urutan " . $segment->urutan_segment;
                        if ($segment->relationLoaded('outlet') && $segment->outlet) {
                            echo " | " . $segment->outlet->nama_outlet;
                        }
                        echo "\n";
                    }
                }
            }
        }
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n===============================\n";
echo "TEST COMPLETED\n";
echo "===============================\n";
?>
