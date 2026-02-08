#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DriverJadwal;

echo "=== Verification Checklist ===\n\n";

// 1. Check beranda dropdown data
echo "1. Verify beranda() dropdown generation:\n";

$kotaAsalList = DriverJadwal::with(['jadwal.rutes'])
    ->tersediaUntukCustomer()
    ->get()
    ->map(function($item) {
        $detail = $item->getDetailRute();
        return $detail['kota_asal'] ?? null;
    })
    ->filter()
    ->unique()
    ->values();

echo "   Kota Asal List: " . implode(", ", $kotaAsalList->toArray()) . "\n";
echo "   Count: " . $kotaAsalList->count() . "\n";

$kotaTujuanList = DriverJadwal::with(['jadwal.rutes'])
    ->tersediaUntukCustomer()
    ->get()
    ->map(function($item) {
        $detail = $item->getDetailRute();
        return $detail['kota_tujuan'] ?? null;
    })
    ->filter()
    ->unique()
    ->values();

echo "   Kota Tujuan List: " . implode(", ", $kotaTujuanList->toArray()) . "\n";
echo "   Count: " . $kotaTujuanList->count() . "\n\n";

// 2. Simulate form submission
echo "2. Simulate user selecting asal='Jakarta', tujuan='Bandung':\n";

$asal = 'Jakarta';
$tujuan = 'Bandung';
$tanggal = null;
$penumpang = 1;

echo "   Form parameters: asal='$asal', tujuan='$tujuan', tanggal=" . ($tanggal ?? 'null') . ", penumpang=$penumpang\n\n";

// 3. Check if search would find results
echo "3. Check search() method would find results:\n";

$query = DriverJadwal::query()->with(['jadwal.rutes', 'driver'])
    ->where('status', 'aktif')
    ->where('tanggal', '>=', now()->toDateString());

if ($asal) {
    $query->where(function($q) use ($asal) {
        $q->where('rute', 'like', '%(' . $asal . '%')
          ->orWhere('rute', 'like', '% ' . $asal . '%');
    });
}

if ($tujuan) {
    $query->where(function($q) use ($tujuan) {
        $q->where('rute', 'like', '%' . $tujuan . '%');
    });
}

if ($tanggal) {
    $query->where('tanggal', $tanggal);
}

$query->whereRaw('(total_kursi - kursi_terisi) >= ?', [$penumpang]);

$count = $query->count();
echo "   Query would return: $count results\n";

if ($count > 0) {
    echo "   Sample results:\n";
    $query->take(3)->get()->each(function($dj) {
        $detail = $dj->getDetailRute();
        echo sprintf("     - %s → %s (Rute: %s)\n", 
            $detail['kota_asal'], 
            $detail['kota_tujuan'],
            substr($dj->rute, 0, 40)
        );
    });
}

echo "\n";

// 4. Verify dates
echo "4. Check date filters:\n";

$today = now()->toDateString();
echo "   Today's date: $today\n";
echo "   Available DriverJadwal dates:\n";
DriverJadwal::distinct()
    ->select('tanggal')
    ->orderBy('tanggal', 'asc')
    ->get()
    ->each(function($dj) {
        echo sprintf("     - %s\n", $dj->tanggal);
    });

echo "\n";

// 5. Check status values
echo "5. Check status values in database:\n";
DriverJadwal::distinct()
    ->select('status')
    ->get()
    ->each(function($dj) {
        $count = DriverJadwal::where('status', $dj->status)->count();
        echo "   Status='$dj->status': $count records\n";
    });

echo "\n=== End Verification ===\n";
