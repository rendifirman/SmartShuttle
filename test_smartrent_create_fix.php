<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Permission;

echo "=================================================================\n";
echo "SmartRent Create Page Access - Verification\n";
echo "=================================================================\n\n";

// Test 1: Route exists
echo "1️⃣  ROUTE VERIFICATION\n";
echo "---------------------\n";
try {
    $url = route('admin.smartrent.create');
    echo "✅ Route 'admin.smartrent.create' EXISTS\n";
    echo "   URL: {$url}\n";
} catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
    echo "❌ Route 'admin.smartrent.create' NOT FOUND\n";
}

// Test 2: Check which routes are in the smartrent group
echo "\n2️⃣  SMARTRENT ROUTES\n";
echo "-------------------\n";
$routes = app('router')->getRoutes()->getRoutes();
foreach ($routes as $route) {
    $name = $route->getName();
    if ($name && strpos($name, 'admin.smartrent') === 0) {
        $method = implode('|', $route->getMethods());
        echo "✓ {$name}: {$method}\n";
    }
}

// Test 3: Check user permissions
echo "\n3️⃣  PERMISSION CHECK\n";
echo "-------------------\n";
$adminUser = User::where('email', 'admin@smartshuttle.test')->first();

if ($adminUser) {
    echo "User: {$adminUser->email}\n";
    echo "  - Roles: " . $adminUser->getRoleNames()->join(', ') . "\n";
    echo "  - Has 'manage_smartrent' permission: " . ($adminUser->hasPermissionTo('manage_smartrent') ? "✅ YES" : "❌ NO") . "\n";
    echo "  - Has admin role: " . ($adminUser->hasAnyRole(['admin_pusat', 'admin_cabang', 'operator']) ? "✅ YES" : "❌ NO") . "\n";
} else {
    echo "Test user not found\n";
}

// Test 4: Check middleware
echo "\n4️⃣  MIDDLEWARE CHAIN\n";
echo "--------------------\n";
echo "Create route middleware:\n";
foreach ($routes as $route) {
    if ($route->getName() === 'admin.smartrent.create') {
        $middleware = $route->middleware();
        foreach ($middleware as $m) {
            echo "  ✓ {$m}\n";
        }
        break;
    }
}

echo "\n✅ Verification complete!\n";
?>
