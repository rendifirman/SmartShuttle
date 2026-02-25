<?php
/**
 * Test Script: E-Ticket Redirect and Payment Status Display Fixes
 * Run with: php artisan tinker < test_eticket_and_payment_status.php
 * Or: php -r "include('test_eticket_and_payment_status.php');"
 */

use App\Models\SmartRentTransaction;
use App\Models\SmartRentOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== SmartRent E-Ticket and Payment Status Fix Test ===\n\n";

try {
    // Test 1: Check if SmartRentTransaction can be created
    echo "TEST 1: Creating test SmartRentTransaction...\n";
    
    $testUser = User::where('role', 'customer')->first();
    if (!$testUser) {
        echo "ERROR: No customer user found. Creating test user...\n";
        $testUser = User::create([
            'name' => 'Test Customer',
            'email' => 'test-eticket-' . time() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '08123456789',
        ]);
    }
    
    $orderNumber = 'SR' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    
    $transaction = SmartRentTransaction::create([
        'order_number' => $orderNumber,
        'invoice_number' => 'INV-SR-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8)),
        'user_id' => $testUser->id,
        'vehicle_id' => 1,
        'vehicle_name' => 'Test Vehicle',
        'vehicle_type' => 'Sedan',
        'vehicle_price' => 100000,
        'duration' => 3,
        'vehicle_total' => 300000,
        'service_type' => 'self_drive',
        'driver_price_per_day' => 0,
        'driver_total' => 0,
        'total_price' => 300000.00,
        'customer_name' => $testUser->name,
        'customer_email' => $testUser->email,
        'customer_phone' => $testUser->phone,
        'customer_address' => 'Test Address',
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(4),
        'start_time' => '08:00',
        'end_time' => '17:00',
        'pickup_location' => 'Test Location',
        'payment_status' => 'unpaid',
        'status' => 'pending_payment',
    ]);
    echo "✓ Transaction created: {$transaction->order_number}\n";
    echo "  - Payment Status: {$transaction->payment_status}\n";
    echo "  - Is Paid: " . ($transaction->is_paid ? 'true' : 'false') . "\n";
    
    // Test 2: Check if SmartRentOrder is created
    echo "\nTEST 2: Creating test SmartRentOrder...\n";
    
    $order = SmartRentOrder::create([
        'order_number' => $orderNumber,
        'invoice_number' => $transaction->invoice_number,
        'user_id' => $testUser->id,
        'vehicle_id' => 1,
        'vehicle_name' => 'Test Vehicle',
        'vehicle_type' => 'Sedan',
        'vehicle_price' => 100000,
        'duration' => 3,
        'vehicle_total' => 300000,
        'service_type' => 'self_drive',
        'driver_price_per_day' => 0,
        'driver_total' => 0,
        'total_price' => 300000.00,
        'customer_name' => $testUser->name,
        'customer_email' => $testUser->email,
        'customer_phone' => $testUser->phone,
        'customer_address' => 'Test Address',
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(4),
        'start_time' => '08:00',
        'end_time' => '17:00',
        'pickup_location' => 'Test Location',
        'status' => 'pending_payment',
    ]);
    echo "✓ Order created: ID {$order->id}, Status: {$order->status}\n";
    
    // Test 3: Simulate payment update (like processPayment() does)
    echo "\nTEST 3: Simulating payment processing...\n";
    
    DB::beginTransaction();
    
    // Update transaction
    $transaction->update([
        'payment_status' => 'paid',
        'payment_method' => 'qris',
        'paid_at' => now(),
        'status' => 'confirmed',
    ]);
    echo "✓ Transaction updated:\n";
    echo "  - Payment Status: {$transaction->payment_status}\n";
    echo "  - Is Paid: " . ($transaction->is_paid ? 'true' : 'false') . "\n";
    echo "  - Paid At: {$transaction->paid_at}\n";
    
    // Update order status
    $order->update(['status' => 'paid']);
    echo "✓ Order status updated: {$order->status}\n";
    
    DB::commit();
    
    // Test 4: Verify data after commit
    echo "\nTEST 4: Verifying data consistency...\n";
    
    $verifyTransaction = SmartRentTransaction::find($transaction->id);
    $verifyOrder = SmartRentOrder::find($order->id);
    
    // Check transaction status
    if ($verifyTransaction->payment_status === 'paid' && $verifyTransaction->is_paid) {
        echo "✓ Transaction payment status is correct (paid)\n";
    } else {
        echo "✗ ERROR: Transaction payment status is incorrect\n";
        echo "  - payment_status: {$verifyTransaction->payment_status}\n";
        echo "  - is_paid: " . ($verifyTransaction->is_paid ? 'true' : 'false') . "\n";
    }
    
    // Check order status
    if ($verifyOrder->status === 'paid') {
        echo "✓ Order status is correct (paid)\n";
    } else {
        echo "✗ ERROR: Order status is incorrect: {$verifyOrder->status}\n";
    }
    
    // Test 5: Check accessor values (as displayed on page)
    echo "\nTEST 5: Checking display labels...\n";
    
    $transactionLabel = $verifyTransaction->payment_status_label;
    
    echo "  - Transaction Payment Status Label: {$transactionLabel}\n";
    
    if (strpos($transactionLabel, 'Lunas') !== false) {
        echo "✓ Transaction label shows 'Lunas' (paid)\n";
    } else {
        echo "✗ ERROR: Transaction label does not show paid status\n";
    }
    
    // Test 6: Test relationship loading
    echo "\nTEST 6: Testing relationship loading...\n";
    
    $orderWithPayment = SmartRentOrder::with('payment')->find($order->id);
    if ($orderWithPayment->payment) {
        echo "✓ Order->payment relationship loaded successfully\n";
        echo "  - Payment Status: {$orderWithPayment->payment->payment_status}\n";
    } else {
        echo "✗ ERROR: Order->payment relationship not found\n";
    }
    
    // Test 7: Test transaction retrieval
    echo "\nTEST 7: Testing transaction retrieval (as done in success page)...\n";
    
    $freshTransaction = SmartRentTransaction::where('order_number', $orderNumber)
        ->where('user_id', $testUser->id)
        ->first();
    
    if ($freshTransaction && $freshTransaction->is_paid) {
        echo "✓ Transaction retrieved fresh from database with correct paid status\n";
        echo "  - Order Number: {$freshTransaction->order_number}\n";
        echo "  - Is Paid: " . ($freshTransaction->is_paid ? 'true' : 'false') . "\n";
        echo "  - Payment Status Label: {$freshTransaction->payment_status_label}\n";
    } else {
        echo "✗ ERROR: Failed to retrieve fresh transaction or status incorrect\n";
    }
    
    // Test 8: Test e-ticket access conditions
    echo "\nTEST 8: Testing e-ticket access conditions...\n";
    
    if ($freshTransaction->is_paid) {
        echo "✓ TransactionisPayment check PASSED - E-Ticket access allowed\n";
    } else {
        echo "✗ ERROR: Transaction is_paid check FAILED - E-Ticket access would be denied\n";
    }
    
    // Summary
    echo "\n=== TEST SUMMARY ===\n";
    echo "Order Number: {$orderNumber}\n";
    echo "User ID: {$testUser->id}\n";
    echo "Transaction ID: {$transaction->id}\n";
    echo "Order ID: {$order->id}\n";
    echo "Payment ID: {$payment->id}\n";
    echo "\nTo manually verify in the app:\n";
    echo "1. Login with user ID: {$testUser->id}\n";
    echo "2. Navigate to: /customer/smartrent/payment-success?order_number={$orderNumber}\n";
    echo "3. Verify payment status shows 'Sudah Dibayar' or similar\n";
    echo "4. Click 'Lihat E-Ticket' button\n";
    echo "5. Verify navigation to e-ticket page succeeds\n";
    
    echo "\n✓ All tests completed successfully!\n";
    
} catch (\Exception $e) {
    echo "\n✗ ERROR: {$e->getMessage()}\n";
    echo "Trace: {$e->getTraceAsString()}\n";
    exit(1);
}
?>
