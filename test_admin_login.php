<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 ADMIN LOGIN TEST\n";
echo "==================\n\n";

// Check if admin users exist
$adminPusat = User::where('email', 'admin@smartshuttle.test')->first();
$jakartaAdmin = User::where('email', 'jakarta@smartshuttle.test')->first();

echo "Admin Pusat exists: " . ($adminPusat ? "✓ Yes" : "✗ No") . "\n";
echo "Jakarta Admin exists: " . ($jakartaAdmin ? "✓ Yes" : "✗ No") . "\n";

if ($adminPusat) {
    echo "Admin Pusat password valid: " . (Hash::check('admin123', $adminPusat->password) ? "✓ Yes" : "✗ No") . "\n";
    echo "Admin Pusat has admin_pusat role: " . ($adminPusat->hasRole('admin_pusat') ? "✓ Yes" : "✗ No") . "\n";
}

if ($jakartaAdmin) {
    echo "Jakarta Admin password valid: " . (Hash::check('password123', $jakartaAdmin->password) ? "✓ Yes" : "✗ No") . "\n";
    echo "Jakarta Admin has admin_cabang role: " . ($jakartaAdmin->hasRole('admin_cabang') ? "✓ Yes" : "✗ No") . "\n";
    echo "Jakarta Admin has branch_id: " . ($jakartaAdmin->branch_id ? "✓ Yes ({$jakartaAdmin->branch_id})" : "✗ No") . "\n";
}

echo "\n";

// Test authentication
if ($adminPusat) {
    echo "Testing admin_pusat login...\n";
    $credentials = ['email' => 'admin@smartshuttle.test', 'password' => 'admin123'];

    if (Auth::guard('admin')->attempt($credentials)) {
        $user = Auth::guard('admin')->user();
        echo "✓ Login successful for: {$user->name} ({$user->email})\n";
        echo "✓ User has admin_pusat role: " . ($user->hasRole('admin_pusat') ? "Yes" : "No") . "\n";
        Auth::guard('admin')->logout();
    } else {
        echo "✗ Login failed\n";
    }
}

if ($jakartaAdmin) {
    echo "\nTesting admin_cabang login...\n";
    $credentials = ['email' => 'jakarta@smartshuttle.test', 'password' => 'password123'];

    if (Auth::guard('admin')->attempt($credentials)) {
        $user = Auth::guard('admin')->user();
        echo "✓ Login successful for: {$user->name} ({$user->email})\n";
        echo "✓ User has admin_cabang role: " . ($user->hasRole('admin_cabang') ? "Yes" : "No") . "\n";
        echo "✓ User has branch_id: " . ($user->branch_id ? "Yes ({$user->branch_id})" : "No") . "\n";
        Auth::guard('admin')->logout();
    } else {
        echo "✗ Login failed\n";
    }
}

echo "\n🎉 Test completed!\n";
?>
