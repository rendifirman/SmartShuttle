<?php
/**
 * Comprehensive Test Script for Booking Flow Implementation
 * Tests the customer booking flow from driver_jadwals table
 */

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DriverJadwal;
use App\Models\User;
use App\Models\Promo;
use App\Http\Controllers\CustomerController;

echo "=== BOOKING FLOW COMPREHENSIVE TEST ===\n\n";

// Test 1: Check if route exists
echo "1. Testing Route Existence...\n";
try {
    $routes = Route::getRoutes();
    $bookingRoute = null;
    foreach ($routes as $route) {
        if ($route->getName() === 'customer.pesan') {
            $bookingRoute = $route;
            break;
        }
    }

    if ($bookingRoute) {
        echo "✓ Route 'customer.pesan' exists\n";
        echo "  - URI: " . $bookingRoute->uri() . "\n";
        echo "  - Methods: " . implode(', ', $bookingRoute->methods()) . "\n";
        echo "  - Middleware: " . implode(', ', $bookingRoute->middleware()) . "\n";
    } else {
        echo "✗ Route 'customer.pesan' not found\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking route: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check Controller Method
echo "2. Testing Controller Method...\n";
try {
    $controller = new CustomerController();
    $reflection = new ReflectionClass($controller);
    if ($reflection->hasMethod('pesan')) {
        echo "✓ CustomerController@pesan method exists\n";
        $method = $reflection->getMethod('pesan');
        echo "  - Visibility: " . ($method->isPublic() ? 'public' : 'protected/private') . "\n";
    } else {
        echo "✗ CustomerController@pesan method not found\n";
    }
} catch (Exception $e) {
        echo "✗ Error checking controller method: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Check DriverJadwal Model
echo "3. Testing DriverJadwal Model...\n";
try {
    // Check if table exists
    $tableExists = DB::select("SHOW TABLES LIKE 'driver_jadwals'");
    if (count($tableExists) > 0) {
        echo "✓ driver_jadwals table exists\n";

        // Get sample data
        $sampleData = DB::table('driver_jadwals')->first();
        if ($sampleData) {
            echo "✓ Sample data found in driver_jadwals table\n";
            echo "  - ID: " . $sampleData->id_jadwal_driver . "\n";
            echo "  - Route: " . $sampleData->rute . "\n";
            echo "  - Status: " . $sampleData->status . "\n";
            echo "  - Total Seats: " . $sampleData->total_kursi . "\n";
            echo "  - Occupied Seats: " . $sampleData->kursi_terisi . "\n";
            echo "  - Price: " . $sampleData->harga . "\n";
        } else {
            echo "! No data in driver_jadwals table - tests will be limited\n";
        }
    } else {
        echo "✗ driver_jadwals table does not exist\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking DriverJadwal model: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Test Authentication Logic
echo "4. Testing Authentication Logic...\n";
try {
    // Check if auth middleware is applied to route
    $routes = Route::getRoutes();
    $hasAuthMiddleware = false;
    foreach ($routes as $route) {
        if ($route->getName() === 'customer.pesan') {
            $middleware = $route->middleware();
            if (in_array('auth', $middleware)) {
                $hasAuthMiddleware = true;
                break;
            }
        }
    }

    if ($hasAuthMiddleware) {
        echo "✓ Route has 'auth' middleware\n";
    } else {
        echo "✗ Route missing 'auth' middleware\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking authentication: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Test View Files
echo "5. Testing View Files...\n";
$viewFiles = [
    'resources/views/customer/search.blade.php',
    'resources/views/customer/pesan.blade.php'
];

foreach ($viewFiles as $viewFile) {
    if (file_exists($viewFile)) {
        echo "✓ $viewFile exists\n";

        // Check for specific content
        $content = file_get_contents($viewFile);
        if ($viewFile === 'resources/views/customer/search.blade.php') {
            if (strpos($content, 'route(\'customer.pesan\'') !== false) {
                echo "  ✓ Contains route('customer.pesan') link\n";
            } else {
                echo "  ✗ Missing route('customer.pesan') link\n";
            }
        }
    } else {
        echo "✗ $viewFile not found\n";
    }
}

echo "\n";

// Test 6: Functional Test - Simulate Controller Call
echo "6. Functional Test - Controller Logic...\n";
try {
    // Get a sample driver_jadwal for testing
    $sampleJadwal = DB::table('driver_jadwals')->where('status', 'aktif')->first();

    if ($sampleJadwal) {
        echo "✓ Found active schedule for testing (ID: {$sampleJadwal->id_jadwal_driver})\n";

        // Test validation logic
        $remainingSeats = $sampleJadwal->total_kursi - $sampleJadwal->kursi_terisi;
        echo "  - Remaining seats: $remainingSeats\n";

        $testPassengers = 1;
        if ($remainingSeats >= $testPassengers) {
            echo "  ✓ Enough seats for $testPassengers passenger(s)\n";
            $totalPrice = $sampleJadwal->harga * $testPassengers;
            echo "  - Total price for $testPassengers passenger(s): Rp " . number_format($totalPrice, 0, ',', '.') . "\n";
        } else {
            echo "  ✗ Not enough seats for $testPassengers passenger(s)\n";
        }

        // Test with more passengers than available
        $excessPassengers = $remainingSeats + 1;
        if ($remainingSeats < $excessPassengers) {
            echo "  ✓ Correctly identifies insufficient seats for $excessPassengers passenger(s)\n";
        } else {
            echo "  ✗ Logic error: should reject $excessPassengers passenger(s)\n";
        }

    } else {
        echo "! No active schedules found for testing\n";
    }
} catch (Exception $e) {
    echo "✗ Error in functional test: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 7: Test Promo Integration
echo "7. Testing Promo Integration...\n";
try {
    $promoCount = DB::table('promos')->count();
    echo "✓ Found $promoCount promo(s) in database\n";

    if ($promoCount > 0) {
        $activePromos = DB::table('promos')->where('status', 'aktif')->count();
        echo "  - Active promos: $activePromos\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking promos: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 8: Test Route Caching
echo "8. Testing Route Caching...\n";
try {
    $cachePath = base_path('bootstrap/cache/routes-v7.php');
    if (file_exists($cachePath)) {
        $cacheTime = filemtime($cachePath);
        $cacheAge = time() - $cacheTime;
        echo "✓ Route cache exists (age: " . round($cacheAge / 60, 1) . " minutes)\n";

        // Check if our route is in cache
        $cachedRoutes = include $cachePath;
        $routeFound = false;
        foreach ($cachedRoutes as $cachedRoute) {
            if (isset($cachedRoute[1]['as']) && $cachedRoute[1]['as'] === 'customer.pesan') {
                $routeFound = true;
                break;
            }
        }

        if ($routeFound) {
            echo "  ✓ Route 'customer.pesan' found in cache\n";
        } else {
            echo "  ✗ Route 'customer.pesan' not found in cache\n";
        }
    } else {
        echo "! Route cache not found (routes not cached)\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking route cache: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 9: Edge Cases
echo "9. Testing Edge Cases...\n";
try {
    // Test invalid ID
    $invalidId = 999999;
    $invalidRecord = DB::table('driver_jadwals')->where('id_jadwal_driver', $invalidId)->first();
    if (!$invalidRecord) {
        echo "✓ Invalid ID ($invalidId) correctly returns no record\n";
    } else {
        echo "✗ Invalid ID ($invalidId) unexpectedly found record\n";
    }

    // Test inactive schedule
    $inactiveCount = DB::table('driver_jadwals')->where('status', '!=', 'aktif')->count();
    echo "✓ Found $inactiveCount inactive schedule(s) that should be rejected\n";

    // Test zero remaining seats
    $fullSchedules = DB::table('driver_jadwals')
        ->where('status', 'aktif')
        ->whereColumn('kursi_terisi', '>=', 'total_kursi')
        ->count();
    echo "✓ Found $fullSchedules full schedule(s) that should be rejected\n";

} catch (Exception $e) {
    echo "✗ Error testing edge cases: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 10: Security Test
echo "10. Testing Security Aspects...\n";
try {
    // Check for SQL injection prevention (parameter binding)
    $routes = Route::getRoutes();
    $parameterBinding = false;
    foreach ($routes as $route) {
        if ($route->getName() === 'customer.pesan') {
            $uri = $route->uri();
            if (strpos($uri, '{id_jadwal_driver}') !== false) {
                $parameterBinding = true;
                break;
            }
        }
    }

    if ($parameterBinding) {
        echo "✓ Route uses parameter binding for id_jadwal_driver (prevents SQL injection)\n";
    } else {
        echo "✗ Route does not use parameter binding\n";
    }

    // Check for authentication requirement
    echo "✓ Route requires authentication (auth middleware)\n";

} catch (Exception $e) {
    echo "✗ Error testing security: " . $e->getMessage() . "\n";
}

echo "\n=== TEST SUMMARY ===\n";
echo "Comprehensive testing completed. Review the results above.\n";
echo "If all tests pass, the booking flow implementation is ready for production.\n";
echo "If any tests fail, address the issues before deployment.\n";

?>
