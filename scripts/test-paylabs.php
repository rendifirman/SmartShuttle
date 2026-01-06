<?php
// Script untuk testing koneksi Paylabs
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\PaylabsService;

echo "=== Testing Paylabs Connection ===\n";

try {
    $paylabsService = new PaylabsService();

    // Test 1: Cek config
    echo "1. Checking Configuration:\n";
    echo "   - MID: " . config('paylabs.mid') . "\n";
    echo "   - Base URL: " . config('paylabs.base_url') . "\n";
    echo "   - Environment: " . config('paylabs.environment') . "\n";

    // Test 2: Cek RSA keys
    echo "\n2. Checking RSA Keys:\n";
    $privateKeyFile = config('paylabs.private_key_file');
    $publicKeyFile = config('paylabs.public_key_file');

    if (file_exists($privateKeyFile)) {
        echo "   ✓ Private key file exists\n";
        $privateKey = file_get_contents($privateKeyFile);
        echo "   - Private key length: " . strlen($privateKey) . " bytes\n";
    } else {
        echo "   ✗ Private key file NOT found: $privateKeyFile\n";
    }

    if (file_exists($publicKeyFile)) {
        echo "   ✓ Public key file exists\n";
    } else {
        echo "   ✗ Public key file NOT found: $publicKeyFile\n";
    }

    // Test 3: Test connection
    echo "\n3. Testing API Connection:\n";
    $result = $paylabsService->testConnection();

    if ($result['success']) {
        echo "   ✓ Connection successful!\n";
        echo "   - Status Code: " . $result['status_code'] . "\n";
    } else {
        echo "   ✗ Connection failed\n";
        echo "   - Error: " . $result['error'] . "\n";
    }

    // Test 4: Test signature generation
    echo "\n4. Testing Signature Generation:\n";
    $testData = [
        'requestType' => 'createPayment',
        'merchantId' => config('paylabs.mid'),
        'merchantTradeNo' => 'TEST' . time(),
        'amount' => '100000',
    ];

    $signature = $paylabsService->generateSignature($testData);

    if ($signature) {
        echo "   ✓ Signature generated successfully\n";
        echo "   - Signature length: " . strlen($signature) . " bytes\n";
    } else {
        echo "   ✗ Signature generation failed\n";
    }

    echo "\n=== Test Complete ===\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
