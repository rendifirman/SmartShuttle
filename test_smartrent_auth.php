<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=================================================================\n";
echo "SmartRent Authorization Test\n";
echo "=================================================================\n\n";

// Test 1: Check if SmartRent permissions exist
echo "1️⃣  PERMISSION CHECK\n";
echo "-------------------\n";

$smartrentPermissions = [
    'view_smartrent',
    'manage_smartrent'
];

foreach ($smartrentPermissions as $perm) {
    $exists = Permission::where('name', $perm)->where('guard_name', 'admin')->exists();
    echo "Permission '{$perm}': " . ($exists ? "✓ EXISTS" : "✗ MISSING") . "\n";
}

echo "\n";

// Test 2: Check roles and their SmartRent permissions
echo "2️⃣  ROLE PERMISSION ASSIGNMENTS\n";
echo "--------------------------------\n";

$roles = ['admin_pusat', 'admin_cabang', 'operator'];
$smartrentPerms = ['view_smartrent', 'manage_smartrent'];

foreach ($roles as $roleName) {
    $role = Role::where('name', $roleName)->where('guard_name', 'admin')->first();
    if (!$role) {
        echo "Role '{$roleName}': ✗ ROLE NOT FOUND\n";
        continue;
    }
    
    echo "Role '{$roleName}':\n";
    foreach ($smartrentPerms as $perm) {
        $hasPerm = $role->hasPermissionTo($perm);
        echo "  - {$perm}: " . ($hasPerm ? "✓ YES" : "✗ NO") . "\n";
    }
}

echo "\n";

// Test 3: Check test users have correct roles
echo "3️⃣  USER ROLE VERIFICATION\n";
echo "--------------------------\n";

$testUsers = [
    ['email' => 'admin@smartshuttle.test', 'expected_role' => 'admin_pusat', 'name' => 'Admin Pusat'],
    ['email' => 'jakarta@smartshuttle.test', 'expected_role' => 'admin_cabang', 'name' => 'Admin Cabang Jakarta'],
    ['email' => 'operator@smartshuttle.test', 'expected_role' => 'operator', 'name' => 'Operator'],
];

foreach ($testUsers as $test) {
    $user = User::where('email', $test['email'])->first();
    if (!$user) {
        echo "{$test['name']}: ✗ USER NOT FOUND\n";
        continue;
    }
    
    $hasRole = $user->hasRole($test['expected_role']);
    echo "{$test['name']} ({$test['email']}):\n";
    echo "  - Has role '{$test['expected_role']}': " . ($hasRole ? "✓ YES" : "✗ NO") . "\n";
    
    // Check SmartRent permissions
    foreach ($smartrentPerms as $perm) {
        $hasPerm = $user->hasPermissionTo($perm);
        echo "  - Has permission '{$perm}': " . ($hasPerm ? "✓ YES" : "✗ NO") . "\n";
    }
}

echo "\n";

// Test 4: Middleware checks
echo "4️⃣  MIDDLEWARE AUTHORIZATION CHECK\n";
echo "------------------------------------\n";

$user = User::where('email', 'admin@smartshuttle.test')->first();
if ($user) {
    echo "Testing admin@smartshuttle.test:\n";
    echo "  - Has role (admin_pusat|admin_cabang|operator): " . ($user->hasAnyRole(['admin_pusat', 'admin_cabang', 'operator']) ? "✓ YES" : "✗ NO") . "\n";
    echo "  - Has permission view_smartrent: " . ($user->hasPermissionTo('view_smartrent') ? "✓ YES" : "✗ NO") . "\n";
    echo "  - Has permission manage_smartrent: " . ($user->hasPermissionTo('manage_smartrent') ? "✓ YES" : "✗ NO") . "\n";
    
    if ($user->hasRole('admin_cabang') && !$user->branch_id) {
        echo "  - Branch check: ✗ FAILED (admin_cabang without branch_id)\n";
    } else {
        echo "  - Branch check: ✓ PASSED\n";
    }
}

echo "\n";

// Test 5: Incorrect permission check
echo "5️⃣  CHECKING FOR INVALID PERMISSIONS\n";
echo "-------------------------------------\n";

$invalidPerms = ['view_smartrent_transaksi', 'manage_smartrent_transaksi'];
foreach ($invalidPerms as $perm) {
    $exists = Permission::where('name', $perm)->where('guard_name', 'admin')->exists();
    echo "Permission '{$perm}': " . ($exists ? "✗ SHOULD NOT EXIST" : "✓ CORRECTLY MISSING") . "\n";
    
    // Check if any role has this invalid permission
    foreach ($roles as $roleName) {
        $role = Role::where('name', $roleName)->where('guard_name', 'admin')->first();
        if ($role && $role->permissions()->where('name', $perm)->exists()) {
            echo "  ⚠️  WARNING: Role '{$roleName}' has invalid permission '{$perm}'\n";
        }
    }
}

echo "\n✅ Authorization test completed!\n";
?>
