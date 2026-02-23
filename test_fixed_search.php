#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DriverJadwal;
use Illuminate\Http\Request;

echo "=== Testing Fixed Search Flow ===\n\n";

// Test 1: Verify beranda filtering works
echo "Test 1: Beranda filtering with Jakarta → Bandung\n";
echo str_repeat("-", 80) . "\n";

// Simulate beranda() query logic
$query = DriverJadwal::with(['driver', 'jadwal.rutes', 'jadwal.shuttle'])
    ->where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->whereColumn('kursi_terisi', '<', 'total_kursi');

// Test with asal=Jakarta, tujuan=Bandung (like the form would submit)
$asalParam = 'Jakarta';
$tujuanParam = 'Bandung';

if ($asalParam) {
    $query->where(function($q) use ($asalParam) {
        $q->where('rute', 'like', '%(' . $asalParam . '%')
          ->orWhere('rute', 'like', '% ' . $asalParam . '%');
    });
}

if ($tujuanParam) {
    $query->where('rute', 'like', '%' . $tujuanParam . '%');
}

$jadwals = $query->orderBy('tanggal', 'asc')
    ->orderBy('waktu_keberangkatan', 'asc')
    ->get();

echo "Results for: $asalParam → $tujuanParam\n";
echo "Found: " . $jadwals->count() . " schedules\n\n";

if ($jadwals->count() > 0) {
    echo "Schedule Details:\n";
    $jadwals->each(function($dj) {
        $detail = $dj->getDetailRute();
        echo sprintf(
            "  - %s → %s | Tanggal: %s | Kursi: %d/%d | Rute: %s\n",
            $detail['kota_asal'],
            $detail['kota_tujuan'],
            $dj->tanggal,
            $dj->kursi_terisi,
            $dj->total_kursi,
            substr($dj->rute, 0, 40)
        );
    });
} else {
    echo "No schedules found.\n";
}

// Test 2: Verify dropdown list generation
echo "\n\nTest 2: Dropdown list generation\n";
echo str_repeat("-", 80) . "\n";

$kotaAsalList = DriverJadwal::with(['jadwal.rutes'])
    ->where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->whereColumn('kursi_terisi', '<', 'total_kursi')
    ->get()
    ->map(function($item) {
        $detail = $item->getDetailRute();
        return $detail['kota_asal'] ?? null;
    })
    ->filter()
    ->unique()
    ->values();

$kotaTujuanList = DriverJadwal::with(['jadwal.rutes'])
    ->where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->whereColumn('kursi_terisi', '<', 'total_kursi')
    ->get()
    ->map(function($item) {
        $detail = $item->getDetailRute();
        return $detail['kota_tujuan'] ?? null;
    })
    ->filter()
    ->unique()
    ->values();

echo "Kota Asal dropdown options: " . implode(", ", $kotaAsalList->toArray()) . "\n";
echo "Kota Tujuan dropdown options: " . implode(", ", $kotaTujuanList->toArray()) . "\n";

// Test 3: Verify search() method logic
echo "\n\nTest 3: Search method filtering (same as search blade would use)\n";
echo str_repeat("-", 80) . "\n";

$searchQuery = DriverJadwal::query()->with(['jadwal.rutes', 'driver'])
    ->where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString());

// Apply filters
$asal = 'Jakarta';
$tujuan = 'Bandung';
$penumpang = 1;

if ($asal) {
    $searchQuery->where(function($q) use ($asal) {
        $q->where('rute', 'like', '%(' . $asal . '%')
          ->orWhere('rute', 'like', '% ' . $asal . '%');
    });
}

if ($tujuan) {
    $searchQuery->where(function($q) use ($tujuan) {
        $q->where('rute', 'like', '%' . $tujuan . '%');
    });
}

$searchQuery->whereRaw('(total_kursi - kursi_terisi) >= ?', [$penumpang]);

$searchResults = $searchQuery->orderBy('tanggal', 'asc')
    ->orderBy('waktu_keberangkatan', 'asc')
    ->get();

echo "Search results for asal='$asal', tujuan='$tujuan', penumpang=$penumpang\n";
echo "Found: " . $searchResults->count() . " schedules\n\n";

if ($searchResults->count() > 0) {
    echo "Results:\n";
    $searchResults->each(function($dj) {
        echo sprintf(
            "  - %s | Kursi: %d/%d | Harga: Rp%s\n",
            substr($dj->rute, 0, 50),
            $dj->kursi_terisi,
            $dj->total_kursi,
            number_format($dj->harga, 0, ',', '.')
        );
    });
}

echo "\n\n=== Summary ===\n";
echo "✓ Beranda filtering works: " . ($jadwals->count() > 0 ? "YES" : "NO - Check data") . "\n";
echo "✓ Dropdowns populated: " . ($kotaAsalList->count() > 0 && $kotaTujuanList->count() > 0 ? "YES" : "NO - Check data") . "\n";
echo "✓ Search filtering works: " . ($searchResults->count() > 0 ? "YES" : "NO - Check data") . "\n";

echo "\n=== End Test ===\n";
