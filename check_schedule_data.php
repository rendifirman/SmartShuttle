<?php
require 'bootstrap/app.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Foundation\Application;
use App\Models\DriverJadwal;
use App\Models\Pemesanan;
use Carbon\Carbon;

$today = Carbon::today();

echo "=== CHECK DRIVER JADWAL DATA ===\n\n";

// Get all drivers
$drivers = \App\Models\User::where('role', 'driver')->get();

foreach ($drivers as $driver) {
    echo "Driver: {$driver->name} (ID: {$driver->id})\n";

    $trips = DriverJadwal::with(['jadwal', 'masterRute', 'jadwal.rutes'])
        ->where('id_driver', $driver->id)
        ->where('tanggal', '>=', $today)
        ->orderBy('tanggal', 'asc')
        ->orderBy('waktu_keberangkatan', 'asc')
        ->get();

    echo "  - Total trips: " . $trips->count() . "\n";

    foreach ($trips as $trip) {
        $bookings = Pemesanan::where('id_jadwal_driver', $trip->id_jadwal_driver)
            ->whereIn('status', ['dibayar'])
            ->count();

        echo "    Trip ID: {$trip->id_jadwal_driver}, Date: {$trip->tanggal}, Time: {$trip->waktu_keberangkatan}, Status: {$trip->status}, Bookings: {$bookings}\n";
        echo "      From: " . ($trip->jadwal?->asal ?? 'N/A') . "\n";
        echo "      To: " . ($trip->jadwal?->tujuan ?? 'N/A') . "\n";
        echo "      Rutes: " . ($trip->jadwal?->rutes?->count() ?? 0) . "\n";
    }

    echo "\n";
}

echo "\n=== CHECK PEMESANAN STATUS ===\n";
$allBookings = Pemesanan::select('id_jadwal_driver', 'status')->distinct()->get();
echo "Total unique (trip, status) combinations:\n";
$allBookings->groupBy('id_jadwal_driver')->each(function($group) {
    echo "  Trip {$group[0]->id_jadwal_driver}: " . implode(', ', $group->pluck('status')->unique()->toArray()) . "\n";
});
?>
