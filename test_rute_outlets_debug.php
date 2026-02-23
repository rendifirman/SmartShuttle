<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Branch;
use App\Models\Rute;
use App\Models\Outlet;

echo "=== DEBUG RUTE OUTLETS ===\n\n";

// 1. Check all branches
echo "1. LIST SEMUA CABANG:\n";
echo str_repeat("=", 50) . "\n";
$branches = Branch::with('outlets')->get();
foreach ($branches as $branch) {
    echo "Branch: {$branch->nama_cabang} (ID: {$branch->id})\n";
    echo "  - Kota: {$branch->kota}\n";
    echo "  - Jumlah Outlets (relation): " . $branch->outlets->count() . "\n";

    if ($branch->outlets->count() > 0) {
        echo "  - Daftar Outlets:\n";
        foreach ($branch->outlets as $outlet) {
            echo "      * {$outlet->nama_outlet} (status: {$outlet->status}, ID: {$outlet->id})\n";
        }

        // Test the exact code from RuteController
        $outletList = $branch->outlets
            ->filter(function($o) { return isset($o->status) && $o->status == 'aktif'; })
            ->map(function($o) { return $o->nama_outlet ?? null; })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        echo "  - Hasil filter (outlet names): " . json_encode($outletList) . "\n";
    }
    echo "\n";
}

// 2. Check recent routes and their rute_pemberhentian
echo "\n2. CHECK RECENT RUTES:\n";
echo str_repeat("=", 50) . "\n";
$recentRoutes = Rute::orderBy('id', 'desc')->limit(5)->get();
foreach ($recentRoutes as $rute) {
    echo "Rute: {$rute->nama_rute} (ID: {$rute->id})\n";
    echo "  - Kota Asal: {$rute->kota_asal}\n";
    echo "  - Kota Tujuan: {$rute->kota_tujuan}\n";

    $pemberhentian = $rute->rute_pemberhentian;
    echo "  - rute_pemberhentian (raw): " . json_encode($pemberhentian) . "\n";

    if (!empty($pemberhentian) && is_array($pemberhentian)) {
        foreach ($pemberhentian as $index => $stop) {
            echo "    Stop {$index}: {$stop['kota']} - {$stop['cabang']}\n";
            echo "      - outlets: " . json_encode($stop['outlets'] ?? []) . "\n";
            echo "      - jumlah outlets: " . (isset($stop['outlets']) ? count($stop['outlets']) : 0) . "\n";
        }
    }
    echo "\n";
}

// 3. Test direct query without model
echo "\n3. DIRECT OUTLET QUERY:\n";
echo str_repeat("=", 50) . "\n";
$outlets = Outlet::where('status', 'aktif')->get();
echo "Total outlets dengan status='aktif': " . $outlets->count() . "\n";
$outletsByBranch = $outlets->groupBy('branch_id');
foreach ($outletsByBranch as $branchId => $branchOutlets) {
    echo "  Branch ID {$branchId}: " . $branchOutlets->count() . " outlets\n";
    foreach ($branchOutlets as $outlet) {
        echo "    - {$outlet->nama_outlet}\n";
    }
}

echo "\n=== END DEBUG ===\n";
