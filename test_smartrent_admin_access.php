<?php
/**
 * SmartRent Admin Access Verification Test
 * Verifies that admin users have proper role and permission configuration
 * 
 * Usage:
 * - Run via: php artisan tinker < test_smartrent_admin_access.php
 * - Or place in project root and run: php test_smartrent_admin_access.php
 */

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "═══════════════════════════════════════════════════════════════\n";
echo "🔍 SmartRent Admin Access Control Verification Test\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Test 1: Verify Permissions Exist
echo "TEST 1: ✓ Checking if SmartRent Permissions Exist\n";
echo "─────────────────────────────────────────────────\n";

$smartrent_view = Permission::where('name', 'view_smartrent')->where('guard_name', 'admin')->first();
$smartrent_manage = Permission::where('name', 'manage_smartrent')->where('guard_name', 'admin')->first();

if ($smartrent_view && $smartrent_manage) {
    echo "✅ view_smartrent permission: EXISTS\n";
    echo "✅ manage_smartrent permission: EXISTS\n";
} else {
    echo "⚠️  MISSING PERMISSIONS - Run migration: php artisan db:seed --class=PermissionSeeder\n";
    if (!$smartrent_view) echo "  ❌ view_smartrent\n";
    if (!$smartrent_manage) echo "  ❌ manage_smartrent\n";
}

echo "\n";

// Test 2: Verify Roles Have SmartRent Permissions
echo "TEST 2: ✓ Checking if Admin Roles Have SmartRent Permissions\n";
echo "─────────────────────────────────────────────────────────────\n";

$roles_to_check = ['admin_pusat', 'admin_cabang', 'operator'];

foreach ($roles_to_check as $role_name) {
    $role = Role::where('name', $role_name)->where('guard_name', 'admin')->first();
    
    if ($role) {
        $has_view = $role->hasPermissionTo('view_smartrent');
        $has_manage = $role->hasPermissionTo('manage_smartrent');
        
        echo "\n📌 Role: {$role_name}\n";
        echo "   view_smartrent: " . ($has_view ? "✅ YES" : "❌ NO") . "\n";
        echo "   manage_smartrent: " . ($has_manage ? "✅ YES" : "❌ NO") . "\n";
        
        if (!$has_view || !$has_manage) {
            echo "   ⚠️  ACTION: Run migration: php artisan db:seed --class=RoleSeeder\n";
        }
    } else {
        echo "\n❌ Role not found: {$role_name}\n";
    }
}

echo "\n";

// Test 3: Check Test Admin Users
echo "TEST 3: ✓ Checking Test Admin Users\n";
echo "────────────────────────────────────\n";

$test_admins = [
    'admin@smartshuttle.test' => ['expected_role' => 'admin_pusat', 'type' => 'Central Admin'],
    'jakarta@smartshuttle.test' => ['expected_role' => 'admin_cabang', 'type' => 'Branch Admin (Jakarta)'],
    'bogor@smartshuttle.test' => ['expected_role' => 'admin_cabang', 'type' => 'Branch Admin (Bogor)'],
    'operator@smartshuttle.test' => ['expected_role' => 'operator', 'type' => 'Operator'],
];

foreach ($test_admins as $email => $config) {
    $user = User::where('email', $email)->first();
    
    echo "\n👤 {$config['type']}: {$email}\n";
    
    if ($user) {
        $roles = $user->getRoleNames();
        $has_correct_role = $user->hasRole($config['expected_role']);
        $has_smartrent_view = $user->hasPermissionTo('view_smartrent');
        $has_smartrent_manage = $user->hasPermissionTo('manage_smartrent');
        
        echo "   Roles: " . ($roles->count() > 0 ? implode(', ', $roles->toArray()) : "NONE") . "\n";
        echo "   Expected role ({$config['expected_role']}): " . ($has_correct_role ? "✅ YES" : "❌ NO") . "\n";
        echo "   Permission (view_smartrent): " . ($has_smartrent_view ? "✅ YES" : "❌ NO") . "\n";
        echo "   Permission (manage_smartrent): " . ($has_smartrent_manage ? "✅ YES" : "❌ NO") . "\n";
        
        if ($user->hasRole('admin_cabang')) {
            echo "   Branch ID assigned: " . ($user->branch_id ? "✅ YES ({$user->branch_id})" : "❌ NO - ISSUE!") . "\n";
        }
        
        // Overall status
        if ($has_correct_role && $has_smartrent_view && $has_smartrent_manage) {
            if ($user->hasRole('admin_cabang') && !$user->branch_id) {
                echo "   🟡 STATUS: Role & Permissions OK, but Branch NOT assigned\n";
            } else {
                echo "   🟢 STATUS: ✅ Can access SmartRent\n";
            }
        } else {
            echo "   🔴 STATUS: ⚠️  Missing permissions or role\n";
        }
    } else {
        echo "   ⚠️  User not found - may need to run seeder\n";
    }
}

