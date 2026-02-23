#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DriverJadwal;

echo "=== Testing Improved Search Logic ===\n\n";

// Get unique cities
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

echo "Unique Asal Cities from correct parsing: " . implode(", ", $kotaAsalList->toArray()) . "\n\n";

// Test with first asal city
if ($kotaAsalList->count() > 0) {
    foreach ($kotaAsalList as $asal) {
        $tujuan = null;
        
        echo "--- Testing: asal='$asal' ---\n";
        
        // Test old logic (naive explode)
        $countOld = DriverJadwal::where('status', 'aktif')
            ->where('tanggal', '>=', now()->toDateString())
            ->where('rute', 'like', '%' . $asal . '%')
            ->count();
        
        echo "OLD Logic - LIKE '%$asal%': Found $countOld records\n";
        
        // Test new logic (with parenthesis pattern matching)
        $countNew = DriverJadwal::where('status', 'aktif')
            ->where('tanggal', '>=', now()->toDateString())
            ->where(function($q) use ($asal) {
                $q->where('rute', 'like', '%(' . $asal . '%')
                  ->orWhere('rute', 'like', '% ' . $asal . '%');
            })
            ->count();
        
        echo "NEW Logic - LIKE '%($asal%' OR LIKE '% $asal%': Found $countNew records\n";
        
        // Show details
        echo "Details:\n";
        DriverJadwal::where('status', 'aktif')
            ->where('tanggal', '>=', now()->toDateString())
            ->where(function($q) use ($asal) {
                $q->where('rute', 'like', '%(' . $asal . '%')
                  ->orWhere('rute', 'like', '% ' . $asal . '%');
            })
            ->get()
            ->each(function($dj) {
                echo "  Rute: '{$dj->rute}'\n";
            });
        
        echo "\n";
    }
}

// Test search with asal and tujuan
echo "\n--- Testing combined search (asal='Jakarta', tujuan='Bandung') ---\n";

$asal = 'Jakarta';
$tujuan = 'Bandung';

$count = DriverJadwal::where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->where(function($q) use ($asal) {
        $q->where('rute', 'like', '%(' . $asal . '%')
          ->orWhere('rute', 'like', '% ' . $asal . '%');
    })
    ->where(function($q) use ($tujuan) {
        $q->where('rute', 'like', '%' . $tujuan . '%');
    })
    ->whereRaw('(total_kursi - kursi_terisi) >= 1')
    ->count();

echo "Found: $count records\n";

DriverJadwal::where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString())
    ->where(function($q) use ($asal) {
        $q->where('rute', 'like', '%(' . $asal . '%')
          ->orWhere('rute', 'like', '% ' . $asal . '%');
    })
    ->where(function($q) use ($tujuan) {
        $q->where('rute', 'like', '%' . $tujuan . '%');
    })
    ->whereRaw('(total_kursi - kursi_terisi) >= 1')
    ->get()
    ->each(function($dj) {
        $detail = $dj->getDetailRute();
        echo sprintf("  %s → %s (Rute: '%s')\n", 
            $detail['kota_asal'], 
            $detail['kota_tujuan'],
            substr($dj->rute, 0, 50)
        );
    });

echo "\n=== End Test ===\n";
