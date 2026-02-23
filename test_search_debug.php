<?php
// Test script to debug DriverJadwal search issue

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\DriverJadwal;
use App\Models\Jadwal;
use Carbon\Carbon;

// Get application instance
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTING DRIVERJADWAL SEARCH ISSUE ===\n\n";

// 1. Check how many DriverJadwals exist
$total = DriverJadwal::count();
echo "Total DriverJadwals in DB: $total\n";

if ($total == 0) {
    echo "ERROR: No DriverJadwal records found in database!\n";
    echo "You need to seed or create test data first.\n";
    exit;
}

// 2. Check active and available ones
$active = DriverJadwal::where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->whereRaw('total_kursi > kursi_terisi')
    ->count();

echo "Active DriverJadwals (status=aktif, tanggal>=today, kursi>0): $active\n";

// 3. Show some sample rute field formats
echo "\n--- Sample rute field formats (first 5): ---\n";
$samples = DriverJadwal::select('id_jadwal_driver', 'rute', 'tanggal', 'status', 'total_kursi', 'kursi_terisi')
    ->limit(5)
    ->get();

foreach ($samples as $sample) {
    echo sprintf(
        "ID: %d | Rute: '%s' | Tanggal: %s | Status: %s | Kursi: %d/%d\n",
        $sample->id_jadwal_driver,
        $sample->rute,
        $sample->tanggal,
        $sample->status,
        $sample->kursi_terisi,
        $sample->total_kursi
    );
}

// 4. Test getDetailRute() method
echo "\n--- Testing getDetailRute() parsing: ---\n";
foreach ($samples as $sample) {
    $detail = $sample->getDetailRute();
    echo sprintf(
        "Rute: '%s' => Asal: '%s' | Tujuan: '%s'\n",
        $sample->rute,
        $detail['kota_asal'] ?? 'N/A',
        $detail['kota_tujuan'] ?? 'N/A'
    );
}

// 5. Test LIKE query matching
echo "\n--- Testing LIKE query matching: ---\n";

$testCases = [
    'Jakarta',
    'Bandung',
    'Jakarta→Bandung',
    'Jakarta → Bandung'
];

foreach ($testCases as $testCase) {
    $count = DriverJadwal::where('status', 'aktif')
        ->where('tanggal', '>=', now()->toDateString())
        ->whereRaw('total_kursi > kursi_terisi')
        ->where('rute', 'like', '%' . $testCase . '%')
        ->count();
    
    echo "LIKE '%$testCase%': Found $count records\n";
}

// 6. Test the exact search query from the controller
echo "\n--- Testing exact search query logic: ---\n";

$asal = 'Jakarta';
$tujuan = 'Bandung';
$tanggal = null;
$penumpang = 1;

$query = DriverJadwal::query();
$query->where('status', 'aktif');
$query->where('tanggal', '>=', now()->toDateString());

if ($asal) {
    $query->where('rute', 'like', '%' . $asal . '%');
}

if ($tujuan) {
    $query->where('rute', 'like', '%' . $tujuan . '%');
}

if ($tanggal) {
    $query->where('tanggal', $tanggal);
}

$query->whereRaw('(total_kursi - kursi_terisi) >= ?', [$penumpang]);

$results = $query->count();
echo "Search for asal='$asal', tujuan='$tujuan': Found $results records\n";

// Show the SQL query
echo "SQL: " . $query->toSql() . "\n";
echo "Bindings: " . json_encode($query->getBindings()) . "\n";

// 7. Get unique city list from dropdown logic
echo "\n--- Unique cities from dropdown logic: ---\n";

$kotaAsalList = DriverJadwal::where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->whereRaw('total_kursi > kursi_terisi')
    ->get()
    ->map(function($item) {
        if (strpos($item->rute, '→') !== false) {
            $parts = explode('→', $item->rute);
            return trim($parts[0] ?? null);
        } elseif (preg_match('/\(([^→]+)→/', $item->rute, $matches)) {
            return trim($matches[1]);
        }
        return null;
    })
    ->filter()
    ->unique()
    ->values();

echo "Kota Asal List: " . implode(", ", $kotaAsalList->toArray()) . "\n";

$kotaTujuanList = DriverJadwal::where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->whereRaw('total_kursi > kursi_terisi')
    ->get()
    ->map(function($item) {
        if (strpos($item->rute, '→') !== false) {
            $parts = explode('→', $item->rute);
            return trim($parts[1] ?? null);
        } elseif (preg_match('/→([^)]+)\)/', $item->rute, $matches)) {
            return trim($matches[1]);
        }
        return null;
    })
    ->filter()
    ->unique()
    ->values();

echo "Kota Tujuan List: " . implode(", ", $kotaTujuanList->toArray()) . "\n";

echo "\n=== TEST COMPLETE ===\n";
