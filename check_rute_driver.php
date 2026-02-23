<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== RUTES DATA ===\n";
$rutes = \App\Models\Rute::select('id', 'nama_rute', 'kota_asal', 'cabang_asal_id')->limit(10)->get();
foreach($rutes as $rute) {
    echo "ID: {$rute->id}, Nama: {$rute->nama_rute}, Kota: {$rute->kota_asal}, Cabang ID: " . ($rute->cabang_asal_id ?? 'NULL') . "\n";
}

echo "\n=== DRIVERS DATA (Role: driver) ===\n";
$drivers = \App\Models\User::where('role', 'driver')->select('id', 'name', 'branch_id', 'schedule_accept_mode')->limit(10)->get();
foreach($drivers as $driver) {
    echo "ID: {$driver->id}, Name: {$driver->name}, Branch ID: " . ($driver->branch_id ?? 'NULL') . ", Mode: {$driver->schedule_accept_mode}\n";
}
