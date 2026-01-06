<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use Illuminate\Support\Str;

class PaylabsService
{
    protected $mid;
    protected $privateKey;
    protected $publicKey;
    protected $baseUrl;
    protected $callbackUrl;
    protected $endpoint;

    public function __construct()
    {
        $this->mid = config('paylabs.mid', '010529');
        $this->privateKey = config('paylabs.private_key', '');
        $this->publicKey = config('paylabs.public_key', '');
        $this->baseUrl = config('paylabs.base_url', 'https://sandbox.paylabs.co.id');
        $this->endpoint = config('paylabs.endpoint', '/pembayaran');
        $this->callbackUrl = config('paylabs.callback_url', 'http://localhost:8000/api/payment/callback');

        Log::info('PAYLABS Service initialized for Smart Shuttle App:', [
            'mid' => $this->mid,
            'base_url' => $this->baseUrl,
            'environment' => config('paylabs.environment', 'sandbox'),
            'testing_mode' => config('paylabs.testing.enabled', false)
        ]);
    }

    /**
     * Create payment request to Paylabs v4.8.1
     */
    public function createPayment(Pembayaran $payment, $channelCode, $channelName)
    {
        try {
            Log::info('PAYLABS: Creating payment for Smart Shuttle App', [
                'payment_id' => $payment->id,
                'kode_pembayaran' => $payment->kode_pembayaran,
                'amount' => $payment->jumlah,
                'channel' => $channelCode,
                'mid' => $this->mid
            ]);

            // Jika testing mode aktif, langsung return dummy
            if (config('paylabs.testing.enabled', false)) {
                Log::info('PAYLABS: Using testing mode');

                $dummyResponse = [
                    'responseCode' => '00',
                    'responseMessage' => 'Success (Test Mode)',
                    'transactionId' => 'T' . time() . rand(1000, 9999),
                    'status' => 'PENDING',
                    'amount' => (int) $payment->jumlah,
                    'currency' => 'IDR',
                    'paymentChannel' => $channelCode,
                    'expiredTime' => now()->addMinutes(30)->toISOString(),
                ];

                // Tambahkan data spesifik channel
                if ($channelCode === 'QRIS') {
                    $dummyResponse['qrCode'] = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' .
                        urlencode('SMARTSHUTTLEAPP|' . $payment->kode_pembayaran . '|' . $payment->jumlah);
                }

                if (strpos($channelCode, 'VA_') === 0) {
                    $bankCode = str_replace('VA_', '', $channelCode);
                    $dummyResponse['vaNumber'] = '888' . rand(100000000, 999999999);
                    $dummyResponse['bankName'] = $bankCode;
                }

                // Update payment
                $updateData = [
                    'paylabs_transaction_id' => $dummyResponse['transactionId'],
                    'paylabs_status' => $dummyResponse['status'],
                    'paylabs_response' => json_encode($dummyResponse),
                    'updated_at' => now(),
                ];

                if (isset($dummyResponse['qrCode'])) {
                    $updateData['qr_code'] = $dummyResponse['qrCode'];
                }

                if (isset($dummyResponse['vaNumber'])) {
                    $updateData['no_virtual_account'] = $dummyResponse['vaNumber'];
                    $updateData['nama_bank'] = $dummyResponse['bankName'];
                }

                $payment->update($updateData);

                return [
                    'success' => true,
                    'transaction_id' => $dummyResponse['transactionId'],
                    'payment_data' => $dummyResponse,
                    'is_test_mode' => true,
                ];
            }

            // Prepare request data
            $requestData = [
                'requestType' => 'createPayment',
                'merchantId' => $this->mid,
                'merchantTradeNo' => $payment->kode_pembayaran,
                'amount' => (string) intval($payment->jumlah),
                'currency' => 'IDR',
                'productName' => 'Smart Shuttle App Ticket',
                'productDetail' => 'Payment for booking: ' . ($payment->pemesanan->kode_booking ?? 'N/A'),
                'feeType' => 'MERCHANT',
                'customerName' => $payment->pemesanan->user->name ?? 'Customer',
                'customerEmail' => $payment->pemesanan->user->email ?? 'customer@example.com',
                'customerPhone' => $payment->pemesanan->user->telepon ?? '08123456789',
                'channelCode' => $channelCode,
                'notifyUrl' => $this->callbackUrl,
                'returnUrl' => url('/customer/detail-pemesanan/' . ($payment->pemesanan->kode_booking ?? 'test')),
                'expiredTime' => 30,
            ];

            Log::info('PAYLABS Request Data:', $requestData);

            // Minify JSON
            $minifiedBody = $this->minifyJson($requestData);
            $timestamp = time() * 1000;

            // Generate signature
            $signature = $this->generateSignatureV481($minifiedBody, $timestamp, $channelCode);

            // Determine endpoint
            $endpointPath = $this->getEndpointPath($channelCode);
            $endpointUrl = rtrim($this->baseUrl, '/') . $endpointPath;

            Log::info('PAYLABS API Call:', [
                'url' => $endpointUrl,
                'timestamp' => $timestamp,
                'signature_length' => strlen($signature)
            ]);

            // Make HTTP request
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'X-TIMESTAMP' => $timestamp,
                    'X-SIGNATURE' => $signature,
                    'X-MERCHANT-ID' => $this->mid,
                ])
                ->post($endpointUrl, $requestData);

