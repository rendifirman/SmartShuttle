#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DriverJadwal;

echo "=== DriverJadwal Search Debug ===\n\n";

// Check total
$total = DriverJadwal::count();
echo "Total DriverJadwals: $total\n";

if ($total == 0) {
    echo "\nERROR: No DriverJadwal data in database!\n";
    exit(1);
}

// Show sample data
echo "\nSample DriverJadwal records:\n";
echo str_repeat("-", 150) . "\n";

$samples = DriverJadwal::with('jadwal')->take(10)->get();
foreach ($samples as $dj) {
    echo sprintf(
        "ID: %4d | Rute: %-40s | Status: %-8s | Tanggal: %s | Kursi: %d/%d\n",
        $dj->id_jadwal_driver,
        substr($dj->rute, 0, 40),
        $dj->status,
        $dj->tanggal,
        $dj->kursi_terisi,
        $dj->total_kursi
    );
}

echo str_repeat("-", 150) . "\n";

// Check active count
$activeCount = DriverJadwal::where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->count();

echo "\nActive DriverJadwals (status=aktif, tanggal>=today): $activeCount\n";

// Check parsing
echo "\nDetailed rute field analysis (first 3):\n";
$samples->take(3)->each(function($dj) {
    echo "\nRute field: '{$dj->rute}'\n";
    
    // Test parsing methods
    if (strpos($dj->rute, '→') !== false) {
        $parts = explode('→', $dj->rute);
        echo "  ✓ Contains '→' arrow - Parts: " . json_encode(array_map('trim', $parts)) . "\n";
    } else {
        echo "  ✗ Does NOT contain '→' arrow\n";
    }
    
    if (preg_match('/\(([^→]+)→([^)]+)\)/', $dj->rute, $matches)) {
        echo "  ✓ Matches regex pattern (City → City) - Asal: '{$matches[1]}', Tujuan: '{$matches[2]}'\n";
    } else {
        echo "  ✗ Does NOT match regex pattern\n";
    }
    
    // Test getDetailRute
    $detail = $dj->getDetailRute();
    echo "  getDetailRute(): Asal='{$detail['kota_asal']}', Tujuan='{$detail['kota_tujuan']}'\n";
});

// Test search query
echo "\n\nTesting search logic:\n";
echo str_repeat("-", 150) . "\n";

// Get unique cities from active jadwals
$kotaAsalUnique = DriverJadwal::where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->get()
    ->map(function($item) {
        $detail = $item->getDetailRute();
        return $detail['kota_asal'] ?? null;
    })
    ->filter()
    ->unique()
    ->values();

echo "Unique Asal Cities: " . implode(", ", $kotaAsalUnique->toArray()) . "\n\n";

// Test search WITH first city
if ($kotaAsalUnique->count() > 0) {
    $testAsal = $kotaAsalUnique->first();
    echo "Testing search with asal='$testAsal':\n";
    
    $count = DriverJadwal::where('status', 'aktif')
        ->where('tanggal', '>=', now()->toDateString())
        ->where('rute', 'like', '%' . $testAsal . '%')
        ->count();
    
    echo "  LIKE '%$testAsal%': Found $count records\n";
    
    // Show matching records
    echo "  Details of matches:\n";
    DriverJadwal::where('status', 'aktif')
        ->where('tanggal', '>=', now()->toDateString())
        ->where('rute', 'like', '%' . $testAsal . '%')
        ->take(3)
        ->get()
        ->each(function($dj) use ($testAsal) {
            $detail = $dj->getDetailRute();
            echo sprintf("    - Rute: '%s' => Asal: '%s' (matched by LIKE %%$testAsal%%)\n", $dj->rute, $detail['kota_asal']);
        });
}

echo "\n=== End Debug ===\n";
