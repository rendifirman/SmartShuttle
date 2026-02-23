<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== TEST PERJALANAN JADWAL INTEGRATION ===\n\n";

// Test 1: Check DriverJadwal with Jadwal
echo "TEST 1: DriverJadwal with Jadwal\n";
$today = \Carbon\Carbon::today();
$trips = \App\Models\DriverJadwal::with(['jadwal', 'jadwal.rutes'])
    ->where('tanggal', '>=', $today)
    ->limit(2)
    ->get();

echo "Trips found: " . $trips->count() . "\n";

foreach ($trips as $trip) {
    echo "\nTrip ID: {$trip->id_jadwal_driver}\n";
    echo "Tanggal: {$trip->tanggal}\n";
    echo "Status: {$trip->status}\n";

    if ($trip->jadwal) {
        echo "  Jadwal ID: {$trip->jadwal->id}\n";
        echo "  Jadwal Status: {$trip->jadwal->status}\n";

        $rutes = $trip->jadwal->rutes;
        echo "  Rutes count: " . $rutes->count() . "\n";

        foreach ($rutes as $rute) {
            echo "    - {$rute->nama_rute} ({$rute->kota_asal} → {$rute->kota_tujuan})\n";

            $pemberhentian = $rute->rute_pemberhentian;
            if (!is_array($pemberhentian)) {
                $pemberhentian = json_decode($pemberhentian, true) ?? [];
            }

            echo "      Pemberhentian: " . count($pemberhentian) . " stops\n";

            foreach ($pemberhentian as $idx => $stop) {
                $kota = $stop['kota'] ?? 'N/A';
                $outlets = isset($stop['outlets']) ? implode(", ", $stop['outlets']) : 'N/A';
                echo "        Stop " . ($idx + 1) . ": $kota - Outlets: $outlets\n";
            }
        }
    } else {
        echo "  ERROR: Jadwal is NULL\n";
    }
}

// Test 2: Check Branch and Outlets
echo "\n\nTEST 2: Branch and Outlets\n";
$branches = \App\Models\Branch::with('outlets')->limit(2)->get();
echo "Branches found: " . $branches->count() . "\n";

foreach ($branches as $branch) {
    echo "\nBranch: {$branch->nama_cabang} (Kota: {$branch->kota})\n";
    $activeOutlets = $branch->outlets()->where('status', 'aktif')->get();
    echo "  Active outlets: " . $activeOutlets->count() . "\n";

    foreach ($activeOutlets as $outlet) {
        echo "    - {$outlet->nama_outlet}\n";
    }
}

echo "\n=== ALL TESTS COMPLETED ===\n";
