<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $getRoute = route('admin.system_settings.schedule_flow');
    echo "✓ GET route: {$getRoute}\n";
} catch (\Exception $e) {
    echo "✗ GET route error: " . $e->getMessage() . "\n";
}

try {
    $postRoute = route('admin.system_settings.schedule_flow.update');
    echo "✓ POST route: {$postRoute}\n";
} catch (\Exception $e) {
    echo "✗ POST route error: " . $e->getMessage() . "\n";
}

echo "\nButton in jadwal-index.blade.php will direct to:\n";
echo "  GET route: admin.system_settings.schedule_flow\n";
echo "\nForm in system_settings/index.blade.php will POST to:\n";
echo "  POST route: admin.system_settings.schedule_flow.update\n";
