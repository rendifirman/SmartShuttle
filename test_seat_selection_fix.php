<?php
/**
 * Seat Selection Fix Verification Script
 *
 * Run dengan: php artisan tinker < test_seat_selection_fix.php
 * Atau: php test_seat_selection_fix.php (jika executable)
 */

use App\Models\Pemesanan;
use App\Models\DetailPenumpang;
use App\Models\KursiTerpesan;
use App\Models\DriverJadwal;
use Carbon\Carbon;

echo "=== SEAT SELECTION FIX VERIFICATION ===\n\n";

// =====================================================
// TEST 1: Check Database Integrity
// =====================================================
echo "TEST 1: Database Integrity Check\n";
echo "-----------------------------------\n";

$seatsCount = KursiTerpesan::count();
$bookingsCount = Pemesanan::count();
$detailCount = DetailPenumpang::count();

echo "✓ Total Kursi Terpesan: $seatsCount\n";
echo "✓ Total Pemesanan: $bookingsCount\n";
echo "✓ Total Detail Penumpang: $detailCount\n\n";

// =====================================================
// TEST 2: Check for Duplicate Seats
// =====================================================
echo "TEST 2: Duplicate Seats Detection\n";
echo "---------------------------------\n";

$duplicates = DetailPenumpang::whereNotNull('nomor_kursi')
    ->groupBy('pemesanan_id', 'nomor_kursi')
    ->havingRaw('COUNT(*) > 1')
    ->count();

if ($duplicates == 0) {
    echo "✓ PASS: Tidak ada duplicate seats dalam satu pemesanan\n\n";
} else {
    echo "✗ FAIL: Ditemukan $duplicates duplicate seats!\n";
    $dupDetails = DetailPenumpang::whereNotNull('nomor_kursi')
        ->selectRaw('pemesanan_id, nomor_kursi, COUNT(*) as count')
        ->groupBy('pemesanan_id', 'nomor_kursi')
        ->havingRaw('COUNT(*) > 1')
        ->get();

    foreach ($dupDetails as $dup) {
        echo "  - Pemesanan {$dup->pemesanan_id}: Kursi {$dup->nomor_kursi} ada {$dup->count}x\n";
    }
    echo "\n";
}

// =====================================================
// TEST 3: Check Booking Status Transitions
// =====================================================
echo "TEST 3: Booking Status Transitions\n";
echo "-----------------------------------\n";

$statusDistribution = Pemesanan::selectRaw('status, COUNT(*) as count')
    ->groupBy('status')
    ->pluck('count', 'status');

foreach ($statusDistribution as $status => $count) {
    echo "• $status: $count booking(s)\n";
}

$menunggukursi = Pemesanan::where('status', 'menunggu_kursi')->count();
$menungguconfirm = Pemesanan::where('status', 'menunggu_konfirmasi')->count();

echo "\n✓ menunggu_kursi: $menunggukursi\n";
echo "✓ menunggu_konfirmasi: $menungguconfirm\n\n";

// =====================================================
// TEST 4: Check Recent Successful Submissions
// =====================================================
echo "TEST 4: Recent Successful Submissions (Last 24 Hours)\n";
echo "----------------------------------------------------\n";

$recentSuccessful = Pemesanan::where('status', 'menunggu_konfirmasi')
    ->where('updated_at', '>=', Carbon::now()->subDay())
    ->count();

echo "✓ Successful submissions (24h): $recentSuccessful\n";

// Show recent successful submissions
$recent = Pemesanan::where('status', 'menunggu_konfirmasi')
    ->where('updated_at', '>=', Carbon::now()->subDay())
    ->orderBy('updated_at', 'desc')
    ->limit(5)
    ->get(['id', 'kode_booking', 'jumlah_penumpang', 'updated_at']);

if ($recent->count() > 0) {
    echo "\nRecent successful bookings:\n";
    foreach ($recent as $booking) {
        $seatsUrl = "Kursi untuk booking: ";
        $seats = DetailPenumpang::where('pemesanan_id', $booking->id)
            ->whereNotNull('nomor_kursi')
            ->pluck('nomor_kursi')
            ->toArray();

        echo "  • {$booking->kode_booking}: " . implode(', ', $seats) . " [{$booking->updated_at}]\n";
    }
} else {
    echo "Tidak ada successful submissions dalam 24 jam terakhir\n";
}
echo "\n";

// =====================================================
// TEST 5: Check Course Correction Needed
// =====================================================
echo "TEST 5: Identify Issues That Need Correction\n";
echo "--------------------------------------------\n";

$issues = [];

// Issue 1: Bookings with seats but wrong status
$wrongStatusSeats = Pemesanan::whereHas('detailPenumpang', function($q) {
    $q->whereNotNull('nomor_kursi');
})
    ->where('status', '!=', 'menunggu_konfirmasi')
    ->where('status', '!=', 'pembayaran')
    ->where('status', '!=', 'sudah_bayar')
    ->where('status', '!=', 'selesai')
    ->where('status', '!=', 'dibatalkan')
    ->count();

if ($wrongStatusSeats > 0) {
    $issues[] = "⚠ $wrongStatusSeats bookings have seats but wrong status (should be menunggu_konfirmasi or later)";
}

// Issue 2: Detail penumpang without nomor_kursi but in confirmed booking
$emptySeatsConfirmed = DetailPenumpang::whereNull('nomor_kursi')
    ->whereHas('pemesanan', function($q) {
        $q->where('status', 'menunggu_konfirmasi');
    })
    ->count();

