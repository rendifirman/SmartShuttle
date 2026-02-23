<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\User;
use App\Models\SmartRentTransaction;

// Boot the app
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

echo "=== DATABASE STATE CHECK ===\n\n";

echo "Total Users: " . User::count() . "\n";
echo "Total SmartRent Transactions: " . SmartRentTransaction::count() . "\n";

echo "\n=== FIRST USER ===\n";
$user = User::first();
if ($user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
} else {
    echo "No users found\n";
}

echo "\n=== LATEST SMARTRENT TRANSACTIONS (LAST 3) ===\n";
$transactions = SmartRentTransaction::latest()->take(3)->get();
foreach ($transactions as $trans) {
    echo "Order: {$trans->order_number}\n";
    echo "Customer: {$trans->customer_name}\n";
    echo "Vehicle: {$trans->vehicle_name}\n";
    echo "Payment Status: {$trans->payment_status}\n";
    echo "---\n";
}

echo "\nTest completed!\n";
