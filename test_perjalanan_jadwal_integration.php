<?php
/**
 * Test Script: Verifikasi Integrasi Perjalanan dengan Jadwal & Outlets
 *
 * Tujuan:
 * - Verifikasi bahwa getStopPointsFromSchedule() menghasilkan struktur data yang benar
 * - Test dengan berbagai kondisi (ada outlets, tidak ada, etc)
 * - Print hasil JSON untuk debugging
 */

require 'bootstrap/app.php';

use Illuminate\Foundation\Application;
use App\Models\DriverJadwal;
use App\Models\User;

$app = new Application(dirname(__DIR__));

// Load app bindings
$app->singleton('request', function ($app) {
    return $app->make('Illuminate\Http\Request');
});

// Setup database
require 'bootstrap/app.php';

$app = $app();

// Test 1: Get first driver with trips
echo "=" . str_repeat("=", 70) . "\n";
echo "TEST 1: Ambil Data Perjalanan Driver\n";
echo "=" . str_repeat("=", 70) . "\n";

$today = \Carbon\Carbon::today();
$trips = DriverJadwal::with(['jadwal', 'masterRute', 'jadwal.rutes'])
    ->where('tanggal', '>=', $today)
    ->orderBy('tanggal', 'asc')
    ->limit(3)
    ->get();

echo "Total trips ditemukan: " . $trips->count() . "\n\n";

foreach ($trips as $trip) {
    echo "Trip ID: {$trip->id_jadwal_driver}\n";
    echo "Tanggal: {$trip->tanggal}\n";
    echo "Waktu: {$trip->waktu_keberangkatan}\n";
    echo "Status: {$trip->status}\n";

    if ($trip->jadwal) {
        echo "Jadwal ID: {$trip->jadwal->id}\n";
        echo "Jadwal Status: {$trip->jadwal->status}\n";

        $rutes = $trip->jadwal->rutes;
        echo "Jumlah Rutes: " . $rutes->count() . "\n";

        foreach ($rutes as $rute) {
            echo "\n  Rute: {$rute->nama_rute}\n";
            echo "  Dari: {$rute->kota_asal} → Ke: {$rute->kota_tujuan}\n";

            $pemberhentian = $rute->rute_pemberhentian ?? [];
            if (!is_array($pemberhentian)) {
                $pemberhentian = json_decode($pemberhentian, true) ?? [];
            }

            echo "  Pemberhentian: " . count($pemberhentian) . " stops\n";

            foreach ($pemberhentian as $idx => $stop) {
                $kota = $stop['kota'] ?? 'N/A';
                echo "    Stop " . ($idx + 1) . ": $kota\n";
                $outlets = $stop['outlets'] ?? [];
                echo "    Outlets: " . implode(", ", $outlets) . "\n";
            }
        }
    }

    echo "\n" . str_repeat("-", 70) . "\n\n";
}

echo "\n✓ Test 1 selesai\n\n";

// Test 2: Verifikasi Branch & Outlet relationships
echo "=" . str_repeat("=", 70) . "\n";
echo "TEST 2: Verifikasi Branch & Outlet Data\n";
echo "=" . str_repeat("=", 70) . "\n";

use App\Models\Branch;
use App\Models\Outlet;

$branches = Branch::with('outlets')->limit(3)->get();
echo "Total branches: " . $branches->count() . "\n";

foreach ($branches as $branch) {
    echo "\nBranch: {$branch->nama_cabang} ({$branch->kota})\n";
    $activeOutlets = $branch->outlets()->where('status', 'aktif')->get();
    echo "Outlets aktif: " . $activeOutlets->count() . "\n";

    foreach ($activeOutlets as $outlet) {
        echo "  - {$outlet->nama_outlet} ({$outlet->alamat_lengkap})\n";
    }
}

echo "\n✓ Test 2 selesai\n\n";

// Test 3: Simulasi getStopPointsFromSchedule()
echo "=" . str_repeat("=", 70) . "\n";
echo "TEST 3: Simulasi getStopPointsFromSchedule()\n";
echo "=" . str_repeat("=", 70) . "\n";

function simulateGetStopPointsFromSchedule($trip) {
    $stopPoints = [];

    try {
        $jadwal = $trip->jadwal ?? null;

        if (!$jadwal) {
            echo "Jadwal tidak ditemukan\n";
            return $stopPoints;
        }

        $rutes = $jadwal->rutes ?? collect();

        if ($rutes->isEmpty()) {
            echo "Rutes kosong\n";
            return $stopPoints;
        }

        foreach ($rutes as $rute) {
            $pemberhentian = $rute->rute_pemberhentian ?? [];

            if (!is_array($pemberhentian)) {
                $pemberhentian = json_decode($pemberhentian, true) ?? [];
            }

            foreach ($pemberhentian as $stopIndex => $stop) {
                if (!is_array($stop)) {
                    continue;
                }

                $kota = $stop['kota'] ?? '';
                $outlets = $stop['outlets'] ?? [];
                $durasiSinggah = $stop['durasi_singgah'] ?? 10;

                $branch = Branch::where('kota', $kota)->first();

                if (!$branch) {
                    echo "Branch untuk kota '$kota' tidak ditemukan\n";
                    continue;
                }

                $branchOutlets = Outlet::where('branch_id', $branch->id)
                    ->where('status', 'aktif')
                    ->get();

                $outletDetails = [];
                foreach ($branchOutlets as $outlet) {
                    if (in_array($outlet->nama_outlet, $outlets)) {
                        $outletDetails[] = [
                            'id' => $outlet->id,
                            'nama_outlet' => $outlet->nama_outlet,
                            'alamat' => $outlet->alamat_lengkap ?? '',
                            'kota' => $branch->kota,
                        ];
                    }
                }

                if (!empty($outletDetails)) {
                    $stopPoints[] = [
                        'urutan' => $stopIndex + 1,
                        'kota' => $kota,
                        'branch_id' => $branch->id,
                        'branch_name' => $branch->nama_cabang,
                        'durasi_singgah' => $durasiSinggah,
                        'outlets' => $outletDetails,
                    ];
                }
            }
        }

    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }

    return $stopPoints;
}

// Test dengan trip pertama
if ($trips->count() > 0) {
    $testTrip = $trips->first();
    echo "Simulasi untuk Trip: {$testTrip->id_jadwal_driver}\n\n";

    $result = simulateGetStopPointsFromSchedule($testTrip);

    echo "Result:\n";
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

    echo "\nTotal stop points: " . count($result) . "\n";
}

echo "\n✓ Test 3 selesai\n\n";

echo "=" . str_repeat("=", 70) . "\n";
echo "✓ SEMUA TEST SELESAI\n";
echo "=" . str_repeat("=", 70) . "\n";
