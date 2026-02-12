<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Rute;

echo "Testing Rute Duration and Stops Display\n";
echo "=======================================\n\n";

$rute = Rute::first();

if (!$rute) {
    echo "No routes found in database.\n";
    exit;
}

echo "Route ID: " . $rute->id . "\n";
echo "Route Name: " . $rute->nama_rute . "\n";
echo "Original Duration: " . $rute->durasi . "\n";
echo "Formatted Duration: " . $rute->formatted_durasi . "\n\n";

echo "Route Stops (rute_pemberhentian):\n";
$pemberhentian = $rute->rute_pemberhentian;
if ($pemberhentian && is_array($pemberhentian)) {
    foreach ($pemberhentian as $index => $stop) {
        echo "Stop " . ($index + 1) . ":\n";
        echo "  Kota: " . ($stop['kota'] ?? 'N/A') . "\n";
        echo "  Cabang: " . ($stop['cabang'] ?? 'N/A') . "\n";
        echo "  Outlets: " . (isset($stop['outlets']) && is_array($stop['outlets']) ? implode(', ', $stop['outlets']) : 'N/A') . "\n";
        echo "  Durasi Singgah: " . ($stop['durasi_singgah'] ?? 0) . " menit\n";
        echo "  Jenis: " . ($stop['jenis'] ?? 'N/A') . "\n\n";
    }
} else {
    echo "No stops data found or data is not an array.\n";
}

echo "Testing duration parsing:\n";
$testDurations = ['2 jam 30 menit', '1 jam', '45 menit', '3 jam 15 menit'];
foreach ($testDurations as $duration) {
    $minutes = $rute->parseDurationToMinutes($duration);
    $formatted = $rute->formatMinutesToDuration($minutes);
    echo "  '$duration' -> $minutes minutes -> '$formatted'\n";
}

echo "\nTest completed.\n";
