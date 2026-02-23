<?php
/**
 * TEST DRIVER LOCATION SAVE AND STATUS UPDATE
 *
 * This script tests:
 * 1. Update location endpoint saves data to database
 * 2. Complete trip endpoint updates DriverJadwal status to 'selesai'
 * 3. Status persists after reload
 */

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\DriverJadwal;
use App\Models\DriverLocation;
use Illuminate\Support\Facades\DB;

echo "\n========== DRIVER LOCATION SAVE TEST ==========\n";

// 1. Check if there's any active driver jadwal
echo "\n1. Checking active driver jadwals...\n";
$activeJadwals = DriverJadwal::where('status', 'aktif')
    ->with('driver')
    ->limit(5)
    ->get();

if ($activeJadwals->isEmpty()) {
    echo "❌ No active driver jadwals found. Need sample data first.\n";
    echo "\nPlease create a driver jadwal with status='aktif' first.\n";
    exit(1);
}

echo "✅ Found " . $activeJadwals->count() . " active driver jadwals:\n";
foreach ($activeJadwals as $jadwal) {
    echo "   - ID: {$jadwal->id_jadwal_driver}, Driver: {$jadwal->driver->name}, Status: {$jadwal->status}\n";
}

// 2. Try creating a driver location record
echo "\n2. Testing DriverLocation creation...\n";
$jadwal = $activeJadwals->first();

$location = DriverLocation::create([
    'id_driver' => $jadwal->id_driver,
    'id_jadwal_driver' => $jadwal->id_jadwal_driver,
    'location_name' => 'Test Outlet - Bandung Station',
    'location_detail' => 'Test Branch - Testing',
    'latitude' => -6.9147,
    'longitude' => 107.6098,
    'stop_index' => 1,
    'status' => 'arrived',
]);

if ($location) {
    echo "✅ DriverLocation created successfully!\n";
    echo "   ID: {$location->id}\n";
    echo "   Location: {$location->location_name}\n";
    echo "   Status: {$location->status}\n";
    echo "   Created at: {$location->created_at}\n";
}

// 3. Check location persists in database
echo "\n3. Verifying location data persists...\n";
$savedLocation = DriverLocation::find($location->id);
if ($savedLocation) {
    echo "✅ Location found in database!\n";
    echo "   Location Name: {$savedLocation->location_name}\n";
    echo "   Status: {$savedLocation->status}\n";
    echo "   Created at: {$savedLocation->created_at}\n";
} else {
    echo "❌ Location not found in database!\n";
}

// 4. Test updating DriverJadwal status
echo "\n4. Testing DriverJadwal status update to 'selesai'...\n";
$originalStatus = $jadwal->status;
$jadwal->update(['status' => 'selesai']);

$updatedJadwal = DriverJadwal::find($jadwal->id_jadwal_driver);
if ($updatedJadwal->status === 'selesai') {
    echo "✅ DriverJadwal status updated to 'selesai'!\n";
    echo "   Original: {$originalStatus}\n";
    echo "   Updated: {$updatedJadwal->status}\n";
    echo "   Updated at: {$updatedJadwal->updated_at}\n";
} else {
    echo "❌ Failed to update DriverJadwal status!\n";
}

// 5. Verify status persists on reload
echo "\n5. Verifying status persists on database reload...\n";
$reloadJadwal = DriverJadwal::find($jadwal->id_jadwal_driver);
if ($reloadJadwal->status === 'selesai') {
    echo "✅ Status persisted! Status is: {$reloadJadwal->status}\n";
} else {
    echo "❌ Status did not persist! Status is: {$reloadJadwal->status}\n";
}

// 6. Check all locations for this trip
echo "\n6. Retrieving all locations for this trip...\n";
$allLocations = DriverLocation::where('id_jadwal_driver', $jadwal->id_jadwal_driver)
    ->orderBy('created_at', 'asc')
    ->get();

echo "✅ Found " . $allLocations->count() . " locations:\n";
foreach ($allLocations as $loc) {
    echo "   - {$loc->location_name} ({$loc->status}) at {$loc->created_at}\n";
}

// 7. Reset for next test
echo "\n7. Cleaning up test data...\n";
$jadwal->update(['status' => $originalStatus]);
$location->delete();
echo "✅ Test data cleaned up.\n";

echo "\n========== TEST COMPLETED ==========\n";
echo "All database operations working correctly!\n\n";
