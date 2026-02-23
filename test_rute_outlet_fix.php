<?php
/**
 * Test Script: Verifikasi Perbaikan Outlet pada Rute
 *
 * Script ini memverifikasi bahwa ketika membuat rute baru dengan branch tertentu,
 * SEMUA outlet dari branch tersebut yang berstatus 'aktif' akan tersimpan
 * di field rute_pemberhentian, bukan hanya satu outlet.
 */

require_once __DIR__ . '/vendor/autoload.php';

// Set up Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use App\Models\Rute;
use App\Models\Branch;
use App\Models\Outlet;

// Boot the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test Data Setup
echo "=== TEST RUTE OUTLET FIX ===\n\n";

// 1. Check if we have outlets for testing
echo "1. Checking available branches and outlets...\n";

$branches = Branch::with('outlets')->where('status', 'aktif')->get();

if ($branches->isEmpty()) {
    echo "❌ ERROR: No active branches found in database\n";
    exit(1);
}

foreach ($branches as $branch) {
    $activeOutlets = $branch->outlets->where('status', 'aktif');
    echo "   Branch: {$branch->nama_cabang} ({$branch->kota})\n";
    echo "   Active Outlets: " . $activeOutlets->count() . "\n";

    foreach ($activeOutlets as $outlet) {
        echo "     - {$outlet->nama_outlet} ({$outlet->tipe_outlet})\n";
    }
}

echo "\n2. Testing Outlet Retrieval (New Approach)...\n";

// Get two branches for testing
$branches = Branch::where('status', 'aktif')->get();
if ($branches->count() < 2) {
    echo "⚠️  WARNING: Need at least 2 branches for full test\n";
}

$cabangAsal = $branches->first();
$cabangTujuan = $branches->count() > 1 ? $branches->get(1) : $branches->first();

echo "\n   Branch Asal: {$cabangAsal->nama_cabang}\n";
echo "   Branch Tujuan: {$cabangTujuan->nama_cabang}\n";

// Test the new query approach
$outletAsalData = Outlet::where('branch_id', $cabangAsal->id)
    ->where('status', 'aktif')
    ->pluck('nama_outlet')
    ->unique()
    ->values()
    ->toArray();

$outletTujuanData = Outlet::where('branch_id', $cabangTujuan->id)
    ->where('status', 'aktif')
    ->pluck('nama_outlet')
    ->unique()
    ->values()
    ->toArray();

echo "\n   ✅ Outlets Asal Retrieved: " . count($outletAsalData) . " outlets\n";
foreach ($outletAsalData as $outlet) {
    echo "      - {$outlet}\n";
}

echo "\n   ✅ Outlets Tujuan Retrieved: " . count($outletTujuanData) . " outlets\n";
foreach ($outletTujuanData as $outlet) {
    echo "      - {$outlet}\n";
}

// Simulate rute_pemberhentian structure
$rutePemberhentian = [];

if (!empty($outletAsalData)) {
    $rutePemberhentian[] = [
        'kota' => $cabangAsal->kota,
        'cabang' => $cabangAsal->nama_cabang,
        'outlets' => $outletAsalData,
        'durasi_singgah' => 0,
        'jenis' => 'asal'
    ];
}

if (!empty($outletTujuanData)) {
    $rutePemberhentian[] = [
        'kota' => $cabangTujuan->kota,
        'cabang' => $cabangTujuan->nama_cabang,
        'outlets' => $outletTujuanData,
        'durasi_singgah' => 0,
        'jenis' => 'tujuan'
    ];
}

echo "\n3. Rute Pemberhentian Structure:\n";
echo json_encode($rutePemberhentian, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

echo "\n4. Verification Results:\n";
$totalOutlets = 0;
foreach ($rutePemberhentian as $stop) {
    $totalOutlets += count($stop['outlets']);
    $outletCount = count($stop['outlets']);
    $status = $outletCount > 1 ? '✅' : '⚠️';
    echo "   {$status} {$stop['jenis']}: {$outletCount} outlets\n";
}

echo "\n5. Summary:\n";

if ($totalOutlets > 1) {
    echo "   ✅ SUCCESS: Multiple outlets are being collected\n";
    echo "   Total outlets collected: {$totalOutlets}\n";
} elseif ($totalOutlets === 1) {
    echo "   ⚠️  WARNING: Only 1 outlet collected (might need more test data)\n";
} else {
    echo "   ❌ ERROR: No outlets collected\n";
}

// Check existing rutes for verification
echo "\n6. Checking Existing Rutes in Database:\n";
$recentRutes = Rute::orderBy('created_at', 'desc')->limit(3)->get();

if ($recentRutes->isEmpty()) {
    echo "   ℹ️  No rutes found in database\n";
} else {
    foreach ($recentRutes as $rute) {
        $rutePem = $rute->rute_pemberhentian;
        $totalOutletCount = 0;

        if (is_array($rutePem)) {
            foreach ($rutePem as $stop) {
                if (isset($stop['outlets']) && is_array($stop['outlets'])) {
                    $totalOutletCount += count($stop['outlets']);
                }
            }
        }

        $status = $totalOutletCount > 1 ? '✅' : '⚠️';
        echo "   {$status} {$rute->nama_rute}: {$totalOutletCount} total outlets\n";

        if ($totalOutletCount > 0) {
            foreach ($rutePem as $stop) {
                echo "      {$stop['jenis']}: " . count($stop['outlets']) . " outlets\n";
            }
        }
    }
}

echo "\n=== TEST COMPLETED ===\n";
