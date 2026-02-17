<?php
/**
 * Test script to verify jadwal_flow_mode config update
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

echo "\n=== Testing Jadwal Flow Mode Config ===\n\n";

// Test 1: Read current setting
echo "1. Current setting from appSetting():\n";
$current = appSetting('jadwal_flow_mode', 'driver_confirmation');
echo "   Value: {$current}\n";

// Test 2: Database query
echo "\n2. Current setting from database:\n";
$dbSetting = AppSetting::where('key', 'jadwal_flow_mode')->first();
if ($dbSetting) {
    echo "   Found in DB: {$dbSetting->value}\n";
} else {
    echo "   Not found in DB (will use default)\n";
}

// Test 3: Test updateOrCreate
echo "\n3. Testing updateOrCreate (upsert):\n";
$newValue = 'direct_assign';
AppSetting::updateOrCreate(['key' => 'jadwal_flow_mode'], ['value' => $newValue]);
Cache::forget('app_setting:jadwal_flow_mode');
$updated = appSetting('jadwal_flow_mode', 'driver_confirmation');
echo "   Updated to: {$updated}\n";

// Test 4: Switch back
echo "\n4. Switching back to driver_confirmation:\n";
AppSetting::updateOrCreate(['key' => 'jadwal_flow_mode'], ['value' => 'driver_confirmation']);
Cache::forget('app_setting:jadwal_flow_mode');
$reverted = appSetting('jadwal_flow_mode', 'driver_confirmation');
echo "   Reverted to: {$reverted}\n";

// Test 5: Check route
echo "\n5. Checking routes:\n";
$routes = [
    'admin.rute_jadwal.index' => '/admin/rute-jadwal',
    'admin.jadwal.config.update' => '/admin/jadwal/config',
];
foreach ($routes as $name => $path) {
    try {
        $url = route($name);
        echo "   {$name}: {$url}\n";
    } catch (\Exception $e) {
        echo "   {$name}: ERROR - {$e->getMessage()}\n";
    }
}

echo "\n=== All tests completed ===\n";
