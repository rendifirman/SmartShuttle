<?php
require_once 'vendor/autoload.php';
use App\Services\PaylabsService;

$service = new PaylabsService();
$requestId = $service->generateRequestId();
$merchantTradeNo = 'VA-MANUAL-TEST-' . time();

$body = [
    'requestId' => $requestId,
    'merchantId' => $service->mid,
    'storeId' => $service->storeId ?: null,
    'paymentType' => 'BCAVA',
    'amount' => '10000.00',
    'merchantTradeNo' => $merchantTradeNo,
    'notifyUrl' => 'https://webhook.site/YOUR_WEBHOOK_URL',
    'feeType' => 'BEN',
    'productName' => 'Smart Shuttle Ticket',
    'productInfo' => [[
        'id' => 'TICKET001',
        'name' => 'Smart Shuttle Ticket',
        'price' => '10000.00',
        'type' => 'Ticket',
        'quantity' => 1
    ]],
    'payer' => 'Test Customer'
];

if (empty($body['storeId'])) {
    unset($body['storeId']);
}

$timestamp = date('Y-m-d\TH:i:s.vP');
$signature = $service->generateSignatureV23($body, $timestamp, '/payment/v2.3/va/create');

echo "=== MANUAL VA CREATION TEST ===\n\n";
echo "URL: https://sit-pay.paylabs.co.id/payment/v2.3/va/create\n\n";
echo "Method: POST\n\n";
echo "Headers:\n";
echo "Content-Type: application/json;charset=utf-8\n";
echo "Accept: application/json\n";
echo "X-TIMESTAMP: $timestamp\n";
echo "X-SIGNATURE: $signature\n";
echo "X-PARTNER-ID: {$service->mid}\n";
echo "X-REQUEST-ID: $requestId\n\n";
echo "Raw Body:\n";
echo json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
