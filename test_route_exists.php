<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test the route
try {
    $url = route('admin.smartrent.index');
    echo "✅ Route 'admin.smartrent.index' EXISTS\n";
    echo "URL: {$url}\n";
} catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
    echo "❌ Route 'admin.smartrent.index' NOT FOUND\n";
    echo "Error: {$e->getMessage()}\n";
}
?>
