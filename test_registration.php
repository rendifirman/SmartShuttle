<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\CustomerController;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "=== TESTING CUSTOMER REGISTRATION ===\n\n";

// Create a mock request
$request = new Request();
$request->merge([
    'name' => 'Test User',
    'email' => 'test' . time() . '@example.com', // Unique email
    'password' => 'password123',
    'password_confirmation' => 'password123',
]);

echo "Test Data:\n";
echo "- Name: " . $request->input('name') . "\n";
echo "- Email: " . $request->input('email') . "\n";
echo "- Password: " . str_repeat('*', strlen($request->input('password'))) . "\n\n";

try {
    // Create controller instance
    $controller = new CustomerController();

    echo "Calling CustomerController::register()...\n";

    // Call the register method
    $response = $controller->register($request);

    echo "Response Type: " . get_class($response) . "\n";

    // Check if it's a redirect response (success)
    if ($response instanceof \Illuminate\Http\RedirectResponse) {
        echo "✅ SUCCESS: Registration completed!\n";
        echo "Redirect URL: " . $response->getTargetUrl() . "\n";

        // Check session for success message
        $session = session();
        if ($session->has('success')) {
            echo "Success Message: " . $session->get('success') . "\n";
        }

        // Check if user was stored in session
        if ($session->has('user')) {
            $user = $session->get('user');
            echo "User stored in session: " . ($user->name ?? 'Unknown') . " (ID: " . ($user->id ?? 'N/A') . ")\n";
        }

        // Check if token was stored in session
        if ($session->has('token')) {
            echo "Token stored in session: " . substr($session->get('token'), 0, 20) . "...\n";
        }

    } else {
        echo "❌ FAILED: Expected redirect response, got: " . get_class($response) . "\n";

        // Check for errors
        if (method_exists($response, 'getSession') && $response->getSession()) {
            $errors = $response->getSession()->get('errors');
            if ($errors) {
                echo "Validation Errors:\n";
                foreach ($errors->all() as $error) {
                    echo "- $error\n";
                }
            }
        }
    }

} catch (\Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== CHECKING DATABASE ===\n";

// Check if user was created in database
try {
    $user = \DB::table('users')->where('email', $request->input('email'))->first();
    if ($user) {
        echo "✅ DATABASE: User created successfully!\n";
        echo "- ID: " . $user->id . "\n";
        echo "- Name: " . $user->name . "\n";
        echo "- Email: " . $user->email . "\n";
        echo "- Created: " . $user->created_at . "\n";
    } else {
        echo "❌ DATABASE: User was not found in database\n";
    }
} catch (\Exception $e) {
    echo "❌ DATABASE ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== CHECKING LOGS ===\n";

// Check recent logs
try {
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $logs = file($logFile);
        $recentLogs = array_slice($logs, -10); // Last 10 lines

        echo "Recent log entries:\n";
        foreach ($recentLogs as $log) {
            if (strpos($log, 'CustomerController::register') !== false) {
                echo "- " . trim($log) . "\n";
            }
        }
    } else {
        echo "❌ Log file not found\n";
    }
} catch (\Exception $e) {
    echo "❌ LOG ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
