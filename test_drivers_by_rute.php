<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING BRANCHES ===\n";
$branches = \App\Models\Branch::select('id', 'nama_cabang', 'kota', 'status')->get();
foreach($branches as $branch) {
    echo "ID: {$branch->id}, Nama: {$branch->nama_cabang}, Kota: {$branch->kota}, Status: {$branch->status}\n";
}

echo "\n=== CHECKING DRIVERS ===\n";
$drivers = \App\Models\User::select('id', 'name', 'branch_id', 'schedule_accept_mode', 'status')->limit(10)->get();
foreach($drivers as $driver) {
    echo "ID: {$driver->id}, Name: {$driver->name}, Branch ID: " . ($driver->branch_id ?? 'NULL') . ", Mode: {$driver->schedule_accept_mode}, Status: {$driver->status}\n";
}

echo "\n=== TEST GETDRIVERSBYRUTE FOR ROUTE 1 ===\n";
$rute = \App\Models\Rute::find(1);
if ($rute) {
    echo "Rute: {$rute->nama_rute}, Kota Asal: {$rute->kota_asal}\n";

    $branch = \App\Models\Branch::where('kota', $rute->kota_asal)->where('status', 'aktif')->first();
    if ($branch) {
        echo "Branch Ditemukan: {$branch->nama_cabang} (ID: {$branch->id})\n";

        $drivers = \App\Models\User::where('branch_id', $branch->id)
            ->where('schedule_accept_mode', 'AUTO_ACCEPT')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        echo "Drivers Ditemukan: " . count($drivers) . "\n";
        foreach($drivers as $driver) {
            echo "  - {$driver->name} ({$driver->email})\n";
        }
    } else {
        echo "Branch NOT FOUND untuk kota: {$rute->kota_asal}\n";
    }
}
