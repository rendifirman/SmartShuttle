#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DriverJadwal;

echo "=== SHUTTLE SEARCH DIAGNOSTIC ===\n\n";

// Test 1: Check if driver_jadwals has test data
echo "TEST 1: DriverJadwal data check\n";
echo str_repeat("-", 70) . "\n";

$total = DriverJadwal::count();
$active = DriverJadwal::where('status', 'aktif')->count();
$future = DriverJadwal::where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->count();

echo "Total records: $total\n";
echo "Active records: $active\n";
echo "Active & future dates: $future\n";

if ($future == 0) {
    echo "\n❌ NO ACTIVE FUTURE DATA - Search will return empty results\n";
    echo "Need to create test data with status='aktif' and future dates\n";
    exit(1);
}

echo "✓ Test data exists\n\n";

// Test 2: Simulate search query
echo "TEST 2: Simulate search query\n";
echo str_repeat("-", 70) . "\n";

// Get a sample from database to understand rute format
$sample = DriverJadwal::first();
if ($sample) {
    echo "Sample rute format: '{$sample->rute}'\n\n";
}

// Simulate user search parameters THAT WOULD WORK
$testCases = [
    ['asal' => 'Jakarta', 'tujuan' => 'Bandung', 'tanggal' => null, 'penumpang' => 1],
    ['asal' => 'Bandung', 'tujuan' => 'Jakarta', 'tanggal' => null, 'penumpang' => 2],
];

foreach ($testCases as $testCase) {
    $asal = $testCase['asal'];
    $tujuan = $testCase['tujuan'];
    $tanggal = $testCase['tanggal'];
    $penumpang = $testCase['penumpang'];

    echo "TEST CASE: asal='$asal', tujuan='$tujuan', tanggal=" . ($tanggal ? "'$tanggal'" : "today") . ", penumpang=$penumpang\n";

    // Build query exactly as controller does
    $query = DriverJadwal::select('id_jadwal_driver', 'rute', 'tanggal', 'status', 'waktu_keberangkatan', 'total_kursi', 'kursi_terisi')
        ->where('status', 'aktif')
        ->where('tanggal', '>=', now()->toDateString())
        ->whereRaw('(total_kursi - kursi_terisi) >= ?', [$penumpang]);

    // Apply LIKE filters exactly as controller does
    $query->where(function($q) use ($asal, $tujuan) {
        $q->where('rute', 'LIKE', "%{$asal}%{$tujuan}%")
          ->orWhere('rute', 'LIKE', "%{$asal} %{$tujuan}%")
          ->orWhere('rute', 'LIKE', "%{$asal}→%{$tujuan}%")
          ->orWhere('rute', 'LIKE', "%{$asal}->%{$tujuan}%");
    });

    // Apply date filter
    if ($tanggal) {
        $query->whereDate('tanggal', $tanggal);
    }

    $count = $query->count();
    echo "  Result count: $count\n";

    if ($count > 0) {
        $results = $query->take(2)->get();
        echo "  Sample results:\n";
        $results->each(function($r) {
            echo "    - Rute: '{$r->rute}' | Date: {$r->tanggal} | Seats: {$r->kursi_terisi}/{$r->total_kursi}\n";
        });
    } else {
        echo "  ❌ No results found\n";
        
        // Debug: Show what's in database
        echo "    Debug - All rute values in database:\n";
        DriverJadwal::where('status', 'aktif')
            ->where('tanggal', '>=', now()->toDateString())
            ->select('rute')
            ->distinct()
            ->get()
            ->each(function($r) {
                echo "      '{$r->rute}'\n";
            });
    }

    echo "\n";
}

// Test 3: Check dropdown data generation
echo "TEST 3: Dropdown data generation\n";
echo str_repeat("-", 70) . "\n";

$kotaAsalList = DriverJadwal::where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->get()
    ->map(function($item) {
        $detail = $item->getDetailRute();
        return $detail['kota_asal'] ?? null;
    })
    ->filter()
    ->unique()
    ->values();

$kotaTujuanList = DriverJadwal::where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->get()
    ->map(function($item) {
        $detail = $item->getDetailRute();
        return $detail['kota_tujuan'] ?? null;
    })
    ->filter()
    ->unique()
    ->values();

echo "Kota Asal: " . implode(", ", $kotaAsalList->toArray()) . "\n";
echo "Kota Tujuan: " . implode(", ", $kotaTujuanList->toArray()) . "\n";

if ($kotaAsalList->isEmpty() || $kotaTujuanList->isEmpty()) {
    echo "\n❌ Dropdown lists are empty - getDetailRute() not working\n";
} else {
    echo "\n✓ Dropdown lists populated\n";
}

echo "\n=== END DIAGNOSTIC ===\n";
