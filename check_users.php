<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 CHECKING USERS AND ROLES\n";
echo "==========================\n\n";

// Check roles
echo "Roles in database:\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "- {$role->name} (guard: {$role->guard_name})\n";
}
echo "\n";

// Check users
echo "Users in database:\n";
$users = User::all();
foreach ($users as $user) {
    $userRoles = $user->getRoleNames()->join(', ');
    echo "- {$user->email} (roles: {$userRoles})\n";
}
echo "\n";

// Check specific admin users
$adminPusat = User::where('email', 'admin@smartshuttle.test')->first();
$jakartaAdmin = User::where('email', 'jakarta@smartshuttle.test')->first();

echo "Admin Pusat: " . ($adminPusat ? "EXISTS" : "NOT FOUND") . "\n";
echo "Jakarta Admin: " . ($jakartaAdmin ? "EXISTS" : "NOT FOUND") . "\n";

if ($adminPusat) {
    echo "Admin Pusat roles: " . $adminPusat->getRoleNames()->join(', ') . "\n";
    echo "Admin Pusat status: " . $adminPusat->status . "\n";
}

if ($jakartaAdmin) {
    echo "Jakarta Admin roles: " . $jakartaAdmin->getRoleNames()->join(', ') . "\n";
    echo "Jakarta Admin status: " . $jakartaAdmin->status . "\n";
    echo "Jakarta Admin branch_id: " . ($jakartaAdmin->branch_id ?? 'null') . "\n";
}

echo "\n🎉 Check completed!\n";
?>
