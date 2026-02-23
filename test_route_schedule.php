<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ROUTE AND SCHEDULE MANAGEMENT TESTING ===\n\n";

try {
    // Test 1: Model Loading
    echo "1. Testing Model Loading...\n";
    $ruteCount = \App\Models\Rute::count();
    $jadwalCount = \App\Models\Jadwal::count();
    $ruteJadwalCount = \App\Models\RuteJadwal::count();

    echo "   ✓ Rute model: {$ruteCount} records\n";
    echo "   ✓ Jadwal model: {$jadwalCount} records\n";
    echo "   ✓ RuteJadwal model: {$ruteJadwalCount} records\n\n";

    // Test 2: Relationships
    echo "2. Testing Relationships...\n";

    $jadwal = \App\Models\Jadwal::with('rutes')->first();
    if ($jadwal) {
        echo "   ✓ Jadwal-Rute relationship: OK (" . $jadwal->rutes->count() . " rutes)\n";
    } else {
        echo "   ⚠ No jadwal data found for relationship testing\n";
    }

    $rute = \App\Models\Rute::with('segments')->first();
    if ($rute) {
        echo "   ✓ Rute-Segments relationship: OK\n";
    } else {
        echo "   ⚠ No rute data found for relationship testing\n";
    }

    // Test 3: Distance Calculation
    echo "\n3. Testing Distance Calculation Logic...\n";

    $rute = \App\Models\Rute::first();
    if ($rute) {
        // Test with sample data
        $jarak = $rute->hitungJarakOutlet('Test Outlet A', 'Test Outlet B');
        echo "   ✓ Distance calculation method works (result: {$jarak})\n";
    } else {
        echo "   ⚠ No rute data for distance calculation testing\n";
    }

    // Test 4: Controller Methods
    echo "\n4. Testing Controller Methods...\n";

    // Check if JadwalController exists and has methods
    $controllerClass = 'App\\Http\\Controllers\\Admin\\JadwalController';
    if (class_exists($controllerClass)) {
        $reflection = new ReflectionClass($controllerClass);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        $methodNames = array_map(function($method) {
            return $method->name;
        }, $methods);

        $expectedMethods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
        $missingMethods = array_diff($expectedMethods, $methodNames);

        if (empty($missingMethods)) {
            echo "   ✓ JadwalController: All CRUD methods implemented\n";
        } else {
            echo "   ⚠ JadwalController: Missing methods: " . implode(', ', $missingMethods) . "\n";
        }
    } else {
        echo "   ✗ JadwalController class not found\n";
    }

    // Test 5: Route Registration
    echo "\n5. Testing Route Registration...\n";

    $routes = app('router')->getRoutes();
    $jadwalRoutes = [];

    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'jadwal') !== false) {
            $jadwalRoutes[] = $uri . ' (' . implode('|', $route->methods()) . ')';
        }
    }

    if (!empty($jadwalRoutes)) {
        echo "   ✓ Jadwal routes registered: " . count($jadwalRoutes) . " routes\n";
        foreach (array_slice($jadwalRoutes, 0, 3) as $route) {
            echo "     - {$route}\n";
        }
        if (count($jadwalRoutes) > 3) {
            echo "     ... and " . (count($jadwalRoutes) - 3) . " more\n";
        }
    } else {
        echo "   ⚠ No jadwal routes found\n";
    }

    echo "\n=== TESTING COMPLETED SUCCESSFULLY ===\n";

} catch (Exception $e) {
    echo "\n✗ TESTING FAILED: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
