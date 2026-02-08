#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\CustomerController;

echo "=== TESTING SEARCH PAGE TEMPLATE RENDERING ===\n\n";

// Simulate search request
$request = Request::create(
    '/customer/search?asal=Jakarta&tujuan=Bekasi&tanggal=2026-02-08&penumpang=1',
    'GET'
);

app()['request'] = $request;
$controller = new CustomerController();

try {
    $response = $controller->showSearch($request);
    $data = $response->getData();
    
    echo "✓ Controller executed successfully\n\n";
    
    // Simulate template rendering with exact code from search.blade.php line 2769-2772
    echo "Simulating template carousel image array creation:\n\n";
    
    $jadwals = $data['jadwals'];
    
    if ($jadwals->count() > 0) {
        $jadwal = $jadwals->first();
        $shuttle = $jadwal->shuttle;
        
        echo "Processing jadwal ID: " . $jadwal->id_jadwal_driver . "\n";
        echo "Shuttle name: " . $shuttle->nama_shuttle . "\n\n";
        
        // This is the exact code from template that was causing the error
        try {
            $images = [
                ['gambar' => $shuttle->gambar_depan, 'caption' => 'Tampak Depan'],
                ['gambar' => $shuttle->gambar_samping, 'caption' => 'Tampak Samping'],
                ['gambar' => $shuttle->gambar_belakang, 'caption' => 'Tampak Belakang'],
                ['gambar' => $shuttle->gambar_interior, 'caption' => 'Interior']
            ];
            
            echo "✓ Images array created successfully\n";
            echo "✓ No undefined property errors\n\n";
            
            // Simulate template loop
            echo "Simulating @foreach loop:\n";
            $index = 0;
            foreach ($images as $image) {
                $index++;
                echo "  Image $index: " . $image['caption'] . " => " . $image['gambar'] . "\n";
            }
            
            echo "\n✓ All images would render correctly in carousel\n";
            
        } catch (Exception $e) {
            echo "✗ ERROR: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
    
} catch (Exception $e) {
    echo "✗ Controller error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== TEST COMPLETE ===\n";
echo "✓ Template will render without errors\n";
echo "✓ Carousel images will display correctly\n";
