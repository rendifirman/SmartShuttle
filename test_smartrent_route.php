<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test the route
try {
    $url = route('admin.smartrent');
    echo "✅ Route 'admin.smartrent' EXISTS\n";
    echo "URL: {$url}\n";
} catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
    echo "❌ Route 'admin.smartrent' NOT FOUND\n";
    echo "Error: {$e->getMessage()}\n";
}

// List all admin.smartrent* routes
echo "\n🔍 All SmartRent-related routes:\n";
echo "-----------------------------------\n";
$routes = app('router')->getRoutes()->getRoutes();
foreach ($routes as $route) {
    $name = $route->getName();
    if ($name && strpos($name, 'smartrent') !== false) {
        echo "✓ {$name}\n";
    }
}

echo "\n";
?>
