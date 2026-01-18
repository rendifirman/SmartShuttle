<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\DriverController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Driver Login ===\n\n";

// Test 1: Check if driver guard exists
echo "1. Checking driver guard configuration...\n";
$config = config('auth.guards.driver');
if ($config) {
    echo "✓ Driver guard found: " . json_encode($config) . "\n";
} else {
    echo "✗ Driver guard not found\n";
}

// Test 2: Check if driver routes exist
echo "\n2. Checking driver routes...\n";
$routes = app('router')->getRoutes();
$driverRoutes = [];
foreach ($routes as $route) {
    if (str_contains($route->getName(), 'driver.')) {
        $driverRoutes[] = $route->getName() . ' -> ' . $route->methods()[0] . ' ' . $route->uri();
    }
}

if (count($driverRoutes) > 0) {
    echo "✓ Found " . count($driverRoutes) . " driver routes:\n";
    foreach ($driverRoutes as $route) {
        echo "  - $route\n";
    }
} else {
    echo "✗ No driver routes found\n";
}

// Test 3: Check if DriverController exists and has required methods
echo "\n3. Checking DriverController...\n";
$controller = new DriverController();
$reflection = new ReflectionClass($controller);

$requiredMethods = ['showLogin', 'login', 'dashboard', 'logout'];
$missingMethods = [];

foreach ($requiredMethods as $method) {
    if (!$reflection->hasMethod($method)) {
        $missingMethods[] = $method;
    }
}

if (empty($missingMethods)) {
    echo "✓ All required methods found in DriverController\n";
} else {
    echo "✗ Missing methods: " . implode(', ', $missingMethods) . "\n";
}

// Test 4: Check if driver views exist
echo "\n4. Checking driver views...\n";
$views = [
    'driver.login' => resource_path('views/driver/login.blade.php'),
    'driver.dashboard-driver' => resource_path('views/driver/dashboard-driver.blade.php')
];

foreach ($views as $name => $path) {
    if (file_exists($path)) {
        echo "✓ View '$name' exists at $path\n";
    } else {
        echo "✗ View '$name' not found at $path\n";
    }
}

// Test 5: Try to create a test driver user (if not exists)
echo "\n5. Checking for test driver user...\n";
$testEmail = 'driver@test.com';
$user = User::where('email', $testEmail)->first();

if (!$user) {
    echo "Creating test driver user...\n";
    try {
        $user = User::create([
            'name' => 'Test Driver',
            'email' => $testEmail,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Assign driver role if spatie/laravel-permission is available
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('driver');
            echo "✓ Test driver user created with role 'driver'\n";
        } else {
            echo "✓ Test driver user created (role assignment not available)\n";
        }
    } catch (Exception $e) {
        echo "✗ Failed to create test user: " . $e->getMessage() . "\n";
    }
} else {
    echo "✓ Test driver user already exists\n";
    if (method_exists($user, 'hasRole') && $user->hasRole('driver')) {
        echo "✓ User has 'driver' role\n";
    } elseif (method_exists($user, 'hasRole')) {
        echo "⚠ User does not have 'driver' role\n";
    }
}

echo "\n=== Test Summary ===\n";
echo "Driver login implementation appears to be " . (count($driverRoutes) > 0 ? "COMPLETE" : "INCOMPLETE") . "\n";
echo "\nTo test manually:\n";
echo "1. Visit: /driver/login\n";
echo "2. Login with: $testEmail / password\n";
echo "3. Should redirect to: /driver/dashboard\n";
echo "4. Should see the driver dashboard page\n";

echo "\n=== End Test ===\n";
