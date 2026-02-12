<?php
/**
 * Script untuk menghapus semua data jadwal
 * Run: php delete_jadwal.php
 */

// Set up Laravel environment
$basePath = __DIR__;
require_once $basePath . '/vendor/autoload.php';
$app = require_once $basePath . '/bootstrap/app.php';

// Create the kernel instance
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DriverJadwal;
use App\Models\Jadwal;
use App\Models\RuteJadwal;

echo "=== Menghapus Data Jadwal ===\n\n";

try {
    // Hitung data sebelum dihapus
    $driverJadwalCount = DriverJadwal::count();
    $jadwalCount = Jadwal::count();
    $ruteJadwalCount = RuteJadwal::count();
    
    echo "Data sebelum penghapusan:\n";
    echo "- Driver Jadwal: {$driverJadwalCount} records\n";
    echo "- Jadwal: {$jadwalCount} records\n";
    echo "- Rute Jadwal: {$ruteJadwalCount} records\n\n";
    
    // Konfirmasi
    echo "PERHATIAN: Anda akan menghapus semua jadwal yang tersimpan!\n";
    echo "Ketik 'HAPUS' untuk melanjutkan atau tekan Enter untuk membatalkan: ";
    
    $handle = fopen("php://stdin", "r");
    $input = trim(fgets($handle));
    fclose($handle);
    
    if ($input !== 'HAPUS') {
        echo "Penghapusan dibatalkan.\n";
        exit(0);
    }
    
    // Hapus data
    echo "\nMenghapus data...\n";
    
    // Hapus rute_jadwals dulu (foreign key)
    RuteJadwal::truncate();
    echo "✓ Rute Jadwal dihapus\n";
    
    // Hapus driver_jadwals
    DriverJadwal::truncate();
    echo "✓ Driver Jadwal dihapus\n";
    
    // Hapus jadwals
    Jadwal::truncate();
    echo "✓ Jadwal dihapus\n";
    
    echo "\n✓ Semua data jadwal berhasil dihapus!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

exit(0);