echo "\n";

// Test 4: Check Current Authenticated User (if any)
echo "TEST 4: ✓ Checking Current Authenticated Admin\n";
echo "──────────────────────────────────────────────\n";

if (Auth::guard('admin')->check()) {
    $current_user = Auth::guard('admin')->user();
    echo "✅ Authenticated as: {$current_user->name} ({$current_user->email})\n";
    echo "   Roles: " . $current_user->getRoleNames()->implode(', ') . "\n";
    echo "   view_smartrent: " . ($current_user->hasPermissionTo('view_smartrent') ? "✅ YES" : "❌ NO") . "\n";
    echo "   manage_smartrent: " . ($current_user->hasPermissionTo('manage_smartrent') ? "✅ YES" : "❌ NO") . "\n";
    
    if ($current_user->hasPermissionTo('view_smartrent')) {
        echo "   🟢 STATUS: Can access SmartRent menu\n";
    } else {
        echo "   🔴 STATUS: Cannot access SmartRent menu\n";
    }
} else {
    echo "⚠️  No admin authenticated (testing without login)\n";
}

echo "\n";

// Summary
echo "═══════════════════════════════════════════════════════════════\n";
echo "📋 VERIFICATION SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "✅ REQUIRED ACTIONS CHECKLIST:\n\n";

$all_issues = [];

// Check permissions
$perm_view = Permission::where('name', 'view_smartrent')->where('guard_name', 'admin')->exists();
$perm_manage = Permission::where('name', 'manage_smartrent')->where('guard_name', 'admin')->exists();

if (!$perm_view || !$perm_manage) {
    $all_issues[] = "1. ⚠️  SmartRent Permissions not found\n   FIX: php artisan db:seed --class=PermissionSeeder";
}

// Check role permissions
$roles_ok = true;
foreach (['admin_pusat', 'admin_cabang', 'operator'] as $role_name) {
    $role = Role::where('name', $role_name)->where('guard_name', 'admin')->first();
    if (!$role || !$role->hasPermissionTo('view_smartrent')) {
        $roles_ok = false;
        break;
    }
}

if (!$roles_ok) {
    $all_issues[] = "2. ⚠️  Admin roles missing SmartRent permissions\n   FIX: php artisan db:seed --class=RoleSeeder";
}

// Check test users
$branch_issue = false;
foreach ($test_admins as $email => $config) {
    $user = User::where('email', $email)->first();
    if ($user && $user->hasRole('admin_cabang') && !$user->branch_id) {
        $branch_issue = true;
    }
}

if ($branch_issue) {
    $all_issues[] = "3. ⚠️  Some admin_cabang users don't have branch assigned\n   FIX: Assign branch_id in database for admin_cabang users";
}

if (count($all_issues) === 0) {
    echo "🎉 All checks passed! SmartRent admin access is properly configured.\n";
    echo "\nActual test by:\n";
    echo "  1. Login as any admin user\n";
    echo "  2. Click SmartRent menu in sidebar\n";
    echo "  3. Should load without 403 error\n";
} else {
    echo "⚠️  Issues found:\n\n";
    foreach ($all_issues as $issue) {
        echo "  $issue\n\n";
    }
    echo "Run the suggested fixes above, then test again.\n";
}

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "✨ Test Complete\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