            $statusCode = $response->status();
            $responseBody = $response->body();

            Log::info('PAYLABS Response:', [
                'status' => $statusCode,
                'body' => $responseBody
            ]);

            if (!$response->successful()) {
                $errorMsg = "HTTP {$statusCode}: ";
                if (str_contains($responseBody, '<!DOCTYPE html>')) {
                    $errorMsg .= 'HTML response received (possibly 404)';
                } else {
                    $errorMsg .= substr($responseBody, 0, 100);
                }
                throw new \Exception($errorMsg);
            }

            $responseData = $response->json();

            if (isset($responseData['responseCode']) && $responseData['responseCode'] === '00') {
                // Update payment with response data
                $updateData = [
                    'paylabs_transaction_id' => $responseData['transactionId'] ?? null,
                    'paylabs_status' => $responseData['status'] ?? 'PENDING',
                    'paylabs_response' => json_encode($responseData),
                    'updated_at' => now(),
                ];

                if (isset($responseData['vaNumber'])) {
                    $updateData['no_virtual_account'] = $responseData['vaNumber'];
                    $updateData['nama_bank'] = $responseData['bankName'] ?? $channelName;
                }

                if (isset($responseData['qrCode'])) {
                    $updateData['qr_code'] = $responseData['qrCode'];
                }

                if (isset($responseData['checkoutUrl'])) {
                    $updateData['checkout_url'] = $responseData['checkoutUrl'];
                }

                $payment->update($updateData);

                return [
                    'success' => true,
                    'transaction_id' => $responseData['transactionId'] ?? null,
                    'payment_data' => $this->formatPaymentData($responseData, $channelCode),
                    'is_test_mode' => false,
                ];
            } else {
                $errorCode = $responseData['responseCode'] ?? 'N/A';
                $errorMessage = $responseData['responseMessage'] ?? $responseData['errCodeDes'] ?? 'Unknown error';
                throw new \Exception("Paylabs Error {$errorCode}: {$errorMessage}");
            }

        } catch (\Exception $e) {
            Log::error('PAYLABS Create Payment Error:', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id ?? 'N/A',
                'channel_code' => $channelCode,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => 'PAYLABS_ERROR',
                'is_test_mode' => config('paylabs.testing.enabled', false)
            ];
        }
    }

    /**
     * Create payment with fallback to local QRIS
     */
    public function createPaymentWithFallback(Pembayaran $payment, $channelCode, $channelName)
    {
        // Try Paylabs first
        $paylabsResult = $this->createPayment($payment, $channelCode, $channelName);

        if ($paylabsResult['success']) {
            return $paylabsResult;
        }

        // If Paylabs fails, use local fallback
        Log::warning('Paylabs failed, using local fallback', [
            'error' => $paylabsResult['error'] ?? 'Unknown',
            'payment_id' => $payment->id
        ]);

        return $this->createLocalPayment($payment, $channelCode);
    }

    /**
     * Create local payment (fallback)
     */
    private function createLocalPayment(Pembayaran $payment, $channelCode)
    {
        try {
            $updateData = [
                'paylabs_transaction_id' => 'LOCAL_' . time(),
                'paylabs_status' => 'PENDING',
                'paylabs_response' => json_encode(['local_fallback' => true]),
                'updated_at' => now(),
            ];

            // Generate local QRIS
            if ($channelCode === 'QRIS') {
                $updateData['qr_code'] = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' .
                    urlencode('SMARTSHUTTLEAPP|' . $payment->kode_pembayaran . '|' . $payment->jumlah . '|' . time());
                $updateData['instruksi_pembayaran'] = json_encode([
                    '1. Buka aplikasi e-wallet (DANA, OVO, GoPay, dll)',
                    '2. Pilih menu "Scan QR"',
                    '3. Scan QR code di atas',
                    '4. Bayar: Rp ' . number_format($payment->jumlah, 0, ',', '.'),
                    '5. Sistem akan otomatis mendeteksi pembayaran'
                ]);
            }

            // For Virtual Account
            if (strpos($channelCode, 'VA_') === 0) {
                $bankCode = str_replace('VA_', '', $channelCode);
                $updateData['no_virtual_account'] = '888' . rand(100000000, 999999999);
                $updateData['nama_bank'] = $bankCode;
                $updateData['instruksi_pembayaran'] = json_encode([
                    '1. Transfer ke Virtual Account: ' . $updateData['no_virtual_account'],
                    '2. Bank: ' . $bankCode,
                    '3. Jumlah: Rp ' . number_format($payment->jumlah, 0, ',', '.'),
                    '4. Pembayaran akan diverifikasi otomatis'
                ]);
            }

            $payment->update($updateData);

            return [
                'success' => true,
                'transaction_id' => $updateData['paylabs_transaction_id'],
                'payment_data' => $updateData,
                'is_fallback' => true,
            ];

        } catch (\Exception $e) {
            Log::error('Local payment fallback failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Both Paylabs and local fallback failed: ' . $e->getMessage(),
                'is_fallback' => true,
            ];
        }
    }

    /**
     * Minify JSON string (remove whitespace)
     */
    private function minifyJson($data)
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get correct endpoint path based on channel code
     */
    private function getEndpointPath($channelCode)
    {
        // Untuk Paylabs v4.8.1, endpoint biasanya:
        // - Virtual Account: /payment/va/create
        // - QRIS: /payment/qris/create
        // - E-Wallet: /payment/ewallet/create

        // Namun jika 404, coba endpoint alternatif:

        // Alternatif 1: Tanpa "payment/" prefix
        if (strpos($channelCode, 'VA_') === 0) {
            return '/va/create'; // Coba tanpa "payment/"
        }

        if ($channelCode === 'QRIS') {
            return '/qris/create'; // Coba tanpa "payment/"
        }

        if (strpos($channelCode, 'EW_') === 0) {
            return '/ewallet/create'; // Coba tanpa "payment/"
        }

        // Default fallback
        return '/va/create';
    }

    /**
     * Generate signature for Paylabs v4.8.1
     * Format: POST:{endpoint}:sha256(minified_json_body):X-TIMESTAMP
     */
    private function generateSignatureV481($minifiedBody, $timestamp, $channelCode)
    {
        try {
            // Hash the minified JSON body using SHA256 (hex lowercase)
            $bodyHash = hash('sha256', $minifiedBody);

            // Get correct endpoint path based on channel
            $endpointPath = $this->getEndpointPath($channelCode);

            // Create signature string
            $signatureString = "POST:{$endpointPath}:{$bodyHash}:{$timestamp}";

            Log::debug('PAYLABS v4.8.1 Signature String:', ['string' => $signatureString]);

            // Load private key
            $privateKey = $this->loadPrivateKey();

            // Sign using RSA SHA256
            $signature = '';
            if (!openssl_sign($signatureString, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
                throw new \Exception('Failed to sign data: ' . openssl_error_string());
            }

            openssl_free_key($privateKey);

            // Return base64 encoded signature
            return base64_encode($signature);

        } catch (\Exception $e) {
            Log::error('PAYLABS v4.8.1 Signature Generation Error:', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Load and prepare private key
     */
    private function loadPrivateKey()
    {
        // Coba load dari file dulu
        $keyFile = config('paylabs.private_key_file');
        if ($keyFile && file_exists($keyFile)) {
            $rawPrivate = file_get_contents($keyFile);
        } else {
            $rawPrivate = $this->privateKey;
        }

        // Jika masih kosong, buat dummy untuk testing
        if (empty($rawPrivate)) {
            Log::warning('PAYLABS: No private key found, using dummy key for testing');
            $rawPrivate = "-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC0vWQp5iJ8Q8Pz
DUMMY_KEY_SMARTSHUTTLE_APP_TESTING_ONLY_12345
-----END PRIVATE KEY-----";
        }

        $rawPrivate = trim($rawPrivate);
        $rawPrivate = trim($rawPrivate, "\"'\n\r\t ");
        if (strpos($rawPrivate, '\\n') !== false) {
            $rawPrivate = str_replace('\\n', "\n", $rawPrivate);
        }

        // Ensure header/footer exist; if not, wrap base64 content
        if (strpos($rawPrivate, '-----BEGIN') === false) {
            $rawPrivate = trim($rawPrivate);
            $rawPrivate = "-----BEGIN PRIVATE KEY-----\n" .
                chunk_split(preg_replace('/\s+/', '', $rawPrivate), 64, "\n") .
                "-----END PRIVATE KEY-----";
        }

        $privateKey = openssl_pkey_get_private($rawPrivate);

        if (!$privateKey) {
            $sample = substr(preg_replace('/\s+/', ' ', $rawPrivate), 0, 80);
            throw new \Exception('Invalid private key format: ' . openssl_error_string() . ' (sample: ' . $sample . ', len=' . strlen($rawPrivate) . ')');
        }

        return $privateKey;
    }

    /**
     * Check payment status (legacy method - may need update for v4.8.1)
     */
    public function checkStatus($transactionId)
    {
        try {
            $requestData = [
                'requestType' => 'queryPayment',
                'merchantId' => $this->mid,
                'transactionId' => $transactionId,
            ];

            $requestData['signature'] = $this->generateSignature($requestData);

            Log::info('PAYLABS Check Status Request:', ['transactionId' => $transactionId]);

            $endpointUrl = rtrim($this->baseUrl, '/') . '/' . ltrim($this->endpoint, '/');
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($endpointUrl, $requestData);

            $responseData = $response->json();

            Log::info('PAYLABS Check Status Response:', $responseData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => $responseData['status'] ?? 'UNKNOWN',
                    'transactionId' => $responseData['transactionId'] ?? $transactionId,
                    'amount' => $responseData['amount'] ?? 0,
                    'paymentChannel' => $responseData['paymentChannel'] ?? '',
                    'paymentTime' => $responseData['paymentTime'] ?? null,
                    'raw_response' => $responseData
                ];
            }

            throw new \Exception('Failed to check status: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('PAYLABS Check Status Error:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);

            return [
                'success' => false,
                'status' => 'UNKNOWN',
                'transactionId' => $transactionId,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate signature for legacy requests (backward compatibility)
     */
    public function generateSignature($data)
    {
        try {
            // Sort data by key
            ksort($data);

            // Create string to sign (exclude signature if exists)
            if (isset($data['signature'])) {
                unset($data['signature']);
            }

            $stringToSign = '';
            foreach ($data as $key => $value) {
                if ($value !== null && $value !== '') {
                    $stringToSign .= $key . '=' . $value . '&';
                }
            }
            $stringToSign = rtrim($stringToSign, '&');

            Log::debug('PAYLABS Legacy String to sign:', ['string' => $stringToSign]);

            $privateKey = $this->loadPrivateKey();

            // Sign the data
            $signature = '';
            if (!openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
                throw new \Exception('Failed to sign data: ' . openssl_error_string());
            }

            openssl_free_key($privateKey);

            // Return base64 encoded signature
            return base64_encode($signature);

        } catch (\Exception $e) {
            Log::error('PAYLABS Legacy Signature Generation Error:', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Verify callback signature (legacy method - may need update for v4.8.1)
     */
    public function verifySignature($data, $signature)
    {
        try {
            // Extract signature from data
            $dataToVerify = $data;
            unset($dataToVerify['signature']);

            // Sort data by key
            ksort($dataToVerify);

            // Create string to verify
            $stringToVerify = '';
            foreach ($dataToVerify as $key => $value) {
                if ($value !== null && $value !== '') {
                    $stringToVerify .= $key . '=' . $value . '&';
                }
            }
            $stringToVerify = rtrim($stringToVerify, '&');

            Log::debug('PAYLABS String to verify:', ['string' => $stringToVerify]);

            // Load public key
            $publicKey = $this->loadPublicKey();
            if (!$publicKey) {
                throw new \Exception('Invalid public key format: ' . openssl_error_string());
            }

            // Verify signature
            $result = openssl_verify($stringToVerify, base64_decode($signature), $publicKey, OPENSSL_ALGO_SHA256);
            openssl_free_key($publicKey);

            return $result === 1;

        } catch (\Exception $e) {
            Log::error('PAYLABS Signature Verification Error:', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return false;
        }
    }

    /**
     * Load and prepare public key
     */
    private function loadPublicKey()
    {
        // Coba load dari file dulu
        $keyFile = config('paylabs.public_key_file');
        if ($keyFile && file_exists($keyFile)) {
            $rawPublic = file_get_contents($keyFile);
        } else {
            $rawPublic = $this->publicKey;
        }

        // Jika masih kosong, buat dummy untuk testing
        if (empty($rawPublic)) {
            Log::warning('PAYLABS: No public key found, using dummy key for testing');
            $rawPublic = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtL1kKeYifEPD8z
DUMMY_KEY_SMARTSHUTTLE_APP_TESTING_ONLY_12345
-----END PUBLIC KEY-----";
        }

        $rawPublic = trim($rawPublic);
        $rawPublic = trim($rawPublic, "\"'\n\r\t ");
        if (strpos($rawPublic, '\\n') !== false) {
            $rawPublic = str_replace('\\n', "\n", $rawPublic);
        }

        // Ensure header/footer exist; if not, wrap base64 content
        if ($rawPublic && strpos($rawPublic, '-----BEGIN') === false) {
            $rawPublic = trim($rawPublic);
            $rawPublic = "-----BEGIN PUBLIC KEY-----\n" .
                chunk_split(preg_replace('/\s+/', '', $rawPublic), 64, "\n") .
                "-----END PUBLIC KEY-----";
        }

        return openssl_pkey_get_public($rawPublic);
    }

    /**
     * Generate payment instructions based on payment channel
     */
    private function generatePaymentInstructions($responseData, $channelCode)
    {
        $instructions = [];

        if (strpos($channelCode, 'VA_') === 0) {
            // Virtual Account
            $instructions = [
                '1. Login ke aplikasi mobile banking Anda',
                '2. Pilih menu "Transfer" atau "Pembayaran"',
                '3. Pilih "Virtual Account" atau "Transfer ke VA"',
                '4. Masukkan nomor VA: <strong>' . ($responseData['vaNumber'] ?? '') . '</strong>',
                '5. Masukkan jumlah: <strong>Rp ' . number_format($responseData['amount'] ?? 0, 0, ',', '.') . '</strong>',
                '6. Konfirmasi dan selesaikan pembayaran',
                '7. Pembayaran akan diproses otomatis dalam 1-2 menit'
            ];
        } elseif (strpos($channelCode, 'EW_') === 0) {
            // E-Wallet
            $instructions = [
                '1. Buka aplikasi e-wallet Anda',
                '2. Scan QR code yang tersedia',
                '3. Konfirmasi jumlah pembayaran',
                '4. Selesaikan transaksi',
                '5. Atau klik link pembayaran jika tersedia'
            ];
        } elseif ($channelCode === 'QRIS') {
            // QRIS
            $instructions = [
                '1. Buka aplikasi mobile banking atau e-wallet',
                '2. Pilih menu "Scan QR"',
                '3. Scan QR code yang tersedia',
                '4. Konfirmasi jumlah pembayaran',
                '5. Selesaikan transaksi',
                '6. Mendukung: DANA, OVO, GoPay, ShopeePay, LinkAja, dll'
            ];
        }

        return json_encode($instructions);
    }

    /**
     * Format payment data for frontend
     */
    private function formatPaymentData($responseData, $channelCode)
    {
        $data = [
            'transaction_id' => $responseData['transactionId'] ?? null,
            'amount' => $responseData['amount'] ?? 0,
            'status' => $responseData['status'] ?? 'PENDING',
            'expired_time' => $responseData['expiredTime'] ?? null,
            'response_code' => $responseData['responseCode'] ?? null,
            'response_message' => $responseData['responseMessage'] ?? null
        ];

        if (strpos($channelCode, 'VA_') === 0) {
            $data['virtual_account'] = $responseData['vaNumber'] ?? null;
            $data['bank_name'] = $responseData['bankName'] ?? null;
        } elseif ($channelCode === 'QRIS') {
            $data['qr_code'] = $responseData['qrCode'] ?? null;
            $data['qr_content'] = $responseData['qrContent'] ?? null;
        } elseif (strpos($channelCode, 'EW_') === 0) {
            $data['deeplink'] = $responseData['deeplink'] ?? null;
            $data['checkout_url'] = $responseData['checkoutUrl'] ?? null;
        }

        return $data;
    }

    /**
     * Get channel-specific parameters
     */
    private function getChannelSpecificParams($channelCode)
    {
        $params = [];

        // Virtual Account channels
        if (strpos($channelCode, 'VA_') === 0) {
            // VA channels may need additional bank-specific parameters
            // For now, no additional params needed
        }

        // QRIS
        elseif ($channelCode === 'QRIS') {
            // QRIS may need additional parameters
            // For now, no additional params needed
        }

        // E-Wallet channels
        elseif (strpos($channelCode, 'EW_') === 0) {
            // E-wallet channels may need additional parameters
            // For now, no additional params needed
        }

        return $params;
    }

    /**
     * Test connection to Paylabs v4.8.1
     */
    public function testConnection()
    {
        try {
            // If testing mode is enabled, return dummy response
            if (config('paylabs.testing.enabled', false)) {
                Log::info('PAYLABS: Test connection in testing mode');

                return [
                    'success' => true,
                    'status_code' => 200,
                    'response' => [
                        'responseCode' => '00',
                        'responseMessage' => 'Test Mode - Success',
                        'merchantId' => $this->mid,
                        'merchantName' => 'Smart Shuttle App (Test Mode)',
                        'status' => 'ACTIVE'
                    ],
                    'config' => [
                        'base_url' => $this->baseUrl,
                        'mid' => $this->mid,
                        'callback_url' => $this->callbackUrl,
                        'testing_mode' => true
                    ]
                ];
            }

            // Test dengan membuat request sederhana
            $testData = [
                'requestType' => 'queryMerchant',
                'merchantId' => $this->mid,
            ];

            $minifiedBody = $this->minifyJson($testData);
            $timestamp = time() * 1000;
            $signature = $this->generateSignatureV481($minifiedBody, $timestamp, 'QRIS');

            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'X-TIMESTAMP' => $timestamp,
                    'X-SIGNATURE' => $signature,
                    'X-MERCHANT-ID' => $this->mid,
                ])
                ->post(rtrim($this->baseUrl, '/') . '/merchant/query', $testData);

            return [
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'response' => $response->json() ?? $response->body(),
                'config' => [
                    'base_url' => $this->baseUrl,
                    'mid' => $this->mid,
                    'callback_url' => $this->callbackUrl,
                    'endpoint_test' => rtrim($this->baseUrl, '/') . '/merchant/query',
                    'testing_mode' => false
                ]
            ];

        } catch (\Exception $e) {
            Log::error('PAYLABS Test Connection Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'config' => [
                    'base_url' => $this->baseUrl,
                    'mid' => $this->mid,
                    'testing_mode' => config('paylabs.testing.enabled', false)
                ]
            ];
        }
    }

    /**
     * Quick test for development
     */
    public function quickTest()
    {
        try {
            // Create a test payment
            $payment = new Pembayaran();
            $payment->id = 999;
            $payment->kode_pembayaran = 'TEST' . time();
            $payment->jumlah = 100000;
            $payment->pemesanan = (object) [
                'kode_booking' => 'BOOKTEST',
                'user' => (object) [
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'telepon' => '08123456789'
                ]
            ];

            $result = $this->createPayment($payment, 'QRIS', 'QRIS');

            return [
                'success' => $result['success'] ?? false,
                'test_payment' => [
                    'id' => $payment->id,
                    'kode_pembayaran' => $payment->kode_pembayaran,
                    'amount' => $payment->jumlah,
                ],
                'paylabs_result' => $result,
                'environment' => config('paylabs.environment', 'sandbox'),
                'testing_mode' => config('paylabs.testing.enabled', false)
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }
}
