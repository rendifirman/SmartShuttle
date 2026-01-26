<?php
/**
 * Test script for membership payment webhook functionality
 * This script tests the membership payment status fix implementation
 */

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\MembershipPayment;
use App\Services\PaylabsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "=== MEMBERSHIP PAYMENT WEBHOOK TEST ===\n\n";

try {
    // Test 1: Create a test membership payment
    echo "Test 1: Creating test membership payment...\n";

    $user = User::where('email', 'test@example.com')->first();
    if (!$user) {
        $user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'membership_status' => 'non_member'
        ]);
        echo "✓ Created test user\n";
    }

    $payment = MembershipPayment::create([
        'user_id' => $user->id,
        'transaction_id' => 'TEST_MEM_' . time() . '_' . rand(1000, 9999),
        'amount' => 100000,
        'discount' => 0,
        'total_amount' => 100000,
        'payment_method' => 'qris',
        'payment_status' => 'pending',
        'waktu_kadaluarsa' => now()->addHours(24),
    ]);

    echo "✓ Created test membership payment: {$payment->transaction_id}\n";
    echo "  - Initial status: {$payment->payment_status}\n";
    echo "  - User membership status: {$user->membership_status}\n\n";

    // Test 2: Simulate Paylabs webhook callback
    echo "Test 2: Simulating Paylabs webhook callback...\n";

    $paylabsService = new PaylabsService();

    // Create mock webhook payload
    $webhookPayload = [
        'merchantId' => config('paylabs.mid'),
        'transactionId' => $payment->transaction_id,
        'merchantTradeNo' => $payment->transaction_id,
        'status' => 'PAID',
        'amount' => '100000.00',
        'currency' => 'IDR',
        'platformTradeNo' => 'PLT' . time() . rand(1000, 9999),
        'payTime' => now()->toISOString(),
        'signature' => 'test_signature' // Will be verified in webhook
    ];

    // Generate proper signature for the webhook
    $timestamp = now()->format('Y-m-d\TH:i:s.v\Z');
    $signature = $paylabsService->generateSignatureV23($webhookPayload, $timestamp, '/payment/v2.3/callback');

    // Create HTTP request simulation
    $request = new Illuminate\Http\Request();
    $request->merge($webhookPayload);
    $request->headers->set('X-TIMESTAMP', $timestamp);
    $request->headers->set('X-SIGNATURE', $signature);

    // Call the webhook method directly
    $customerController = new App\Http\Controllers\CustomerController($paylabsService);
    $response = $customerController->membershipWebhook($request);

    echo "✓ Webhook called with status: PAID\n";

    // Test 3: Verify payment status update
    echo "\nTest 3: Verifying payment status update...\n";

    $payment->refresh();
    $user->refresh();

    echo "  - Payment status after webhook: {$payment->payment_status}\n";
    echo "  - User membership status after webhook: {$user->membership_status}\n";

    if ($payment->payment_status === 'success') {
        echo "✓ Payment status correctly updated to 'success'\n";
    } else {
        echo "✗ Payment status not updated correctly\n";
    }

    if ($user->membership_status === 'active') {
        echo "✓ User membership correctly activated\n";
    } else {
        echo "✗ User membership not activated\n";
    }

    // Test 4: Test different status codes
    echo "\nTest 4: Testing different Paylabs status codes...\n";

    $testStatuses = ['PENDING', 'FAILED', 'EXPIRED'];

    foreach ($testStatuses as $status) {
        echo "  Testing status: {$status}\n";

        $webhookPayload['status'] = $status;
        $signature = $paylabsService->generateSignatureV23($webhookPayload, $timestamp, '/payment/v2.3/callback');

        $request = new Illuminate\Http\Request();
        $request->merge($webhookPayload);
        $request->headers->set('X-TIMESTAMP', $timestamp);
        $request->headers->set('X-SIGNATURE', $signature);

        $response = $customerController->membershipWebhook($request);

        $payment->refresh();
        $user->refresh();

        $expectedLocalStatus = match($status) {
            'PENDING' => 'pending',
            'FAILED' => 'failed',
            'EXPIRED' => 'expired',
            default => 'pending'
        };

        echo "    - Payment status: {$payment->payment_status} (expected: {$expectedLocalStatus})\n";

        if ($payment->payment_status === $expectedLocalStatus) {
            echo "    ✓ Status mapping correct\n";
        } else {
            echo "    ✗ Status mapping incorrect\n";
        }

        // Membership should remain inactive for non-PAID statuses
        if ($status !== 'PAID' && $user->membership_status === 'active') {
            echo "    ✗ Membership incorrectly activated for non-PAID status\n";
        }
    }

    // Cleanup
    echo "\nTest 5: Cleaning up test data...\n";
    $payment->delete();
    if ($user->email === 'test@example.com') {
        $user->delete();
    }
    echo "✓ Test data cleaned up\n";

    echo "\n=== TEST COMPLETED SUCCESSFULLY ===\n";

} catch (Exception $e) {
    echo "\n✗ TEST FAILED: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
