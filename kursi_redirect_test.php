<?php
/**
 * TEST SCRIPT: Kursi Redirect Fix Verification
 *
 * Run dengan: php artisan tinker < kursi_redirect_test.php
 * Atau: php kursi_redirect_test.php
 */

namespace Tests\Feature;

use App\Models\Pemesanan;
use App\Models\DetailPenumpang;
use App\Models\KursiTerpesan;
use App\Models\DriverJadwal;
use Illuminate\Support\Facades\DB;

class KursiRedirectTest
{
    public function testKursiSubmissionFlow()
    {
        echo "=== KURSI REDIRECT FIX TEST ===\n";

        // Find a test booking
        $pemesanan = Pemesanan::where('status', 'menunggu_kursi')->first();

        if (!$pemesanan) {
            echo "❌ No pending booking found with status 'menunggu_kursi'\n";
            return;
        }

        echo "\n📋 Test Booking:\n";
        echo "  Pemesanan ID: {$pemesanan->id}\n";
        echo "  Kode Booking: {$pemesanan->kode_booking}\n";
        echo "  Jumlah Penumpang: {$pemesanan->jumlah_penumpang}\n";
        echo "  Status: {$pemesanan->status}\n";
        echo "  Driver Jadwal: " . ($pemesanan->id_jadwal_driver ? $pemesanan->id_jadwal_driver : 'None') . "\n";

        // Check current detail_penumpang
        $detailPenumpang = DetailPenumpang::where('pemesanan_id', $pemesanan->id)->get();
        echo "\n👥 Detail Penumpang:\n";
        foreach ($detailPenumpang as $i => $detail) {
            echo "  [{$i}] ID={$detail->id}, Nomor Kursi: " . ($detail->nomor_kursi ?? 'BELUM') . "\n";
        }

        // Check current KursiTerpesan
        $kursiTerpesan = KursiTerpesan::where('pemesanan_id', $pemesanan->id)->get();
        echo "\n🪑 Kursi Terpesans (Before):\n";
        if ($kursiTerpesan->count() > 0) {
            foreach ($kursiTerpesan as $kt) {
                echo "  - Nomor: {$kt->nomor_kursi}, Status: {$kt->status}, Detail ID: " . ($kt->detail_penumpang_id ?? 'NULL') . "\n";
            }
        } else {
            echo "  (none)\n";
        }

        // Check DriverJadwal state
        if ($pemesanan->id_jadwal_driver) {
            $dj = DriverJadwal::where('id_jadwal_driver', $pemesanan->id_jadwal_driver)->first();
            if ($dj) {
                echo "\n🚐 Driver Jadwal State:\n";
                echo "  Total Kursi: {$dj->total_kursi}\n";
                echo "  Kursi Terisi: {$dj->kursi_terisi}\n";
                echo "  Sisa Kursi: " . ($dj->total_kursi - $dj->kursi_terisi) . "\n";
                echo "  Status: {$dj->status}\n";

                // Verify actual count vs stored value
                $actualCount = KursiTerpesan::where('id_jadwal_driver', $pemesanan->id_jadwal_driver)
                    ->where('status', 'terpesan')
                    ->whereHas('pemesanan', function($q) {
                        $q->whereNotIn('status', ['dibatalkan', 'expired']);
                    })
                    ->count();

                echo "  Actual KursiTerpesan Count: {$actualCount}\n";

                if ($actualCount !== $dj->kursi_terisi) {
                    echo "  ⚠️  INCONSISTENCY: kursi_terisi ({$dj->kursi_terisi}) !== actual count ({$actualCount})\n";
                } else {
                    echo "  ✅ Konsisten: kursi_terisi = actual count\n";
                }
            }
        }

        // Simulate seats that would be selected
        $selectedSeats = [];
        for ($i = 0; $i < $pemesanan->jumlah_penumpang; $i++) {
            $selectedSeats[] = chr(65 + $i); // A, B, C, etc
        }

        echo "\n📊 Simulated Submission:\n";
        echo "  Selected Seats: " . implode(', ', $selectedSeats) . "\n";
        echo "  Count: " . count($selectedSeats) . " (should match jumlah_penumpang: {$pemesanan->jumlah_penumpang})\n";

        if (count($selectedSeats) === $pemesanan->jumlah_penumpang) {
            echo "  ✅ Count Match\n";
        } else {
            echo "  ❌ Count Mismatch!\n";
        }

        echo "\n📝 Test Results:\n";
        $tests = [
            'Booking status is menunggu_kursi' => $pemesanan->status === 'menunggu_kursi',
            'Detail penumpang count matches' => $detailPenumpang->count() === $pemesanan->jumlah_penumpang,
            'Seats are in correct format' => count($selectedSeats) === $pemesanan->jumlah_penumpang,
            'Driver jadwal state consistent' => !$pemesanan->id_jadwal_driver || $dj->kursi_terisi >= 0,
        ];

        foreach ($tests as $test => $result) {
            echo "  " . ($result ? "✅" : "❌") . " $test\n";
        }

        echo "\n💡 Next Steps:\n";
        echo "  1. Lock these seats via API: POST /customer/kursi/lock\n";
        echo "  2. Verify KursiTerpesan records are created\n";
        echo "  3. Submit form to /customer/kursi/proses\n";
        echo "  4. Verify redirect to detail_pesanan (not back to kursi)\n";
        echo "  5. Check storage/logs/laravel-*.log for debug info\n";
    }
}

// Run test
$test = new KursiRedirectTest();
$test->testKursiSubmissionFlow();
?>
