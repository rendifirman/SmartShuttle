<?php
/**
 * DEBUG: Test Kursi Form Submission Flow
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a test request
$testData = [
    'pemesanan_id' => 1, // Ganti dengan ID yang valid
    'kursi' => ['1', '2', '3'], // Array of seat numbers
];

echo "Testing Kursi Form Submission\n";
echo "==============================\n\n";

echo "Test Data:\n";
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";

// Check Database
use Illuminate\Support\Facades\DB;
use App\Models\Pemesanan;
use App\Models\DetailPenumpang;

echo "Database Check:\n";
echo "---------------\n";

$pemesanan = Pemesanan::find(1);
if ($pemesanan) {
    echo "✓ Pemesanan ditemukan: ID={$pemesanan->id}, Status={$pemesanan->status}\n";
    echo "  - Jumlah Penumpang: {$pemesanan->jumlah_penumpang}\n";
    echo "  - Jadwal ID: {$pemesanan->jadwal_id}\n";
    echo "  - Driver Jadwal ID: {$pemesanan->id_jadwal_driver}\n";

    $detailCount = DetailPenumpang::where('pemesanan_id', 1)->count();
    echo "  - Detail Penumpang: {$detailCount}\n";

    // Check if seats match
    $requiredSeats = $pemesanan->jumlah_penumpang;
    $selectedSeats = count($testData['kursi']);
    echo "\n✓ Seat Count Check:\n";
    echo "  - Required: {$requiredSeats}\n";
    echo "  - Selected: {$selectedSeats}\n";
    echo "  - Match: " . ($requiredSeats === $selectedSeats ? "YES" : "NO") . "\n";
} else {
    echo "✗ Pemesanan tidak ditemukan\n";
}

echo "\n\nValidation Check:\n";
echo "------------------\n";

// Simulate validation
$validator = \Illuminate\Support\Facades\Validator::make($testData, [
    'pemesanan_id' => 'required|exists:pemesanan,id',
    'kursi' => 'required|array|min:1',
    'kursi.*' => 'required|string|distinct'
]);

if ($validator->passes()) {
    echo "✓ Validation PASSED\n";
} else {
    echo "✗ Validation FAILED:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "  - {$error}\n";
    }
}
?>
