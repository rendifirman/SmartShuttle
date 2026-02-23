<?php
/**
 * Backend Driver Jadwal Connection Test
 *
 * Test apakah kursi berhasil tersimpan dengan driver jadwal dengan benar
 *
 * Run dengan: php artisan tinker < test_driver_jadwal_seats.php
 */

use App\Models\Pemesanan;
use App\Models\DetailPenumpang;
use App\Models\KursiTerpesan;
use App\Models\DriverJadwal;
use Carbon\Carbon;

echo "\n=== DRIVER JADWAL SEAT SELECTION TEST ===\n\n";

// =====================================================
// TEST 1: Check Recent Driver Jadwal Bookings
// =====================================================
echo "TEST 1: Recent Driver Jadwal Bookings\n";
echo "--------------------------------------\n";

$recentDriverJadwalBookings = Pemesanan::whereNotNull('id_jadwal_driver')
    ->with('driverJadwal', 'detailPenumpang')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

if ($recentDriverJadwalBookings->count() > 0) {
    echo "✓ Found " . $recentDriverJadwalBookings->count() . " recent driver jadwal bookings:\n\n";

    foreach ($recentDriverJadwalBookings as $booking) {
        echo "Booking ID: {$booking->id} | Kode: {$booking->kode_booking}\n";
        echo "  - Status: {$booking->status}\n";
        echo "  - Driver Jadwal ID: {$booking->id_jadwal_driver}\n";
        echo "  - Jumlah Penumpang: {$booking->jumlah_penumpang}\n";
        echo "  - Detail Penumpang Count: " . $booking->detailPenumpang->count() . "\n";

        $seatsAssigned = $booking->detailPenumpang->filter(function($dp) {
            return !empty($dp->nomor_kursi);
        })->count();

        echo "  - Seats Assigned: $seatsAssigned\n";

        if ($seatsAssigned > 0) {
            $seatsArray = $booking->detailPenumpang
                ->filter(function($dp) { return !empty($dp->nomor_kursi); })
                ->pluck('nomor_kursi')
                ->toArray();
            echo "  - Seat Numbers: " . implode(', ', $seatsArray) . "\n";
        }

        // Check KursiTerpesan records
        $tertipesan = KursiTerpesan::where('pemesanan_id', $booking->id)
            ->where('id_jadwal_driver', $booking->id_jadwal_driver)
            ->get();

        echo "  - KursiTerpesan Records: " . $tertipesan->count() . "\n";

        if ($tertipesan->count() > 0) {
            foreach ($tertipesan as $kt) {
                echo "    → Seat: {$kt->nomor_kursi} | Status: {$kt->status}\n";
            }
        }

        echo "\n";
    }
} else {
    echo "⚠ No recent driver jadwal bookings found\n\n";
}

// =====================================================
// TEST 2: Check Driver Jadwal Occupancy Consistency
// =====================================================
echo "TEST 2: Driver Jadwal Occupancy Consistency\n";
echo "--------------------------------------------\n";

$driverJadwals = DriverJadwal::where('status', '!=', 'dibatalkan')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

if ($driverJadwals->count() > 0) {
    echo "✓ Checking " . $driverJadwals->count() . " driver jadwals:\n\n";

    foreach ($driverJadwals as $dj) {
        // Get actual seat count from database
        $actualSeatsDb = KursiTerpesan::where('id_jadwal_driver', $dj->id_jadwal_driver)
            ->where('status', 'terpesan')
            ->count();

        // Get recorded count
        $recordedSeats = $dj->kursi_terisi ?? 0;

        $match = $actualSeatsDb === $recordedSeats ? "✓" : "✗";

        echo "Driver Jadwal {$dj->id_jadwal_driver}: $match\n";
        echo "  - Recorded: $recordedSeats | Actual in DB: $actualSeatsDb\n";
        echo "  - Total Capacity: {$dj->total_kursi}\n";

        if ($actualSeatsDb !== $recordedSeats) {
            echo "  - ⚠ MISMATCH! Difference: " . abs($actualSeatsDb - $recordedSeats) . "\n";
        }

        echo "\n";
    }
} else {
    echo "⚠ No driver jadwals found\n\n";
}

// =====================================================
// TEST 3: Check for Seat Conflicts
// =====================================================
echo "TEST 3: Check for Seat Conflicts\n";
echo "---------------------------------\n";

// Find seats that appear multiple times in same driver jadwal
$conflicts = KursiTerpesan::whereNotNull('id_jadwal_driver')
    ->where('status', 'terpesan')
    ->selectRaw('id_jadwal_driver, nomor_kursi, COUNT(*) as count')
    ->groupBy('id_jadwal_driver', 'nomor_kursi')
    ->havingRaw('COUNT(*) > 1')
    ->get();

if ($conflicts->count() > 0) {
    echo "✗ FAIL: Found " . $conflicts->count() . " duplicate seat assignments!\n";
    foreach ($conflicts as $conflict) {
        echo "  - Driver Jadwal {$conflict->id_jadwal_driver}: Seat {$conflict->nomor_kursi} appears {$conflict->count}x\n";
    }
} else {
    echo "✓ PASS: No seat conflicts detected\n";
}
echo "\n";

// =====================================================
// TEST 4: Validate Query Logic for Reserved Seats Check
// =====================================================
echo "TEST 4: Query Logic Validation\n";
echo "-------------------------------\n";

$testPemesanan = Pemesanan::whereNotNull('id_jadwal_driver')
    ->with('driverJadwal')
    ->where('status', 'menunggu_konfirmasi')
    ->first();

if ($testPemesanan) {
    echo "Using booking: {$testPemesanan->kode_booking}\n\n";

    // Simulate the query from prosesPemilihanKursi
    $testSeats = ['A1', 'A2']; // Test seats

    $otherBookingSeats = KursiTerpesan::where('id_jadwal_driver', $testPemesanan->id_jadwal_driver)
        ->whereIn('nomor_kursi', $testSeats)
        ->where('status', 'terpesan')
        ->where('pemesanan_id', '!=', $testPemesanan->id)
        ->whereHas('pemesanan', function($query) {
            $query->whereNotIn('status', ['dibatalkan', 'expired']);
        })
        ->pluck('nomor_kursi')
        ->toArray();

    echo "Testing query for seats [" . implode(', ', $testSeats) . "]\n";
    echo "Driver Jadwal ID: {$testPemesanan->id_jadwal_driver}\n";
    echo "Current Booking ID: {$testPemesanan->id}\n\n";

    if (count($otherBookingSeats) > 0) {
        echo "✓ Query found conflicts: " . implode(', ', $otherBookingSeats) . "\n";
    } else {
        echo "✓ Query executed successfully - no conflicts for these seats\n";
    }
} else {
    echo "⚠ No test booking available\n";
}
echo "\n";

// =====================================================
// SUMMARY & RECOMMENDATIONS
// =====================================================
echo "=== SUMMARY ===\n";
echo "✓ Tests completed\n\n";

echo "Recommendations:\n";
echo "1. If occupancy mismatch found: Run migration to recalculate kursi_terisi\n";
echo "2. If seat conflicts found: Clean up duplicate entries manually\n";
echo "3. If query test failed: Check pemesanan-kursiTerpesan relationship\n";
echo "\n";

return "Test completed\n";
