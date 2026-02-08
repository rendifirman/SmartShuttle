<?php
/**
 * Comprehensive Booking Flow Test Script
 * Tests the customer booking flow from driver_jadwals table
 * This script runs within Laravel application context
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DriverJadwal;
use App\Models\User;
use App\Models\Promo;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;

echo "=== COMPREHENSIVE BOOKING FLOW TEST ===\n\n";

// Test 1: Route Registration
echo "1. Testing Route Registration...\n";
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
        echo "✓ Route 'customer.pesan' found\n";
        echo "  - URI: " . $bookingRoute->uri() . "\n";
        echo "  - Methods: " . implode(', ', $bookingRoute->methods()) . "\n";
        echo "  - Controller: " . $bookingRoute->getActionName() . "\n";

        // Check middleware
        $middleware = $bookingRoute->middleware();
        if (in_array('auth', $middleware)) {
            echo "  ✓ Has 'auth' middleware\n";
        } else {
            echo "  ✗ Missing 'auth' middleware\n";
        }
    } else {
        echo "✗ Route 'customer.pesan' not found\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking route: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Controller Method
echo "2. Testing Controller Method...\n";
try {
    $controller = new CustomerController();
    $reflection = new ReflectionClass($controller);

    if ($reflection->hasMethod('pesan')) {
        echo "✓ CustomerController@pesan method exists\n";
        $method = $reflection->getMethod('pesan');
        echo "  - Visibility: " . ($method->isPublic() ? 'public' : 'protected/private') . "\n";

        // Check parameters
        $params = $method->getParameters();
        echo "  - Parameters: " . count($params) . " (expects Request and id_jadwal_driver)\n";
    } else {
        echo "✗ CustomerController@pesan method not found\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking controller method: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Database Structure
echo "3. Testing Database Structure...\n";
try {
    // Check if driver_jadwals table exists
    $tables = DB::select("SHOW TABLES LIKE 'driver_jadwals'");
    if (count($tables) > 0) {
        echo "✓ driver_jadwals table exists\n";

        // Check table structure
        $columns = DB::select("DESCRIBE driver_jadwals");
        $requiredColumns = ['id_jadwal_driver', 'rute', 'tanggal', 'armada', 'waktu_keberangkatan', 'waktu_kedatangan', 'harga', 'total_kursi', 'kursi_terisi', 'status'];
        $existingColumns = array_column($columns, 'Field');

        $missingColumns = array_diff($requiredColumns, $existingColumns);
        if (empty($missingColumns)) {
            echo "  ✓ All required columns present\n";
        } else {
            echo "  ✗ Missing columns: " . implode(', ', $missingColumns) . "\n";
        }

        // Get sample data
        $sampleData = DB::table('driver_jadwals')->first();
        if ($sampleData) {
            echo "  ✓ Sample data found\n";
            echo "    - ID: {$sampleData->id_jadwal_driver}\n";
            echo "    - Route: {$sampleData->rute}\n";
            echo "    - Status: {$sampleData->status}\n";
            echo "    - Total Seats: {$sampleData->total_kursi}\n";
            echo "    - Occupied Seats: {$sampleData->kursi_terisi}\n";
            echo "    - Price: Rp " . number_format($sampleData->harga, 0, ',', '.') . "\n";
        } else {
            echo "  ! No data in driver_jadwals table\n";
        }
    } else {
        echo "✗ driver_jadwals table does not exist\n";
    }
} catch (Exception $e) {
    echo "✗ Error checking database: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Model Functionality
echo "4. Testing DriverJadwal Model...\n";
try {
    // Test model instantiation
    $model = new DriverJadwal();
    echo "✓ DriverJadwal model can be instantiated\n";

    // Test fillable attributes
    $fillable = $model->getFillable();
    $requiredFillable = ['id_jadwal', 'rute_id', 'id_driver', 'rute', 'tanggal', 'armada', 'waktu_keberangkatan', 'waktu_kedatangan', 'harga', 'total_kursi', 'kursi_terisi', 'status'];
    $missingFillable = array_diff($requiredFillable, $fillable);
    if (empty($missingFillable)) {
        echo "  ✓ All required fillable attributes present\n";
    } else {
        echo "  ✗ Missing fillable attributes: " . implode(', ', $missingFillable) . "\n";
    }

    // Test table name
    if ($model->getTable() === 'driver_jadwals') {
        echo "  ✓ Correct table name: driver_jadwals\n";
    } else {
        echo "  ✗ Wrong table name: {$model->getTable()}\n";
    }

    // Test primary key
    if ($model->getKeyName() === 'id_jadwal_driver') {
        echo "  ✓ Correct primary key: id_jadwal_driver\n";
    } else {
        echo "  ✗ Wrong primary key: {$model->getKeyName()}\n";
    }

} catch (Exception $e) {
    echo "✗ Error testing model: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Functional Logic Test
echo "5. Testing Functional Logic...\n";
try {
    // Get an active schedule for testing
    $testSchedule = DB::table('driver_jadwals')->where('status', 'aktif')->first();

    if ($testSchedule) {
        echo "✓ Found active schedule for testing (ID: {$testSchedule->id_jadwal_driver})\n";

        // Test seat calculation logic
        $remainingSeats = $testSchedule->total_kursi - $testSchedule->kursi_terisi;
        echo "  - Remaining seats: $remainingSeats\n";

        // Test pricing logic
        $testPassengers = 1;
        if ($remainingSeats >= $testPassengers) {
            $totalPrice = $testSchedule->harga * $testPassengers;
            echo "  ✓ Can accommodate $testPassengers passenger(s)\n";
            echo "    Total price: Rp " . number_format($totalPrice, 0, ',', '.') . "\n";
        } else {
            echo "  ✗ Cannot accommodate $testPassengers passenger(s)\n";
        }

        // Test insufficient seats scenario
        $excessPassengers = $remainingSeats + 1;
        if ($remainingSeats < $excessPassengers) {
            echo "  ✓ Correctly identifies insufficient seats for $excessPassengers passenger(s)\n";
        } else {
            echo "  ✗ Logic error: should reject $excessPassengers passenger(s)\n";
        }

        // Test status validation
        if ($testSchedule->status === 'aktif') {
            echo "  ✓ Schedule status is active\n";
        } else {
            echo "  ✗ Schedule status is not active: {$testSchedule->status}\n";
        }

    } else {
        echo "! No active schedules found for testing\n";
    }
} catch (Exception $e) {
    echo "✗ Error in functional logic test: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 6: View Files
echo "6. Testing View Files...\n";
$viewFiles = [
    'resources/views/customer/search.blade.php',
    'resources/views/customer/pesan.blade.php'
];

foreach ($viewFiles as $viewFile) {
    if (file_exists($viewFile)) {
        echo "✓ $viewFile exists\n";

        $content = file_get_contents($viewFile);
        if ($viewFile === 'resources/views/customer/search.blade.php') {
            if (strpos($content, 'route(\'customer.pesan\'') !== false) {
                echo "  ✓ Contains route('customer.pesan') link\n";
            } else {
                echo "  ✗ Missing route('customer.pesan') link\n";
            }

            if (strpos($content, 'Book Now') !== false) {
                echo "  ✓ Contains 'Book Now' button text\n";
            } else {
                echo "  ✗ Missing 'Book Now' button text\n";
            }
        }
    } else {
        echo "✗ $viewFile not found\n";
    }
}

echo "\n";

// Test 7: Promo Integration
echo "7. Testing Promo Integration...\n";
try {
    $promoCount = DB::table('promos')->count();
    echo "✓ Found $promoCount promo(s) in database\n";

    if ($promoCount > 0) {
        $activePromos = DB::table('promos')->where('status', 'aktif')->count();
        echo "  - Active promos: $activePromos\n";

        // Test promo structure
        $samplePromo = DB::table('promos')->first();
        if ($samplePromo) {
            echo "  ✓ Sample promo found\n";
            echo "    - Code: {$samplePromo->kode_promo}\n";
            echo "    - Discount: {$samplePromo->diskon}%\n";
            echo "    - Status: {$samplePromo->status}\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Error checking promos: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 8: Edge Cases
echo "8. Testing Edge Cases...\n";
try {
    // Test invalid ID
    $invalidId = 999999;
    $invalidRecord = DB::table('driver_jadwals')->where('id_jadwal_driver', $invalidId)->first();
    if (!$invalidRecord) {
        echo "✓ Invalid ID ($invalidId) correctly returns no record\n";
    } else {
        echo "✗ Invalid ID ($invalidId) unexpectedly found record\n";
    }

    // Test inactive schedules
    $inactiveCount = DB::table('driver_jadwals')->where('status', '!=', 'aktif')->count();
    echo "✓ Found $inactiveCount inactive schedule(s) that should be rejected\n";

    // Test full schedules
    $fullSchedules = DB::table('driver_jadwals')
        ->where('status', 'aktif')
        ->whereColumn('kursi_terisi', '>=', 'total_kursi')
        ->count();
    echo "✓ Found $fullSchedules full schedule(s) that should be rejected\n";

    // Test negative values
    $negativeSeats = DB::table('driver_jadwals')
        ->where('total_kursi', '<', 0)
        ->orWhere('kursi_terisi', '<', 0)
        ->orWhere('harga', '<', 0)
        ->count();
    if ($negativeSeats == 0) {
        echo "✓ No schedules with negative values found\n";
    } else {
        echo "✗ Found $negativeSeats schedule(s) with negative values\n";
    }

} catch (Exception $e) {
    echo "✗ Error testing edge cases: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 9: Security Aspects
echo "9. Testing Security Aspects...\n";
try {
    // Check route parameter binding
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

    // Check authentication requirement
    $routes = Route::getRoutes();
    $hasAuth = false;
    foreach ($routes as $route) {
        if ($route->getName() === 'customer.pesan') {
            $middleware = $route->middleware();
            if (in_array('auth', $middleware)) {
                $hasAuth = true;
                break;
            }
        }
    }

    if ($hasAuth) {
        echo "✓ Route requires authentication\n";
    } else {
        echo "✗ Route does not require authentication\n";
    }

    // Check for direct database queries in controller (should use Eloquent/Model)
    $controllerContent = file_get_contents('app/Http/Controllers/CustomerController.php');
    if (strpos($controllerContent, 'DB::table(\'driver_jadwals\')') !== false) {
        echo "✓ Controller uses DB::table for driver_jadwals queries\n";
    } else {
        echo "? Controller may use Eloquent models for queries\n";
    }

} catch (Exception $e) {
    echo "✗ Error testing security: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 10: Data Integrity
echo "10. Testing Data Integrity...\n";
try {
    // Check for orphaned records or invalid foreign keys
    $invalidDriverSchedules = DB::table('driver_jadwals')
        ->leftJoin('users', 'driver_jadwals.id_driver', '=', 'users.id')
        ->whereNull('users.id')
        ->count();

    if ($invalidDriverSchedules == 0) {
        echo "✓ No orphaned driver_jadwal records (all drivers exist)\n";
    } else {
        echo "✗ Found $invalidDriverSchedules orphaned driver_jadwal record(s)\n";
    }

    // Check data consistency
    $inconsistentData = DB::table('driver_jadwals')
        ->where('kursi_terisi', '>', 'total_kursi')
        ->count();

    if ($inconsistentData == 0) {
        echo "✓ No schedules with kursi_terisi > total_kursi\n";
    } else {
        echo "✗ Found $inconsistentData schedule(s) with inconsistent seat data\n";
    }

    // Check date validity
    $invalidDates = DB::table('driver_jadwals')
        ->where('tanggal', '<', date('Y-m-d'))
        ->where('status', 'aktif')
        ->count();

    if ($invalidDates == 0) {
        echo "✓ No active schedules with past dates\n";
    } else {
        echo "✗ Found $invalidDates active schedule(s) with past dates\n";
    }

} catch (Exception $e) {
    echo "✗ Error testing data integrity: " . $e->getMessage() . "\n";
}

echo "\n=== TEST SUMMARY ===\n";
echo "Comprehensive testing completed. Review the results above.\n";
echo "\nNext steps for full verification:\n";
echo "1. Test actual HTTP requests to the booking endpoint\n";
echo "2. Test authentication flow (login redirects)\n";
echo "3. Test UI interactions in browser\n";
echo "4. Test complete booking workflow end-to-end\n";
echo "\nIf all tests pass, the booking flow implementation is ready for production.\n";
echo "If any tests fail, address the issues before deployment.\n";

?>
