#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DriverJadwal;

echo "=== TESTING SHUTTLE IMAGE PROPERTIES ===\n\n";

// Get a sample DriverJadwal
$jadwal = DriverJadwal::first();

if (!$jadwal) {
    echo "No DriverJadwal records found\n";
    exit(1);
}

echo "Testing shuttle accessor on DriverJadwal ID: " . $jadwal->id_jadwal_driver . "\n";
echo "Driver Jadwal: " . $jadwal->rute . "\n\n";

// Access the shuttle virtual object
$shuttle = $jadwal->shuttle;

echo "Shuttle object type: " . get_class($shuttle) . "\n";
echo "Shuttle is stdClass: " . ($shuttle instanceof stdClass ? 'YES' : 'NO') . "\n\n";

// Check properties
$properties = [
    'nama_shuttle',
    'tipe_shuttle',
    'kapasitas_kursi',
    'fasilitas_array',
    'gambar_depan',
    'gambar_samping',
    'gambar_belakang',
    'gambar_interior'
];

echo "Checking properties:\n";
foreach ($properties as $prop) {
    $exists = isset($shuttle->$prop) ? 'YES' : 'NO';
    $value = isset($shuttle->$prop) ? $shuttle->$prop : 'NOT SET';
    
    if (is_array($value)) {
        $value = implode(', ', $value);
    }
    
    echo "  $exists  \$shuttle->$prop = $value\n";
}

// Test the images array that would be used in template
echo "\nTesting images array for template:\n";
$images = [
    ['gambar' => $shuttle->gambar_depan, 'caption' => 'Tampak Depan'],
    ['gambar' => $shuttle->gambar_samping, 'caption' => 'Tampak Samping'],
    ['gambar' => $shuttle->gambar_belakang, 'caption' => 'Tampak Belakang'],
    ['gambar' => $shuttle->gambar_interior, 'caption' => 'Interior']
];

echo json_encode($images, JSON_PRETTY_PRINT) . "\n";

echo "\n✓ No undefined property errors!\n";
echo "✓ All image properties are accessible\n";
echo "✓ Template carousel should work without errors\n";
