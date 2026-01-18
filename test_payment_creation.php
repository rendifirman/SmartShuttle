<?php

/**
 * SMARTSHUTTLE PAYMENT CREATION TEST
 *
 * This script demonstrates how to properly test the payment creation endpoint.
 * Run this with: php artisan tinker --execute="require 'test_payment_creation.php';"
 */

echo "=== SMARTSHUTTLE PAYMENT CREATION TEST ===\n\n";

// Step 1: Login to get token
echo "Step 1: Logging in to get authentication token...\n";

try {
    $loginResponse = \Illuminate\Support\Facades\Http::post('http://localhost:8000/api/login', [
        'email' => 'test@example.com', // Use the test user from seeder
        'password' => 'password'
    ]);

    if (!$loginResponse->successful()) {
        echo "Login failed: " . $loginResponse->body() . "\n";
        echo "Make sure your Laravel server is running on port 8000\n";
        return;
    }

    $loginData = $loginResponse->json();
    $token = $loginData['token'];

    echo "✓ Login successful! Token: " . substr($token, 0, 20) . "...\n\n";

} catch (Exception $e) {
    echo "Login error: " . $e->getMessage() . "\n";
    echo "Make sure your Laravel server is running: php artisan serve\n";
    return;
}

// Step 2: Test payment creation with existing booking
echo "Step 2: Testing payment creation with existing booking...\n";

$testBookings = ['BOOK123456', 'BOOK001', 'BOOK002']; // Try different booking codes

foreach ($testBookings as $kodeBooking) {
    echo "Testing with booking code: $kodeBooking\n";

    try {
        $paymentResponse = \Illuminate\Support\Facades\Http::withToken($token)
            ->post('http://localhost:8000/api/payment/create', [
                'kode_booking' => $kodeBooking,
                'payment_method' => 'qris'
            ]);

        echo "Response Status: " . $paymentResponse->status() . "\n";

        if ($paymentResponse->successful()) {
            $paymentData = $paymentResponse->json();
            echo "✓ Payment creation successful!\n";
            echo "Payment Code: " . ($paymentData['data']['payment']['kode_pembayaran'] ?? 'N/A') . "\n";
            echo "Amount: " . ($paymentData['data']['payment']['jumlah'] ?? 'N/A') . "\n";
            echo "Status: " . ($paymentData['data']['payment']['status'] ?? 'N/A') . "\n";
            echo "QR Code: " . ($paymentData['data']['payment']['qr_code'] ?? 'N/A') . "\n\n";
            break; // Stop at first successful test
        } else {
            echo "✗ Payment creation failed for $kodeBooking\n";
            echo "Response: " . $paymentResponse->body() . "\n\n";
        }

    } catch (Exception $e) {
        echo "Error testing $kodeBooking: " . $e->getMessage() . "\n\n";
    }
}

// Step 3: Show available payment methods
echo "Step 3: Getting available payment methods...\n";

try {
    $methodsResponse = \Illuminate\Support\Facades\Http::get('http://localhost:8000/api/payment/methods');

    if ($methodsResponse->successful()) {
        $methods = $methodsResponse->json();
        echo "Available payment methods:\n";
        print_r($methods);
    } else {
        echo "Failed to get payment methods: " . $methodsResponse->body() . "\n";
    }

} catch (Exception $e) {
    echo "Error getting payment methods: " . $e->getMessage() . "\n";
}

echo "\n=== MANUAL TESTING INSTRUCTIONS ===\n";
echo "If the automated test above doesn't work, try these manual steps:\n\n";

echo "1. Start your Laravel server:\n";
echo "   php artisan serve\n\n";

echo "2. Get an authentication token by logging in:\n";
echo "   POST http://localhost:8000/api/login\n";
echo "   Body: {\"email\": \"your-customer@example.com\", \"password\": \"password\"}\n\n";

echo "3. Create a booking first (if you don't have one):\n";
echo "   POST http://localhost:8000/api/pemesanan\n";
echo "   Headers: Authorization: Bearer YOUR_TOKEN\n";
echo "   Body: {\n";
echo "     \"jadwal_id\": 1,\n";
echo "     \"detail_penumpang\": [{\n";
echo "       \"nama\": \"Test Customer\",\n";
echo "       \"telepon\": \"081234567890\",\n";
echo "       \"email\": \"customer@example.com\"\n";
echo "     }],\n";
echo "     \"kursi_dipesan\": [\"A1\"],\n";
echo "     \"promo_kode\": null\n";
echo "   }\n\n";

echo "4. Create payment using the correct endpoint:\n";
echo "   POST http://localhost:8000/api/payment/create\n";
echo "   Headers: Authorization: Bearer YOUR_TOKEN\n";
echo "   Body: {\n";
echo "     \"kode_booking\": \"YOUR_BOOKING_CODE\",\n";
echo "     \"payment_method\": \"qris\"\n";
echo "   }\n\n";

echo "5. Check payment status:\n";
echo "   GET http://localhost:8000/api/payment/status/YOUR_PAYMENT_CODE\n\n";

echo "=== TEST COMPLETED ===\n";
