<?php
// app/Services/PaylabsService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use App\Models\MetodePembayaran;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaylabsService
{
    protected $mid;
    protected $privateKey;
    protected $publicKey;
    protected $baseUrl;
    protected $callbackUrl;
    protected $returnUrl;
    protected $merchantName;
    protected $storeId;
    protected $environment;

    public function __construct()
    {
        $this->mid = config('paylabs.mid', '010529');
        $this->privateKey = config('paylabs.private_key', '');
        $this->publicKey = config('paylabs.public_key', '');
        // Pay-in v2.3 base URL defaults:
        // SIT  => https://sit-pay.paylabs.co.id
        // PROD => https://pay.paylabs.co.id
        $this->baseUrl = config('paylabs.base_url', 'https://sit-pay.paylabs.co.id');
        $this->callbackUrl = config('paylabs.callback_url', 'http://localhost:8000/api/payment/callback-v23');
        $this->returnUrl = config('paylabs.return_url', 'http://localhost:8000/customer/detail-pemesanan');
        $this->merchantName = config('paylabs.merchant_name', 'Smart Shuttle');
        $this->storeId = config('paylabs.store_id', '');
        $this->environment = config('paylabs.environment', 'sandbox');

        Log::info('PAYLABS Service initialized:', [
            'mid' => $this->mid,
            'base_url' => $this->baseUrl,
            'environment' => $this->environment,
            'testing_mode' => config('paylabs.testing.enabled', false)
        ]);
    }

    /**
     * Backward-compatible helper used by existing dev routes.
     * Note: Signature string is endpoint-path dependent in v2.3.
     */
    public function generateSignature(array $requestData, ?string $timestamp = null, string $endpointPath = '/payment/v2.3/qris/create')
    {
        $timestamp = $timestamp ?: $this->generatePaylabsTimestamp();
        return $this->generateSignatureV23($requestData, $timestamp, $endpointPath);
    }

    /**
     * Create QRIS order directly (v2.3) - useful for Postman testing.
     */
    public function qrisCreateV23(array $body)
    {
        $requestId = $body['requestId'] ?? $this->generateRequestId();
        $merchantTradeNo = $body['merchantTradeNo'] ?? ('TEST-' . now()->format('YmdHis') . '-' . Str::random(6));

        $payload = array_merge([
            'requestId' => $requestId,
            'merchantId' => $this->mid,
            'storeId' => $this->storeId ?: null,
            'paymentType' => 'QRIS',
            'amount' => isset($body['amount']) ? number_format((float) $body['amount'], 2, '.', '') : '0.00',
            'merchantTradeNo' => $merchantTradeNo,
            'notifyUrl' => $body['notifyUrl'] ?? $this->callbackUrl,
            'feeType' => $body['feeType'] ?? (config('paylabs.qris.fee_type', 'BEN') ?: 'BEN'),
            'productName' => $body['productName'] ?? 'Smart Shuttle Ticket',
        ], $body);

        // Remove null storeId to avoid confusing Paylabs API
        if (empty($payload['storeId'])) {
            unset($payload['storeId']);
        }

        return $this->requestV23('/payment/v2.3/qris/create', $payload, $requestId);
    }

    /**
     * Query QRIS order status directly (v2.3).
     */
    public function qrisQueryV23(array $body)
    {
        $requestId = $body['requestId'] ?? $this->generateRequestId();

        $payload = array_merge([
            'requestId' => $requestId,
            'merchantId' => $this->mid,
            'paymentType' => 'QRIS',
        ], $body);

        return $this->requestV23('/payment/v2.3/qris/query', $payload, $requestId);
    }

    /**
     * Cancel QRIS order directly (v2.3).
     */
    public function qrisCancelV23(array $body)
    {
        $requestId = $body['requestId'] ?? $this->generateRequestId();

        $payload = array_merge([
            'requestId' => $requestId,
            'merchantId' => $this->mid,
            'paymentType' => 'QRIS',
        ], $body);

        return $this->requestV23('/payment/v2.3/qris/cancel', $payload, $requestId);
    }

    /**
     * Create Virtual Account order directly (v2.3) - useful for Postman testing.
     */
    public function vaCreateV23(array $body)
    {
        $requestId = $body['requestId'] ?? $this->generateRequestId();
        $merchantTradeNo = $body['merchantTradeNo'] ?? ('VA-TEST-' . now()->format('YmdHis') . '-' . Str::random(6));

        $payload = array_merge([
            'requestId' => $requestId,
            'merchantId' => $this->mid,
            'storeId' => $this->storeId ?: null,
            'amount' => isset($body['amount']) ? number_format((float) $body['amount'], 2, '.', '') : '0.00',
            'merchantTradeNo' => $merchantTradeNo,
            'notifyUrl' => $body['notifyUrl'] ?? $this->callbackUrl,
            'feeType' => $body['feeType'] ?? (config('paylabs.va.fee_type', 'BEN') ?: 'BEN'),
            'productName' => $body['productName'] ?? 'Smart Shuttle Ticket',
            'productInfo' => $body['productInfo'] ?? [],
            'payer' => $body['payer'] ?? 'Customer',
        ], $body);

        // Remove null storeId to avoid confusing Paylabs API
        if (empty($payload['storeId'])) {
            unset($payload['storeId']);
        }

        return $this->requestV23('/payment/v2.3/va/create', $payload, $requestId);
    }

    /**
     * Query Virtual Account status (v2.3)
     */
    public function vaQueryV23(array $body)
    {
        $requestId = $body['requestId'] ?? $this->generateRequestId();

        $payload = array_merge([
            'requestId' => $requestId,
            'merchantId' => $this->mid,
            'paymentType' => $body['paymentType'] ?? 'VA',
        ], $body);

        return $this->requestV23('/payment/v2.3/va/query', $payload, $requestId);
    }

    /**
     * Create payment request
     */
    public function createPayment(Pembayaran $payment, $channelCode, $channelName)
    {
        try {
            Log::info('PAYLABS: Creating payment', [
                'payment_id' => $payment->id,
                'kode_pembayaran' => $payment->kode_pembayaran,
                'amount' => $payment->jumlah,
                'channel_code' => $channelCode,
                'channel_name' => $channelName,
                'mid' => $this->mid
            ]);

            // Jika testing mode aktif, langsung return dummy
            if (config('paylabs.testing.enabled', false)) {
                Log::info('PAYLABS: Using testing mode');
                return $this->createTestPayment($payment, $channelCode, $channelName);
            }

            // Pilih metode berdasarkan channel code
            if ($channelCode === 'QRIS') {
                return $this->createQRISPayment($payment, $channelCode, $channelName);
            } elseif (strpos($channelCode, 'VA_') === 0) {
                return $this->createVirtualAccountPayment($payment, $channelCode, $channelName);
            } elseif (strpos($channelCode, 'EW_') === 0) {
                return $this->createEWalletPayment($payment, $channelCode, $channelName);
            } else {
                throw new \Exception("Unsupported payment channel: {$channelCode}");
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
     * Create QRIS payment (v2.3)
     */
    private function createQRISPayment(Pembayaran $payment, $channelCode, $channelName)
    {
        try {
            // Generate request ID
            $requestId = $this->generateRequestId();

            // Prepare product info
            $productInfo = $this->prepareProductInfo($payment);

            // Prepare request data sesuai spesifikasi v2.3
            $requestData = [
                'requestId' => $requestId,
                'merchantId' => $this->mid,
                'storeId' => $this->storeId,
                'paymentType' => 'QRIS',
                'amount' => number_format((float) $payment->jumlah, 2, '.', ''),
                'merchantTradeNo' => $payment->kode_pembayaran,
                'notifyUrl' => $this->callbackUrl,
                'feeType' => 'BEN', // BEN: Merchant, OUR: Customer
                'productName' => 'Smart Shuttle Ticket',
                'productInfo' => $productInfo
            ];

            // Tambahkan expire time jika ada waktu kadaluarsa
            if ($payment->waktu_kadaluarsa) {
                $expireSeconds = Carbon::now()->diffInSeconds($payment->waktu_kadaluarsa);
                if ($expireSeconds > 0) {
                    $requestData['expire'] = $expireSeconds;
                }
            }

            Log::info('PAYLABS v2.3 QRIS Request Data:', $requestData);

            // Generate timestamp
            $timestamp = $this->generatePaylabsTimestamp();

            // Generate signature
            $signature = $this->generateSignatureV23($requestData, $timestamp, '/payment/v2.3/qris/create');

            // Build endpoint URL
            $endpointUrl = rtrim($this->baseUrl, '/') . '/payment/v2.3/qris/create';

            Log::info('PAYLABS v2.3 QRIS API Call:', [
                'url' => $endpointUrl,
                'timestamp' => $timestamp,
                'request_id' => $requestId
            ]);

            // Make HTTP request
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json;charset=utf-8',
                    'Accept' => 'application/json',
                    'X-TIMESTAMP' => $timestamp,
                    'X-SIGNATURE' => $signature,
                    'X-PARTNER-ID' => $this->mid,
                    'X-REQUEST-ID' => $requestId,
                ])
                ->post($endpointUrl, $requestData);

            $statusCode = $response->status();
            $responseBody = (string) $response->body();

            Log::info('PAYLABS v2.3 QRIS Response:', [
                'status' => $statusCode,
                'body' => $responseBody
            ]);

            if (!$response->successful()) {
                $errorMsg = "HTTP {$statusCode}: " . substr($responseBody, 0, 200);
                throw new \Exception($errorMsg);
            }

            $responseData = json_decode($responseBody, true);

            // Process response
            return $this->processQRISResponse($payment, $responseData, $requestId);

        } catch (\Exception $e) {
            Log::error('PAYLABS v2.3 Create QRIS Error:', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Create Virtual Account payment
     */
    private function createVirtualAccountPayment(Pembayaran $payment, $channelCode, $channelName)
    {
        try {
            // Generate request ID
            $requestId = $this->generateRequestId();

            // Extract bank code from channel code (VA_BCA -> BCA)
            $bankCode = str_replace('VA_', '', $channelCode);

            // Map kode bank ke format Paylabs
            $paylabsBankMap = [
                'BCA' => 'BCAVA',
                'MANDIRI' => 'MandiriVA',
                'BNI' => 'BNIVA',
                'BRI' => 'BRIVA',
                'PERMATA' => 'PermataVA',
                'CIMB' => 'CIMBVA',
                'DANAMON' => 'DanamonVA',
                'MAYBANK' => 'MaybankVA',
                'BTN' => 'BTNVA',
                'SINARMAS' => 'SinarmasVA',
                'BJB' => 'BJBVA',
                'BTPN' => 'BTPNVA',
                'OCBC' => 'OCBCVA',
            ];

            $paylabsPaymentType = $paylabsBankMap[$bankCode] ?? $bankCode . 'VA';

            // Prepare product info
            $productInfo = $this->prepareProductInfo($payment);

            // Prepare request data sesuai dokumentasi Paylabs v2.3 VA
            $requestData = [
                'requestId' => $requestId,
                'merchantId' => $this->mid,
                'storeId' => $this->storeId ?: null,
                'paymentType' => $paylabsPaymentType,
                'amount' => number_format((float) $payment->jumlah, 2, '.', ''),
                'merchantTradeNo' => $payment->kode_pembayaran,
                'notifyUrl' => $this->callbackUrl,
                'feeType' => 'BEN', // BEN: Merchant, OUR: Customer
                'productName' => 'Smart Shuttle Ticket',
                'productInfo' => $productInfo,
                'payer' => $payment->pemesanan->nama_pemesan ?? 'Customer',
            ];

            // Remove null storeId
            if (empty($requestData['storeId'])) {
                unset($requestData['storeId']);
            }

            // Tambahkan expire time
            if ($payment->waktu_kadaluarsa) {
                $expireSeconds = Carbon::now()->diffInSeconds($payment->waktu_kadaluarsa);
                if ($expireSeconds > 0) {
                    $requestData['expire'] = $expireSeconds;
                }
            }

            Log::info('PAYLABS Virtual Account Request Data:', $requestData);

            // Panggil requestV23 untuk konsistensi
            $apiResult = $this->requestV23('/payment/v2.3/va/create', $requestData, $requestId);

            // Periksa hasil request
            if (!$apiResult['success'] || $apiResult['http_status'] !== 200) {
                // Jika tidak sukses, lempar exception dengan pesan dari response
                $errorMsg = $apiResult['response']['errCodeDes'] ?? 'Unknown error from Paylabs';
                throw new \Exception("Paylabs Error: " . $errorMsg);
            }

            // Process response
            return $this->processVirtualAccountResponse($payment, $apiResult['response'], $requestId, $bankCode, $channelName);

        } catch (\Exception $e) {
            Log::error('PAYLABS Create Virtual Account Error:', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Create E-Wallet payment
     */
    private function createEWalletPayment(Pembayaran $payment, $channelCode, $channelName)
    {
        try {
            // Generate request ID
            $requestId = $this->generateRequestId();

            // Extract e-wallet type from channel code (EW_DANA -> DANA)
            $walletType = str_replace('EW_', '', $channelCode);

            // Prepare product info
            $productInfo = $this->prepareProductInfo($payment);

            // Prepare request data untuk E-Wallet
            $requestData = [
                'requestId' => $requestId,
                'merchantId' => $this->mid,
                'storeId' => $this->storeId,
                'paymentType' => 'E_WALLET',
                'amount' => number_format((float) $payment->jumlah, 2, '.', ''),
                'merchantTradeNo' => $payment->kode_pembayaran,
                'notifyUrl' => $this->callbackUrl,
                'returnUrl' => $this->returnUrl,
                'feeType' => 'BEN',
                'productName' => 'Smart Shuttle Ticket',
                'productInfo' => $productInfo,
                'walletType' => $walletType,
                'customerInfo' => [
                    'name' => $payment->pemesanan->nama_pemesan ?? 'Customer',
                    'email' => $payment->pemesanan->email_pemesan ?? 'customer@example.com',
                    'phone' => $payment->pemesanan->telepon_pemesan ?? '08123456789'
                ]
            ];

            // Tambahkan expire time
            if ($payment->waktu_kadaluarsa) {
                $expireSeconds = Carbon::now()->diffInSeconds($payment->waktu_kadaluarsa);
                if ($expireSeconds > 0) {
                    $requestData['expire'] = $expireSeconds;
                }
            }

            Log::info('PAYLABS E-Wallet Request Data:', $requestData);

            // Generate timestamp
            $timestamp = $this->generatePaylabsTimestamp();

            // Generate signature
            $signature = $this->generateSignatureV23($requestData, $timestamp, '/payment/v2.3/e-wallet/create');

            // Build endpoint URL
            $endpointUrl = rtrim($this->baseUrl, '/') . '/payment/v2.3/e-wallet/create';

            Log::info('PAYLABS E-Wallet API Call:', [
                'url' => $endpointUrl,
                'timestamp' => $timestamp,
                'request_id' => $requestId
            ]);

            // Make HTTP request
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json;charset=utf-8',
                    'Accept' => 'application/json',
                    'X-TIMESTAMP' => $timestamp,
                    'X-SIGNATURE' => $signature,
                    'X-PARTNER-ID' => $this->mid,
                    'X-REQUEST-ID' => $requestId,
                ])
                ->post($endpointUrl, $requestData);

            $statusCode = $response->status();
            $responseBody = (string) $response->body();

            Log::info('PAYLABS E-Wallet Response:', [
                'status' => $statusCode,
                'body' => $responseBody
            ]);

            if (!$response->successful()) {
                $errorMsg = "HTTP {$statusCode}: " . substr($responseBody, 0, 200);
                throw new \Exception($errorMsg);
            }

            $responseData = json_decode($responseBody, true);

            // Process response
            return $this->processEWalletResponse($payment, $responseData, $requestId, $walletType);

        } catch (\Exception $e) {
            Log::error('PAYLABS Create E-Wallet Error:', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Process QRIS response
     */
    private function processQRISResponse($payment, $responseData, $requestId)
    {
        if (isset($responseData['errCode']) && $responseData['errCode'] === '0') {
            // Map QRIS status to Paylabs status
            $paylabsStatus = $this->mapQRISStatus($responseData['status'] ?? '01');
            $localStatus = $this->mapPaylabsStatusToLocal($paylabsStatus);

            // Update payment with response data
            $updateData = [
                'paylabs_request_id' => $requestId,
                'paylabs_transaction_id' => $responseData['platformTradeNo'] ?? null,
                'paylabs_status' => $paylabsStatus,
                'status' => $localStatus,
                'paylabs_response' => json_encode($responseData),
                'paylabs_raw_response' => json_encode($responseData),
                'qr_code' => $responseData['qrCode'] ?? null,
                'qris_url' => $responseData['qrisUrl'] ?? null,
                'nmid' => $responseData['nmid'] ?? null,
                'platform_trade_no' => $responseData['platformTradeNo'] ?? null,
                'tid' => $responseData['tid'] ?? null,
                'rrn' => $responseData['rrn'] ?? null,
                'payer_name' => $responseData['payer'] ?? null,
                'payer_phone' => $responseData['phoneNumber'] ?? null,
                'issuer_id' => $responseData['issuerId'] ?? null,
                'trans_fee_rate' => $responseData['transFeeRate'] ?? null,
                'trans_fee_amount' => $responseData['transFeeAmount'] ?? null,
                'total_trans_fee' => $responseData['totalTransFee'] ?? null,
                'vat_fee' => $responseData['vatFee'] ?? null,
                'account_no' => $responseData['accountNo'] ?? null,
                'create_time' => $responseData['createTime'] ?? null,
                'expired_time' => $responseData['expiredTime'] ?? null,
                'updated_at' => now(),
            ];

            $payment->update($updateData);

            return [
                'success' => true,
                'transaction_id' => $responseData['platformTradeNo'] ?? $requestId,
                'payment_data' => $responseData,
                'is_test_mode' => false,
            ];
        } else {
            $errorCode = $responseData['errCode'] ?? 'UNKNOWN';
            $errorMessage = $responseData['errCodeDes'] ?? 'Unknown error from Paylabs';
            throw new \Exception("Paylabs Error {$errorCode}: {$errorMessage}");
        }
    }

    /**
     * Process Virtual Account response
     */
    private function processVirtualAccountResponse($payment, $responseData, $requestId, $bankCode, $channelName)
    {
        if (isset($responseData['errCode']) && $responseData['errCode'] === '0') {
            // Map status - VA menggunakan status 01, 02, 09 (bukan string)
            $statusCode = $responseData['status'] ?? '01';
            $paylabsStatus = $this->mapVAStatusToPaylabs($statusCode);
            $localStatus = $this->mapPaylabsStatusToLocal($paylabsStatus);

            // Update payment with response data
            $updateData = [
                'paylabs_request_id' => $requestId,
                'paylabs_transaction_id' => $responseData['platformTradeNo'] ?? null,
                'paylabs_status' => $paylabsStatus,
                'status' => $localStatus,
                'paylabs_response' => json_encode($responseData),
                'paylabs_raw_response' => json_encode($responseData),
                'no_virtual_account' => $responseData['vaCode'] ?? $responseData['vaNumber'] ?? null,
                'nama_bank' => $channelName,
                'platform_trade_no' => $responseData['platformTradeNo'] ?? null,
                'create_time' => $responseData['createTime'] ?? null,
                'expired_time' => $responseData['expiredTime'] ?? null,
                'trans_fee_rate' => $responseData['transFeeRate'] ?? null,
                'trans_fee_amount' => $responseData['transFeeAmount'] ?? null,
                'total_trans_fee' => $responseData['totalTransFee'] ?? null,
                'vat_fee' => $responseData['vatFee'] ?? null,
                'fee_type' => $responseData['feeType'] ?? null,
                'payer_name' => $responseData['payer'] ?? null,
                'account_no' => $responseData['accountNo'] ?? null,
                'updated_at' => now(),
            ];

            $payment->update($updateData);

            return [
                'success' => true,
                'transaction_id' => $responseData['platformTradeNo'] ?? $requestId,
                'payment_data' => $responseData,
                'is_test_mode' => false,
            ];
        } else {
            $errorCode = $responseData['errCode'] ?? 'UNKNOWN';
            $errorMessage = $responseData['errCodeDes'] ?? 'Unknown error from Paylabs';
            throw new \Exception("Paylabs Error {$errorCode}: {$errorMessage}");
        }
    }

    /**
     * Map VA status code to Paylabs status
     */
    private function mapVAStatusToPaylabs($statusCode)
    {
        $mapping = [
            '01' => 'PENDING',
            '02' => 'PAID',
            '09' => 'FAILED',
        ];

        return $mapping[$statusCode] ?? 'PENDING';
    }

    /**
     * Process E-Wallet response
     */
    private function processEWalletResponse($payment, $responseData, $requestId, $walletType)
    {
        if (isset($responseData['errCode']) && $responseData['errCode'] === '0') {
            // Map status
            $paylabsStatus = $responseData['status'] ?? 'PENDING';
            $localStatus = $this->mapPaylabsStatusToLocal($paylabsStatus);

            // Update payment with response data
            $updateData = [
                'paylabs_request_id' => $requestId,
                'paylabs_transaction_id' => $responseData['platformTradeNo'] ?? null,
                'paylabs_status' => $paylabsStatus,
                'status' => $localStatus,
                'paylabs_response' => json_encode($responseData),
                'paylabs_raw_response' => json_encode($responseData),
                'platform_trade_no' => $responseData['platformTradeNo'] ?? null,
                'create_time' => $responseData['createTime'] ?? null,
                'expired_time' => $responseData['expiredTime'] ?? null,
                'checkout_url' => $responseData['checkoutUrl'] ?? null,
                'deeplink' => $responseData['deeplink'] ?? null,
                'updated_at' => now(),
            ];

            $payment->update($updateData);

            return [
                'success' => true,
                'transaction_id' => $responseData['platformTradeNo'] ?? $requestId,
                'payment_data' => $responseData,
                'is_test_mode' => false,
            ];
        } else {
            $errorCode = $responseData['errCode'] ?? 'UNKNOWN';
            $errorMessage = $responseData['errCodeDes'] ?? 'Unknown error from Paylabs';
            throw new \Exception("Paylabs Error {$errorCode}: {$errorMessage}");
        }
    }

    /**
     * Generate signature for Paylabs v2.3 - PERBAIKAN BESAR
     */
    private function generateSignatureV23($requestData, $timestamp, $endpointPath)
    {
        try {
            // Log request data untuk debugging
            Log::debug('PAYLABS v2.3 Signature Input:', [
                'request_data' => $requestData,
                'timestamp' => $timestamp,
                'endpoint_path' => $endpointPath
            ]);

            // 1. Convert array ke JSON string TANPA whitespace - penting untuk consistency
            $jsonString = json_encode($requestData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            // 2. Hash body dengan SHA256 (harus lowercase)
            $bodyHash = hash('sha256', $jsonString);

            // 3. Buat string untuk signature (FORMAT: POST:{endpointPath}:{bodyHash}:{timestamp})
            $signatureString = "POST:{$endpointPath}:{$bodyHash}:{$timestamp}";

            Log::debug('PAYLABS v2.3 Signature Components:', [
                'json_string' => $jsonString,
                'body_hash' => $bodyHash,
                'signature_string' => $signatureString,
                'timestamp' => $timestamp
            ]);

            // 4. Load private key dengan method yang sudah diperbaiki
            $privateKey = $this->loadPrivateKey();
            if (!$privateKey) {
                throw new \Exception('Failed to load private key');
            }

            // 5. Sign dengan RSA-SHA256
            $signature = '';
            $result = openssl_sign($signatureString, $signature, $privateKey, OPENSSL_ALGO_SHA256);

            if (!$result) {
                $error = openssl_error_string();
                openssl_free_key($privateKey);
                throw new \Exception('OpenSSL sign failed: ' . $error);
            }

            openssl_free_key($privateKey);

            // 6. Return base64 encoded signature
            $encodedSignature = base64_encode($signature);

            Log::debug('PAYLABS v2.3 Generated Signature:', [
                'signature_base64' => $encodedSignature,
                'signature_length' => strlen($encodedSignature),
                'signature_preview' => substr($encodedSignature, 0, 50) . '...'
            ]);

            return $encodedSignature;

        } catch (\Exception $e) {
            Log::error('PAYLABS v2.3 Signature Generation Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Verify signature for callback v2.3
     */
    public function verifySignatureV23($data, $signature, $timestamp, $endpointPath = null)
    {
        try {
            if (!$endpointPath) {
                // Backward compatible default (caller should pass the actual path)
                $endpointPath = '/';
            }

            if (is_string($data)) {
                $bodyHash = hash('sha256', $data);
            } else {
                // Minify JSON (without whitespace)
                $minifiedBody = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                $bodyHash = hash('sha256', $minifiedBody);
            }

            // Create signature string
            $signatureString = "POST:{$endpointPath}:{$bodyHash}:{$timestamp}";

            Log::debug('PAYLABS v2.3 Signature Verification String:', ['string' => $signatureString]);

            // Load public key
            $publicKey = $this->loadPublicKey();

            if (!$publicKey) {
                throw new \Exception('Invalid public key format: ' . openssl_error_string());
            }

            // Verify signature
            $result = openssl_verify($signatureString, base64_decode($signature), $publicKey, OPENSSL_ALGO_SHA256);
            openssl_free_key($publicKey);

            return $result === 1;

        } catch (\Exception $e) {
            Log::error('PAYLABS v2.3 Signature Verification Error:', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return false;
        }
    }

    /**
     * Generate timestamp untuk Paylabs v2.3 dengan format yang benar
     * Format: YYYY-MM-DDTHH:mm:ss.SSS±HH:mm (contoh: 2026-01-19T02:08:06.897+00:00)
     */
    private function generatePaylabsTimestamp()
    {
        $carbon = Carbon::now();

        // Format dasar
        $timestamp = $carbon->format('Y-m-d\TH:i:s');

        // Millisecond (3 digit)
        $milliseconds = $carbon->format('v'); // v = millisecond (001-999)

        // Timezone dengan colon
        $timezone = $carbon->format('P'); // P = +00:00

        // Gabungkan semua komponen
        $paylabsTimestamp = $timestamp . '.' . $milliseconds . $timezone;

        Log::debug('PAYLABS Generated Timestamp:', [
            'timestamp' => $paylabsTimestamp,
            'components' => [
                'date_time' => $timestamp,
                'milliseconds' => $milliseconds,
                'timezone' => $timezone
            ]
        ]);

        return $paylabsTimestamp;
    }

    /**
     * Request helper untuk Paylabs v2.3 API - DIPERBAIKI
     */
    private function requestV23(string $endpointPath, array $requestData, string $requestId)
    {
        try {
            // Generate timestamp dengan format yang benar
            $timestamp = $this->generatePaylabsTimestamp();

            // Generate signature
            $signature = $this->generateSignatureV23($requestData, $timestamp, $endpointPath);

            $endpointUrl = rtrim($this->baseUrl, '/') . $endpointPath;

            Log::info('PAYLABS v2.3 API Request Details:', [
                'url' => $endpointUrl,
                'path' => $endpointPath,
                'timestamp' => $timestamp,
                'request_id' => $requestId,
                'signature_preview' => substr($signature, 0, 30) . '...',
                'request_data' => $requestData
            ]);

            // Make HTTP request
            $response = Http::timeout((int) config('paylabs.timeout', 30))
                ->withHeaders([
                    'Content-Type' => 'application/json;charset=utf-8',
                    'Accept' => 'application/json',
                    'X-TIMESTAMP' => $timestamp,
                    'X-SIGNATURE' => $signature,
                    'X-PARTNER-ID' => $this->mid,
                    'X-REQUEST-ID' => $requestId,
                ])
                ->post($endpointUrl, $requestData);

            $statusCode = $response->status();
            $responseBody = (string) $response->body();

            Log::info('PAYLABS v2.3 API Response:', [
                'status' => $statusCode,
                'body' => $responseBody,
                'request_id' => $requestId
            ]);

            return [
                'http_status' => $statusCode,
                'success' => $response->successful(),
                'request' => [
                    'url' => $endpointUrl,
                    'path' => $endpointPath,
                    'timestamp' => $timestamp,
                    'requestId' => $requestId,
                ],
                'response' => $response->json() ?? ['raw' => $responseBody],
            ];

        } catch (\Exception $e) {
            Log::error('PAYLABS v2.3 Request Error:', [
                'error' => $e->getMessage(),
                'endpoint' => $endpointPath,
                'request_id' => $requestId,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'http_status' => 500,
                'success' => false,
                'error' => $e->getMessage(),
                'request' => [
                    'url' => rtrim($this->baseUrl, '/') . $endpointPath,
                    'path' => $endpointPath,
                    'requestId' => $requestId,
                ],
                'response' => ['errCode' => 'REQUEST_ERROR', 'errCodeDes' => $e->getMessage()],
            ];
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus($merchantTradeNo, $platformTradeNo = null)
    {
        try {
            $requestId = $this->generateRequestId();

            // Prepare request data
            $requestData = [
                'requestId' => $requestId,
                'merchantId' => $this->mid,
                'merchantTradeNo' => $merchantTradeNo,
            ];

            if ($platformTradeNo) {
                $requestData['platformTradeNo'] = $platformTradeNo;
            }

            // Cari payment untuk menentukan tipe
            $pembayaran = Pembayaran::where('kode_pembayaran', $merchantTradeNo)->first();
            if ($pembayaran) {
                $method = MetodePembayaran::where('kode', $pembayaran->metode)->first();
                if ($method && strpos($method->paylabs_channel_code ?? '', 'VA_') === 0) {
                    // Ini adalah VA, gunakan endpoint VA query
                    return $this->vaQueryV23($requestData);
                }
            }

            // Default ke order query
            return $this->requestV23('/payment/v2.3/order/query', $requestData, $requestId);

        } catch (\Exception $e) {
            Log::error('PAYLABS Check Status Error:', [
                'error' => $e->getMessage(),
                'merchantTradeNo' => $merchantTradeNo,
                'platformTradeNo' => $platformTradeNo
            ]);

            return [
                'success' => false,
                'status' => 'UNKNOWN',
                'merchantTradeNo' => $merchantTradeNo,
                'platformTradeNo' => $platformTradeNo,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate test payment (for testing mode)
     */
    private function createTestPayment(Pembayaran $payment, $channelCode, $channelName)
    {
        $requestId = 'TEST' . time() . rand(1000, 9999);
        $platformTradeNo = 'PLT' . time() . rand(1000, 9999);

        $testResponse = [
            'errCode' => '0',
            'errCodeDes' => 'Success (Test Mode)',
            'requestId' => $requestId,
            'merchantId' => $this->mid,
            'paymentType' => $channelCode,
            'amount' => (string) intval($payment->jumlah),
            'merchantTradeNo' => $payment->kode_pembayaran,
            'platformTradeNo' => $platformTradeNo,
            'createTime' => date('YmdHis'),
            'expiredTime' => date('YmdHis', strtotime('+30 minutes')),
            'status' => 'PENDING',
            'productName' => 'Smart Shuttle Ticket',
        ];

        // Tambahkan data spesifik berdasarkan channel
        if ($channelCode === 'QRIS') {
            $testResponse['qrCode'] = '00020101021126650013ID.CO.QRIS.WWW0118936009110020721986020215SMART SHUTTLE0303UMI51440014ID.CO.QRIS.WWW0215SMART SHUTTLE0303UMI5204581253033605802ID5912SmartShuttle6007Jakarta61051064062070703A016304';
            $testResponse['qrisUrl'] = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode('SMARTSHUTTLE|' . $payment->kode_pembayaran);
            $testResponse['nmid'] = 'ID123456789012345';
            $testResponse['status'] = '01'; // Pending for QRIS
        }

        if (strpos($channelCode, 'VA_') === 0) {
            $bankCode = str_replace('VA_', '', $channelCode);
            $testResponse['vaNumber'] = '888' . rand(100000000, 999999999);
            $testResponse['bankName'] = $bankCode;
        }

        if (strpos($channelCode, 'EW_') === 0) {
            $testResponse['checkoutUrl'] = 'https://sandbox.paylabs.co.id/payment/checkout/' . $platformTradeNo;
            $testResponse['deeplink'] = 'dana://payment?orderId=' . $platformTradeNo;
        }

        // Map status
        $paylabsStatus = $channelCode === 'QRIS' ? $this->mapQRISStatus($testResponse['status']) : $testResponse['status'];
        $localStatus = $this->mapPaylabsStatusToLocal($paylabsStatus);

        // Update payment data
        $updateData = [
            'paylabs_request_id' => $requestId,
            'paylabs_transaction_id' => $platformTradeNo,
            'paylabs_status' => $paylabsStatus,
            'status' => $localStatus,
            'paylabs_response' => json_encode($testResponse),
            'paylabs_raw_response' => json_encode($testResponse),
            'platform_trade_no' => $platformTradeNo,
            'create_time' => $testResponse['createTime'],
            'expired_time' => $testResponse['expiredTime'],
            'updated_at' => now(),
        ];

        if (isset($testResponse['qrCode'])) {
            $updateData['qr_code'] = $testResponse['qrCode'];
            $updateData['qris_url'] = $testResponse['qrisUrl'];
            $updateData['nmid'] = $testResponse['nmid'];
        }

        if (isset($testResponse['vaNumber'])) {
            $updateData['no_virtual_account'] = $testResponse['vaNumber'];
            $updateData['nama_bank'] = $testResponse['bankName'];
        }

        if (isset($testResponse['checkoutUrl'])) {
            $updateData['checkout_url'] = $testResponse['checkoutUrl'];
        }

        if (isset($testResponse['deeplink'])) {
            $updateData['deeplink'] = $testResponse['deeplink'];
        }

        $payment->update($updateData);

        return [
            'success' => true,
            'transaction_id' => $platformTradeNo,
            'payment_data' => $testResponse,
            'is_test_mode' => true,
        ];
    }

    /**
     * Helper methods
     */
    private function generateRequestId()
    {
        return date('YmdHis') . rand(1000, 9999);
    }

    private function prepareProductInfo($payment)
    {
        $productInfo = [
            [
                'id' => 'TICKET001',
                'name' => 'Smart Shuttle Ticket',
                'price' => number_format((float) $payment->jumlah, 2, '.', ''),
                'type' => 'Ticket',
                'url' => url('/customer/detail-pemesanan/' . ($payment->pemesanan->kode_booking ?? '')),
                'quantity' => $payment->pemesanan->jumlah_penumpang ?? 1
            ]
        ];

        // Tambahkan detail rute jika tersedia
        if ($payment->pemesanan && $payment->pemesanan->jadwal) {
            $rutePertama = $payment->pemesanan->jadwal->rutes->first();
            $ruteTerakhir = $payment->pemesanan->jadwal->rutes->last();

            if ($rutePertama && $ruteTerakhir) {
                $productInfo[0]['name'] = 'Ticket: ' . $rutePertama->kota_asal . ' to ' . $ruteTerakhir->kota_tujuan;
            }
        }

        return $productInfo;
    }

    private function mapQRISStatus($qrisStatus)
    {
        $mapping = [
            '01' => 'PENDING',
            '02' => 'PAID',
            '09' => 'FAILED'
        ];

        return $mapping[$qrisStatus] ?? $qrisStatus;
    }

    private function mapPaylabsStatusToLocal($paylabsStatus)
    {
        $mapping = [
            'PENDING' => 'menunggu',
            'PROCESSING' => 'diproses',
            'PAID' => 'berhasil',
            'EXPIRED' => 'kadaluarsa',
            'FAILED' => 'gagal',
            'CANCELLED' => 'dibatalkan',
            'REFUNDED' => 'dikembalikan',
            '01' => 'menunggu', // QRIS Pending
            '02' => 'berhasil', // QRIS Success
            '09' => 'gagal', // QRIS Failed
        ];

        return $mapping[$paylabsStatus] ?? 'menunggu';
    }

    private function loadPrivateKey()
    {
        try {
            // Ambil private key dari config - prefer inline key; fall back to file
            $rawKey = $this->privateKey;

            if (empty(trim($rawKey))) {
                // Try to load from file if env key is empty
                $keyFile = config('paylabs.private_key_file');
                if ($keyFile && file_exists($keyFile)) {
                    $rawKey = file_get_contents($keyFile);
                    Log::debug('PAYLABS: Loading private key from file', [
                        'file' => $keyFile
                    ]);
                } else {
                    throw new \Exception('PAYLABS: Private key is empty and file not found at ' . ($keyFile ?? 'not configured'));
                }
            }

            Log::debug('PAYLABS Raw Private Key (first 100 chars):', [
                'key_preview' => substr($rawKey, 0, 100)
            ]);

            // Clean key - handle escaped newlines
            $rawKey = trim($rawKey);
            $rawKey = str_replace('\\n', "\n", $rawKey);

            // Jika key sudah dalam format PEM
            if (strpos($rawKey, '-----BEGIN') !== false) {
                $privateKey = openssl_pkey_get_private($rawKey);
                if ($privateKey) {
                    Log::debug('PAYLABS: Private key loaded successfully (PEM format)');
                    return $privateKey;
                }
            }

            // Jika key adalah base64 string
            $base64Key = preg_replace('/\s+/', '', $rawKey);

            // Coba format PKCS#8
            $pemKey = "-----BEGIN PRIVATE KEY-----\n" .
                     chunk_split($base64Key, 64, "\n") .
                     "-----END PRIVATE KEY-----";

            $privateKey = openssl_pkey_get_private($pemKey);
            if ($privateKey) {
                Log::debug('PAYLABS: Private key loaded successfully (PKCS#8)');
                return $privateKey;
            }

            // Coba format PKCS#1
            $pemKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
                     chunk_split($base64Key, 64, "\n") .
                     "-----END RSA PRIVATE KEY-----";

            $privateKey = openssl_pkey_get_private($pemKey);
            if ($privateKey) {
                Log::debug('PAYLABS: Private key loaded successfully (PKCS#1)');
                return $privateKey;
            }

            throw new \Exception('Failed to parse private key in any format. Key starts with: ' . substr($rawKey, 0, 50));

        } catch (\Exception $e) {
            Log::error('PAYLABS Load Private Key Error:', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function loadPublicKey()
    {
        // Prefer inline key from config/.env; fall back to file if empty.
        $rawPublic = $this->publicKey;
        if (empty(trim((string) $rawPublic))) {
            $keyFile = config('paylabs.public_key_file');
            if ($keyFile && file_exists($keyFile)) {
                $rawPublic = file_get_contents($keyFile);
            }
        }

        $rawPublic = trim($rawPublic);
        $rawPublic = trim($rawPublic, "\"'\n\r\t ");
        if (strpos($rawPublic, '\\n') !== false) {
            $rawPublic = str_replace('\\n', "\n", $rawPublic);
        }

        if (empty($rawPublic)) {
            throw new \Exception('PAYLABS: Public key is empty. Set PUBLIC_KEY or PAYLABS_PUBLIC_KEY in .env');
        }

        $candidates = [];
        if (strpos($rawPublic, '-----BEGIN') !== false) {
            $candidates[] = $rawPublic;
        } else {
            $base64 = preg_replace('/\s+/', '', $rawPublic);
            $candidates[] = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($base64, 64, "\n") . "-----END PUBLIC KEY-----";
            $candidates[] = "-----BEGIN RSA PUBLIC KEY-----\n" . chunk_split($base64, 64, "\n") . "-----END RSA PUBLIC KEY-----";
        }

        $lastError = null;
        foreach ($candidates as $pem) {
            $publicKey = openssl_pkey_get_public($pem);
            if ($publicKey) {
                return $publicKey;
            }
            $lastError = openssl_error_string() ?: $lastError;
        }

        $preview = substr(preg_replace('/\s+/', '', $rawPublic), 0, 16);
        throw new \Exception('Invalid public key format: ' . ($lastError ?: 'unknown openssl error') . ' (key starts with: ' . $preview . '...)');
    }

    /**
     * Test connection to Paylabs
     */
    public function testConnection()
    {
        return [
            'success' => false,
            'message' => 'Paylabs does not support merchant connection test. Use QRIS/VA create instead.'
        ];
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
            $payment->waktu_kadaluarsa = Carbon::now()->addMinutes(30);
            $payment->pemesanan = (object) [
                'kode_booking' => 'BOOKTEST',
                'jumlah_penumpang' => 1,
                'nama_pemesan' => 'Test User',
                'email_pemesan' => 'test@example.com',
                'telepon_pemesan' => '08123456789',
                'jadwal' => (object) [
                    'rutes' => collect([
                        (object) ['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung']
                    ])
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
                'environment' => $this->environment,
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

    /**
     * Real API test - Creates actual payment with Paylabs sandbox
     * This verifies true integration by receiving authentic responses
     */
    public function realApiTest($paymentMethod = 'QRIS', $channelCode = 'QRIS', $channelName = 'QRIS')
    {
        try {
            // Ensure we're not in testing mode for real API testing
            if (config('paylabs.testing.enabled', false)) {
                throw new \Exception('Cannot run real API test while testing mode is enabled. Set PAYLABS_TESTING=false in .env');
            }

            // Create a temporary test payment record
            $payment = new Pembayaran();
            $payment->id = 'REAL_TEST_' . time() . '_' . rand(1000, 9999);
            $payment->kode_pembayaran = 'REALTEST-' . time() . '-' . Str::random(6);
            $payment->jumlah = config('paylabs.real_api_testing.test_amount', 10000); // Small test amount
            $payment->metode = strtolower($paymentMethod);
            $payment->status = 'menunggu';
            $payment->waktu_kadaluarsa = Carbon::now()->addMinutes(30);

            // Mock pemesanan relationship for testing
            $payment->pemesanan = (object) [
                'id' => 999999,
                'kode_booking' => 'REALAPITEST-' . time(),
                'jumlah_penumpang' => 1,
                'nama_pemesan' => 'Real API Test User',
                'email_pemesan' => 'realapi@test.com',
                'telepon_pemesan' => '081234567890',
                'jadwal' => (object) [
                    'rutes' => collect([
                        (object) ['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung']
                    ])
                ]
            ];

            Log::info('REAL API TEST: Starting payment creation', [
                'method' => $paymentMethod,
                'channel_code' => $channelCode,
                'channel_name' => $channelName,
                'amount' => $payment->jumlah,
                'payment_code' => $payment->kode_pembayaran
            ]);

            // Create actual payment with Paylabs
            $result = $this->createPayment($payment, $channelCode, $channelName);

            $testResult = [
                'success' => $result['success'] ?? false,
                'test_type' => 'real_api_integration',
                'payment_method' => $paymentMethod,
                'channel_code' => $channelCode,
                'channel_name' => $channelName,
                'test_payment' => [
                    'id' => $payment->id,
                    'kode_pembayaran' => $payment->kode_pembayaran,
                    'amount' => $payment->jumlah,
                    'status' => $payment->status ?? 'unknown',
                ],
                'paylabs_response' => $result,
                'environment' => $this->environment,
                'base_url' => $this->baseUrl,
                'mid' => $this->mid,
                'testing_mode' => false, // Explicitly false for real API test
                'timestamp' => now()->toISOString(),
            ];

            // Log the result
            if ($result['success'] ?? false) {
                Log::info('REAL API TEST SUCCESS: Payment created with Paylabs', [
                    'payment_code' => $payment->kode_pembayaran,
                    'transaction_id' => $result['transaction_id'] ?? null,
                    'qr_code' => isset($result['payment_data']['qr_code']),
                    'va_number' => $result['payment_data']['no_virtual_account'] ?? null,
                ]);
            } else {
                Log::error('REAL API TEST FAILED: Paylabs payment creation failed', [
                    'payment_code' => $payment->kode_pembayaran,
                    'error' => $result['error'] ?? 'Unknown error',
                ]);
            }

            // Auto cleanup if enabled
            if (config('paylabs.real_api_testing.auto_cleanup', true) && isset($result['transaction_id'])) {
                Log::info('REAL API TEST: Auto cleanup enabled, would cancel payment if needed', [
                    'transaction_id' => $result['transaction_id']
                ]);
                // Note: In real scenario, you might want to cancel the test payment
                // But for verification purposes, we keep it to check callback handling
            }

            return $testResult;

        } catch (\Exception $e) {
            Log::error('REAL API TEST ERROR: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'payment_method' => $paymentMethod
            ]);

            return [
                'success' => false,
                'test_type' => 'real_api_integration',
                'error' => $e->getMessage(),
                'payment_method' => $paymentMethod,
                'environment' => $this->environment,
                'testing_mode' => false,
                'timestamp' => now()->toISOString(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }

    /**
     * Debug helper untuk Postman testing
     */
    public function debugSignatureForPostman(array $requestData, string $endpointPath = '/payment/v2.3/va/create')
    {
        try {
            // Generate timestamp dengan format yang benar
            $timestamp = $this->generatePaylabsTimestamp();

            // Generate signature
            $signature = $this->generateSignatureV23($requestData, $timestamp, $endpointPath);

            // Create complete request info for Postman
            $debugInfo = [
                'request_data' => $requestData,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'headers' => [
                    'Content-Type' => 'application/json;charset=utf-8',
                    'Accept' => 'application/json',
                    'X-TIMESTAMP' => $timestamp,
                    'X-SIGNATURE' => $signature,
                    'X-PARTNER-ID' => $this->mid,
                    'X-REQUEST-ID' => $requestData['requestId'] ?? $this->generateRequestId(),
                ],
                'endpoint_url' => rtrim($this->baseUrl, '/') . $endpointPath,
                'mid' => $this->mid,
            ];

            Log::info('PAYLABS DEBUG - Complete Signature Info:', $debugInfo);

            return [
                'success' => true,
                'data' => $debugInfo,
                'instructions' => [
                    '1. Use the endpoint_url as POST URL',
                    '2. Add all headers from headers section',
                    '3. Send request_data as raw JSON body',
                    '4. Verify timestamp format matches exactly'
                ]
            ];

        } catch (\Exception $e) {
            Log::error('PAYLABS DEBUG Signature Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }

    /**
     * Test VA Connection
     */
    public function testVAConnection()
    {
        try {
            $requestId = $this->generateRequestId();
            $testData = [
                'requestId' => $requestId,
                'merchantId' => $this->mid,
                'paymentType' => 'BCAVA',
                'amount' => '10000.00',
                'merchantTradeNo' => 'TEST-VA-' . time(),
                'payer' => 'Test User',
                'productName' => 'Test VA Payment',
                'productInfo' => [[
                    'id' => '1',
                    'name' => 'Test Product',
                    'price' => '10000.00',
                    'type' => 'Test',
                    'quantity' => 1
                ]],
                'notifyUrl' => $this->callbackUrl,
                'feeType' => 'BEN'
            ];

            $result = $this->vaCreateV23($testData);

            return [
                'success' => $result['success'] ?? false,
                'http_status' => $result['http_status'] ?? 500,
                'response' => $result['response'] ?? [],
                'test_data' => $testData
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }

    /**
     * Create payment for membership using Paylabs
     * Supports both Pembayaran and MembershipPayment models
     */
    public function createMembershipPayment($payment, $channelCode, $channelName)
    {
        try {
            $modelClass = get_class($payment);
            $isMembership = strpos($modelClass, 'MembershipPayment') !== false;

            Log::info('PAYLABS: Creating membership payment', [
                'payment_id' => $payment->id,
                'transaction_id' => $isMembership ? $payment->transaction_id : $payment->kode_pembayaran,
                'amount' => $isMembership ? $payment->total_amount : $payment->jumlah,
                'channel_code' => $channelCode,
                'channel_name' => $channelName,
                'mid' => $this->mid
            ]);

            // If testing mode active, return dummy response
            if (config('paylabs.testing.enabled', false)) {
                Log::info('PAYLABS: Using testing mode for membership');
                return $this->createTestMembershipPayment($payment, $channelCode, $channelName);
            }

            // Choose method based on channel code
            if ($channelCode === 'QRIS') {
                return $this->createMembershipQRISPayment($payment, $channelCode, $channelName);
            } elseif (strpos($channelCode, 'VA_') === 0) {
                return $this->createMembershipVirtualAccountPayment($payment, $channelCode, $channelName);
            } else {
                throw new \Exception("Unsupported payment channel for membership: {$channelCode}");
            }

        } catch (\Exception $e) {
            Log::error('PAYLABS Create Membership Payment Error:', [
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
     * Create membership QRIS payment (v2.3)
     */
    private function createMembershipQRISPayment($payment, $channelCode, $channelName)
    {
        try {
            $isMembership = class_basename($payment) === 'MembershipPayment';
            $requestId = $this->generateRequestId();
            $amount = $isMembership ? $payment->total_amount : $payment->jumlah;
            $transactionId = $isMembership ? $payment->transaction_id : $payment->kode_pembayaran;

            $requestData = [
                'requestId' => $requestId,
                'merchantId' => $this->mid,
                'storeId' => $this->storeId ?: null,
                'paymentType' => 'QRIS',
                'amount' => number_format((float) $amount, 2, '.', ''),
                'merchantTradeNo' => $transactionId,
                'notifyUrl' => $this->callbackUrl,
                'feeType' => 'BEN',
                'productName' => 'Smart Shuttle Membership',
            ];

            // Add expiry if available
            $expiryField = $isMembership ? $payment->waktu_kadaluarsa : ($payment->waktu_kadaluarsa ?? null);
            if ($expiryField) {
                $expireSeconds = Carbon::now()->diffInSeconds($expiryField);
                if ($expireSeconds > 0) {
                    $requestData['expire'] = $expireSeconds;
                }
            }

            // Remove null/empty storeId to avoid Paylabs validation error
            if (empty($requestData['storeId'])) {
                unset($requestData['storeId']);
            }

            Log::info('PAYLABS v2.3 Membership QRIS Request Data:', $requestData);

            $timestamp = $this->generatePaylabsTimestamp();
            $signature = $this->generateSignatureV23($requestData, $timestamp, '/payment/v2.3/qris/create');
            $endpointUrl = rtrim($this->baseUrl, '/') . '/payment/v2.3/qris/create';

            Log::info('PAYLABS v2.3 Membership QRIS API Call:', [
                'url' => $endpointUrl,
                'timestamp' => $timestamp,
                'request_id' => $requestId
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json;charset=utf-8',
                    'Accept' => 'application/json',
                    'X-TIMESTAMP' => $timestamp,
                    'X-SIGNATURE' => $signature,
                    'X-PARTNER-ID' => $this->mid,
                    'X-REQUEST-ID' => $requestId,
                ])
                ->post($endpointUrl, $requestData);

            $statusCode = $response->status();
            $responseBody = (string) $response->body();

            Log::info('PAYLABS v2.3 Membership QRIS Response:', [
                'status' => $statusCode,
                'body' => $responseBody
            ]);

            if (!$response->successful()) {
                $errorMsg = "HTTP {$statusCode}: " . substr($responseBody, 0, 200);
                throw new \Exception($errorMsg);
            }

            $responseData = json_decode($responseBody, true);

            return $this->processMembershipQRISResponse($payment, $responseData, $requestId);

        } catch (\Exception $e) {
            Log::error('PAYLABS v2.3 Create Membership QRIS Error:', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Create membership Virtual Account payment (v2.3)
     */
    private function createMembershipVirtualAccountPayment($payment, $channelCode, $channelName)
    {
        try {
            $isMembership = class_basename($payment) === 'MembershipPayment';
            $requestId = $this->generateRequestId();
            $amount = $isMembership ? $payment->total_amount : $payment->jumlah;
            $transactionId = $isMembership ? $payment->transaction_id : $payment->kode_pembayaran;

            // Map channel code to bank code
            $bankCode = match($channelCode) {
                'VA_BCA' => 'BCAVA',
                'VA_MANDIRI' => 'MANDIRIVA',
                'VA_BNI' => 'BNIIVA',
                'VA_BRI' => 'BRIIVA',
                default => 'BCAVA'
            };

            $requestData = [
                'requestId' => $requestId,
                'merchantId' => $this->mid,
                'storeId' => $this->storeId ?: null,
                'paymentType' => $bankCode,
                'amount' => number_format((float) $amount, 2, '.', ''),
                'merchantTradeNo' => $transactionId,
                'notifyUrl' => $this->callbackUrl,
                'feeType' => 'BEN',
                'productName' => 'Smart Shuttle Membership',
                'payer' => $payment->user ? $payment->user->name : 'Membership User',
            ];

            // Add expiry if available
            $expiryField = $isMembership ? $payment->waktu_kadaluarsa : ($payment->waktu_kadaluarsa ?? null);
            if ($expiryField) {
                $expireSeconds = Carbon::now()->diffInSeconds($expiryField);
                if ($expireSeconds > 0) {
                    $requestData['expire'] = $expireSeconds;
                }
            }

            // Remove null/empty storeId to avoid Paylabs validation error
            if (empty($requestData['storeId'])) {
                unset($requestData['storeId']);
            }

            Log::info('PAYLABS v2.3 Membership VA Request Data:', $requestData);

            $timestamp = $this->generatePaylabsTimestamp();
            $signature = $this->generateSignatureV23($requestData, $timestamp, '/payment/v2.3/va/create');
            $endpointUrl = rtrim($this->baseUrl, '/') . '/payment/v2.3/va/create';

            Log::info('PAYLABS v2.3 Membership VA API Call:', [
                'url' => $endpointUrl,
                'timestamp' => $timestamp,
                'request_id' => $requestId
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json;charset=utf-8',
                    'Accept' => 'application/json',
                    'X-TIMESTAMP' => $timestamp,
                    'X-SIGNATURE' => $signature,
                    'X-PARTNER-ID' => $this->mid,
                    'X-REQUEST-ID' => $requestId,
                ])
                ->post($endpointUrl, $requestData);

            $statusCode = $response->status();
            $responseBody = (string) $response->body();

            Log::info('PAYLABS v2.3 Membership VA Response:', [
                'status' => $statusCode,
                'body' => $responseBody
            ]);

            if (!$response->successful()) {
                $errorMsg = "HTTP {$statusCode}: " . substr($responseBody, 0, 200);
                throw new \Exception($errorMsg);
            }

            $responseData = json_decode($responseBody, true);

            return $this->processMembershipVAResponse($payment, $responseData, $requestId, $bankCode, $channelName);

        } catch (\Exception $e) {
            Log::error('PAYLABS v2.3 Create Membership VA Error:', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Process membership QRIS response
     */
    private function processMembershipQRISResponse($payment, $responseData, $requestId)
    {
        if (isset($responseData['errCode']) && $responseData['errCode'] === '0') {
            $paylabsStatus = $this->mapQRISStatus($responseData['status'] ?? '01');

            // Helper function to parse Paylabs timestamp format (YYYYMMDDHHmmss)
            $parseTimestamp = function($timestamp) {
                if (!$timestamp || strlen($timestamp) < 14) {
                    return null;
                }
                try {
                    // Format: YYYYMMDDHHmmss -> 20260126130349
                    return Carbon::createFromFormat('YmdHis', $timestamp);
                } catch (\Exception $e) {
                    Log::warning('Failed to parse Paylabs timestamp: ' . $timestamp);
                    return null;
                }
            };

            $updateData = [
                'paylabs_request_id' => $requestId,
                'paylabs_transaction_id' => $responseData['platformTradeNo'] ?? null,
                'paylabs_response' => json_encode($responseData),
                'paylabs_raw_response' => json_encode($responseData),
                'qr_code' => $responseData['qrCode'] ?? null,
                'qris_url' => $responseData['qrisUrl'] ?? null,
                'nmid' => $responseData['nmid'] ?? null,
                'platform_trade_no' => $responseData['platformTradeNo'] ?? null,
                'tid' => $responseData['tid'] ?? null,
                'rrn' => $responseData['rrn'] ?? null,
                'payer_name' => $responseData['payer'] ?? null,
                'payer_phone' => $responseData['phoneNumber'] ?? null,
                'issuer_id' => $responseData['issuerId'] ?? null,
                'trans_fee_rate' => $responseData['transFeeRate'] ?? null,
                'trans_fee_amount' => $responseData['transFeeAmount'] ?? null,
                'total_trans_fee' => $responseData['totalTransFee'] ?? null,
                'vat_fee' => $responseData['vatFee'] ?? null,
                'account_no' => $responseData['accountNo'] ?? null,
                'create_time' => $parseTimestamp($responseData['createTime'] ?? null),
                'expired_time' => $parseTimestamp($responseData['expiredTime'] ?? null),
                'updated_at' => now(),
            ];

            $payment->update($updateData);

            return [
                'success' => true,
                'transaction_id' => $responseData['platformTradeNo'] ?? $requestId,
                'payment_data' => $responseData,
                'is_test_mode' => false,
            ];
        } else {
            $errorCode = $responseData['errCode'] ?? 'UNKNOWN';
            $errorMessage = $responseData['errCodeDes'] ?? 'Unknown error from Paylabs';
            throw new \Exception("Paylabs Error {$errorCode}: {$errorMessage}");
        }
    }

    /**
     * Process membership VA response
     */
    private function processMembershipVAResponse($payment, $responseData, $requestId, $bankCode, $channelName)
    {
        if (isset($responseData['errCode']) && $responseData['errCode'] === '0') {
            $statusCode = $responseData['status'] ?? '01';
            $paylabsStatus = $this->mapVAStatusToPaylabs($statusCode);

            // Helper function to parse Paylabs timestamp format (YYYYMMDDHHmmss)
            $parseTimestamp = function($timestamp) {
                if (!$timestamp || strlen($timestamp) < 14) {
                    return null;
                }
                try {
                    // Format: YYYYMMDDHHmmss -> 20260126130349
                    return Carbon::createFromFormat('YmdHis', $timestamp);
                } catch (\Exception $e) {
                    Log::warning('Failed to parse Paylabs timestamp: ' . $timestamp);
                    return null;
                }
            };

            $updateData = [
                'paylabs_request_id' => $requestId,
                'paylabs_transaction_id' => $responseData['platformTradeNo'] ?? null,
                'paylabs_response' => json_encode($responseData),
                'paylabs_raw_response' => json_encode($responseData),
                'no_virtual_account' => $responseData['vaCode'] ?? $responseData['vaNumber'] ?? null,
                'bank_name' => $channelName,
                'platform_trade_no' => $responseData['platformTradeNo'] ?? null,
                'create_time' => $parseTimestamp($responseData['createTime'] ?? null),
                'expired_time' => $parseTimestamp($responseData['expiredTime'] ?? null),
                'trans_fee_rate' => $responseData['transFeeRate'] ?? null,
                'trans_fee_amount' => $responseData['transFeeAmount'] ?? null,
                'total_trans_fee' => $responseData['totalTransFee'] ?? null,
                'vat_fee' => $responseData['vatFee'] ?? null,
                'fee_type' => $responseData['feeType'] ?? null,
                'payer_name' => $responseData['payer'] ?? null,
                'account_no' => $responseData['accountNo'] ?? null,
                'updated_at' => now(),
            ];

            $payment->update($updateData);

            return [
                'success' => true,
                'transaction_id' => $responseData['platformTradeNo'] ?? $requestId,
                'payment_data' => $responseData,
                'is_test_mode' => false,
            ];
        } else {
            $errorCode = $responseData['errCode'] ?? 'UNKNOWN';
            $errorMessage = $responseData['errCodeDes'] ?? 'Unknown error from Paylabs';
            throw new \Exception("Paylabs Error {$errorCode}: {$errorMessage}");
        }
    }

    /**
     * Create test membership payment for development
     */
    private function createTestMembershipPayment($payment, $channelCode, $channelName)
    {
        Log::info('PAYLABS: Test membership payment created', [
            'channel_code' => $channelCode,
            'channel_name' => $channelName
        ]);

        $isMembership = class_basename($payment) === 'MembershipPayment';
        $requestId = $this->generateRequestId();
        $amount = $isMembership ? $payment->total_amount : $payment->jumlah;
        $transactionId = $isMembership ? $payment->transaction_id : $payment->kode_pembayaran;

        $testData = [
            'errCode' => '0',
            'errCodeDes' => 'Success',
            'merchantId' => $this->mid,
            'platformTradeNo' => 'TEST-MEMBER-' . time() . '-' . Str::random(6),
            'merchantTradeNo' => $transactionId,
            'amount' => number_format((float) $amount, 2, '.', ''),
            'status' => '01',
            'createTime' => now()->toDateTimeString(),
            'expiredTime' => now()->addMinutes(30)->toDateTimeString(),
        ];

        if ($channelCode === 'QRIS') {
            $testData['qrCode'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmYqRSgAAAC4AAAA3AAAAAAA...';
            $testData['qrisUrl'] = 'https://example.com/qris-test';
            return $this->processMembershipQRISResponse($payment, $testData, $requestId);
        } else {
            $testData['vaCode'] = 'VA-TEST-' . Str::random(10);
            $testData['vaNumber'] = '1234567890123456789';
            return $this->processMembershipVAResponse($payment, $testData, $requestId, 'BCAVA', $channelName);
        }
    }
}
