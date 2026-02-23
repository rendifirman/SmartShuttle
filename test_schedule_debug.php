<?php
require 'bootstrap/app.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DriverJadwal;
use Carbon\Carbon;

echo "=== Today: " . Carbon::today()->format('Y-m-d') . " ===\n";
echo "Total DriverJadwal in DB: " . DriverJadwal::count() . "\n";
echo "\nSample DriverJadwal (limit 5):\n";

$samples = DriverJadwal::select('id_jadwal_driver', 'id_driver', 'tanggal', 'rute', 'waktu_keberangkatan')->limit(5)->get();
foreach($samples as $s) {
    echo "  - Driver: {$s->id_driver}, Tanggal: {$s->tanggal}, Rute: {$s->rute}\n";
}

echo "\nAll unique tanggal values:\n";
$dates = DriverJadwal::select('tanggal')->distinct()->orderBy('tanggal', 'desc')->limit(10)->get();
foreach($dates as $d) {
    echo "  - {$d->tanggal}\n";
}

echo "\n=== Checking driver schedules ===\n";
echo "Auth::guard('driver')->user(): ";
$driver = \Illuminate\Support\Facades\Auth::guard('driver')->user();
echo $driver ? "Driver found (ID: {$driver->id})\n" : "No driver logged in\n";

if ($driver) {
    $today = Carbon::today();
    echo "\nSchedules for driver {$driver->id} today ({$today->format('Y-m-d')}):\n";
    $todaySchedules = DriverJadwal::where('id_driver', $driver->id)
        ->whereDate('tanggal', $today)
        ->get();
    echo "Count: " . $todaySchedules->count() . "\n";

    foreach($todaySchedules as $s) {
        echo "  - {$s->rute} at {$s->waktu_keberangkatan}\n";
    }
}
