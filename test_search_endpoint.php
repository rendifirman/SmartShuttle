#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

// Bootstrap the app
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\CustomerController;
use Illuminate\View\Factory;

echo "=== TESTING SEARCH ENDPOINT ===\n\n";

// Create a request
$request = Request::create('/customer/search?asal=Jakarta&tujuan=Bekasi&tanggal=2026-02-08&penumpang=1', 'GET');

// Create controller instance
$controller = new CustomerController();

// Set request on controller
app()['request'] = $request;

// Call showSearch method
echo "Calling showSearch()...\n";
try {
    $response = $controller->showSearch($request);
    
    // Get the view data if it's a view
    if ($response instanceof \Illuminate\View\View) {
        echo "✓ Response is a View\n";
        
        $data = $response->getData();
        
        echo "\nView Data Keys:\n";
        foreach (array_keys($data) as $key) {
            if ($key === 'validated') {
                echo "  - $key: " . json_encode($data[$key]) . "\n";
            } elseif ($key === 'jadwals') {
                echo "  - $key: [Paginator with " . $data[$key]->count() . " items]\n";
            } elseif (is_object($data[$key])) {
                echo "  - $key: [Object]\n";
            } else {
                echo "  - $key\n";
            }
        }
        
        echo "\nCritical Variables:\n";
        echo "  isset(\$validated): " . (isset($data['validated']) ? 'TRUE' : 'FALSE') . "\n";
        echo "  isset(\$jadwals): " . (isset($data['jadwals']) ? 'TRUE' : 'FALSE') . "\n";
        
        if (isset($data['jadwals'])) {
            echo "  \$jadwals->count(): " . $data['jadwals']->count() . "\n";
            echo "  \$jadwals->isEmpty(): " . ($data['jadwals']->isEmpty() ? 'TRUE' : 'FALSE') . "\n";
        }
        
        if (isset($data['validated'])) {
            echo "  \$validated['asal']: " . $data['validated']['asal'] . "\n";
            echo "  \$validated['tujuan']: " . $data['validated']['tujuan'] . "\n";
        }
    } else {
        echo "✗ Response is not a View: " . get_class($response) . "\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