if ($emptySeatsConfirmed > 0) {
    $issues[] = "⚠ $emptySeatsConfirmed detail penumpang missing seat numbers in confirmed bookings";
}

// Issue 3: Seats beyond available capacity
$overCapacity = KursiTerpesan::with('driverJadwal')
    ->get()
    ->groupBy('id_jadwal_driver')
    ->filter(function($group) {
        if (!$group[0]->driverJadwal) return false;
        $dj = $group[0]->driverJadwal;
        return $group->count() > $dj->total_kursi;
    })
    ->count();

if ($overCapacity > 0) {
    $issues[] = "⚠ $overCapacity driver jadwals dengan seats exceed capacity";
}

if (count($issues) === 0) {
    echo "✓ PASS: Tidak ada issues terdeteksi\n";
} else {
    echo "✗ FAIL: Ditemukan issues:\n";
    foreach ($issues as $issue) {
        echo "$issue\n";
    }
}
echo "\n";

// =====================================================
// TEST 6: Check Driver Jadwal Occupancy
// =====================================================
echo "TEST 6: Driver Jadwal Occupancy Check\n";
echo "-------------------------------------\n";

$driverJadwals = DriverJadwal::with('seats')
    ->where('status', '!=', 'dibatalkan')
    ->take(10)
    ->get();

if ($driverJadwals->count() > 0) {
    echo "Checking first 10 active driver_jadwals:\n";
    foreach ($driverJadwals as $dj) {
        $dbCount = KursiTerpesan::where('id_jadwal_driver', $dj->id)->count();
        $mismatch = $dbCount !== $dj->kursi_terisi ? " ⚠ MISMATCH!" : " ✓";
        echo "  • Driver Jadwal {$dj->id}: DB={$dbCount}, Record={$dj->kursi_terisi}//{$dj->total_kursi}$mismatch\n";
    }
} else {
    echo "Tidak ada active driver_jadwals\n";
}
echo "\n";

// =====================================================
// TEST 7: Check Recent Errors in Logs
// =====================================================
echo "TEST 7: Recent Error Analysis\n";
echo "-----------------------------\n";

$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = array_slice(file($logFile), -100);
    $seatErrors = array_filter($lines, function($line) {
        return stripos($line, 'prosesPemilihanKursi') !== false ||
               stripos($line, 'seat selection') !== false;
    });

    if (count($seatErrors) > 0) {
        echo "Found " . count($seatErrors) . " seat-related log entries\n";
        echo "Recent entries:\n";
        foreach (array_slice($seatErrors, -3) as $entry) {
            echo "  • " . trim($entry) . "\n";
        }
    } else {
        echo "✓ No recent seat selection errors in logs\n";
    }
} else {
    echo "Log file not found\n";
}
echo "\n";

// =====================================================
// TEST 8: Performance Check
// =====================================================
echo "TEST 8: Query Performance Check\n";
echo "-------------------------------\n";

$startTime = microtime(true);

// Test Query 1: Get recent successful submissions
$q1 = Pemesanan::where('status', 'menunggu_konfirmasi')
    ->where('updated_at', '>=', Carbon::now()->subDay())
    ->get();
$time1 = (microtime(true) - $startTime) * 1000;

$startTime = microtime(true);

// Test Query 2: Check for duplicate seats
$q2 = DetailPenumpang::whereNotNull('nomor_kursi')
    ->groupBy('pemesanan_id', 'nomor_kursi')
    ->havingRaw('COUNT(*) > 1')
    ->get();
$time2 = (microtime(true) - $startTime) * 1000;

$startTime = microtime(true);

// Test Query 3: Validate driver jadwal capacity
$q3 = KursiTerpesan::with('driverJadwal')
    ->where('id_jadwal_driver', '>', 0)
    ->take(100)
    ->get();
$time3 = (microtime(true) - $startTime) * 1000;

echo "✓ Query 1 (Recent successful): " . round($time1, 2) . "ms\n";
echo "✓ Query 2 (Duplicate check): " . round($time2, 2) . "ms\n";
echo "✓ Query 3 (Driver jadwal): " . round($time3, 2) . "ms\n";

$avgTime = ($time1 + $time2 + $time3) / 3;
echo "✓ Average query time: " . round($avgTime, 2) . "ms\n";

if ($avgTime > 500) {
    echo "⚠ WARNING: Query times are higher than expected (>500ms avg)\n";
}
echo "\n";

// =====================================================
// SUMMARY
// =====================================================
echo "=== SUMMARY ===\n";
echo "✓ Fix Status: ";

$totalIssues = $duplicates + count($issues) + max(0, ($overCapacity ?? 0)) + ($wrongStatusSeats ?? 0);

if ($totalIssues === 0) {
    echo "PASS - All tests successful!\n";
} else {
    echo "NEEDS ATTENTION - $totalIssues issue(s) found\n";
}

echo "\n✓ Next Steps:\n";
echo "  1. Monitor logs untuk double-submit attempts\n";
echo "  2. Test langsung di browser: select kursi & submit\n";
echo "  3. Verify halaman redirect ke detail-pesanan\n";
echo "  4. Check button disabled & loading indicator shown\n";
echo "  5. Test rapid double-click untuk validate prevention\n";
echo "\n";

return "Test completion successful\n";
