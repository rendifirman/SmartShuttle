<?php

namespace App\Services;

use App\Models\Pembayaran;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaylabsSimulator
{
    private $merchantId;
    private $secretKey;
    private $baseUrl;

    public function __construct()
    {
        // Configuration for simulation
        $this->merchantId = 'DEMO_MERCHANT_' . date('Ymd');
        $this->secretKey = 'demo_secret_key_' . Str::random(16);
        $this->baseUrl = 'https://sandbox.paylabs.co.id'; // Simulation URL
    }

    /**
     * Create payment request
     */
    public function createPayment(Pembayaran $pembayaran, $channelCode, $channelName = null)
    {
        try {
            $amount = (int) round($pembayaran->jumlah);

            // Generate unique transaction ID
            $transactionId = 'PL' . date('YmdHis') . Str::random(6);

            // Prepare payment data based on channel
            $paymentData = $this->preparePaymentData($pembayaran, $amount, $channelCode, $channelName);

            // Generate signature for simulation
            $signature = $this->generateSignature([
                'merchantId' => $this->merchantId,
                'transactionId' => $transactionId,
                'amount' => $amount
            ]);

            $response = [
                'responseCode' => '00',
                'responseMessage' => 'SUCCESS',
                'merchantId' => $this->merchantId,
                'merchantOrderId' => $pembayaran->kode_pembayaran,
                'transactionId' => $transactionId,
                'paymentChannel' => $channelCode,
                'channelName' => $channelName,
                'amount' => $amount,
                'currency' => 'IDR',
                'paymentUrl' => url("/payment/simulate/{$transactionId}"),
                'qrCodeUrl' => $channelCode === 'QRIS' ? $this->generateQRCode($pembayaran) : null,
                'virtualAccount' => $this->generateVirtualAccount($pembayaran, $channelName),
                'expiredTime' => now()->addMinutes(30)->format('Y-m-d H:i:s'),
                'signature' => $signature
            ];

            // Update pembayaran dengan data Paylabs
            $pembayaran->update([
                'paylabs_transaction_id' => $transactionId,
                'paylabs_merchant_id' => $this->merchantId,
                'paylabs_status' => 'PENDING',
                'paylabs_response' => json_encode($response),
                'waktu_kadaluarsa' => now()->addMinutes(30),
            ]);

            // Generate QR code data untuk QRIS
            if ($channelCode === 'QRIS') {
                $this->generateQRISData($pembayaran, $response);
            }

            return $response;

        } catch (\Exception $e) {
            Log::error('Paylabs simulation error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Prepare payment data based on channel
     */
    private function preparePaymentData($pembayaran, $amount, $channelCode, $channelName)
    {
        $data = [
            'merchantId' => $this->merchantId,
            'merchantOrderId' => $pembayaran->kode_pembayaran,
            'amount' => $amount,
            'currency' => 'IDR',
            'customerName' => $pembayaran->pemesanan->nama_pemesan ?? 'Customer',
            'customerEmail' => $pembayaran->pemesanan->email_pemesan ?? '',
            'customerPhone' => $pembayaran->pemesanan->telepon_pemesan ?? '',
            'description' => 'Pembayaran Tiket Smart Shuttle - ' . $pembayaran->pemesanan->kode_booking,
            'paymentChannel' => $channelCode,
            'channelName' => $channelName,
            'callbackUrl' => url('/api/payment/callback'),
            'redirectUrl' => url('/customer/pembayaran/' . $pembayaran->pemesanan->kode_booking),
            'expiredTime' => now()->addMinutes(30)->format('Y-m-d H:i:s'),
        ];

        return $data;
    }

    /**
     * Generate QR code for QRIS
     */
    private function generateQRCode($pembayaran)
    {
        // Simulate QR code generation
        $qrData = "00020101021126680014ID.SMART-SHUTTLE.WWW011893600914SMARTSHUTTLE0215" .
                  Str::random(15) . "0303IDR5802ID5914SMART SHUTTLE6013JAKARTA PUSAT61051234062380125" .
                  $pembayaran->kode_pembayaran . "0708" . date('Ymd') . "0805300604" .
                  str_pad((int)$pembayaran->jumlah, 12, '0', STR_PAD_LEFT) .
                  "2905" . Str::random(5) . "6304";

        // Calculate CRC
        $crc = strtoupper(dechex(crc32($qrData)));
        $qrData .= str_pad($crc, 4, '0', STR_PAD_LEFT);

        return url("/api/payment/qr-code/{$pembayaran->kode_pembayaran}");
    }

    /**
     * Generate QRIS data
     */
    private function generateQRISData($pembayaran, $response)
    {
        $nmid = '8888' . Str::random(12);
        $rawData = $this->generateEMVCoQRData($pembayaran, $nmid);

        $pembayaran->update([
            'qris_raw_data' => $rawData,
            'qris_nmid' => $nmid,
            'qr_code' => $response['qrCodeUrl']
        ]);
    }

    /**
     * Generate EMVCo QR Data
     */
    private function generateEMVCoQRData($pembayaran, $nmid)
    {
        $data = [
            'payloadFormatIndicator' => '01',
            'pointOfInitiationMethod' => '12',
            'merchantAccountInformation' => [
                'gui' => 'ID.CO.QRIS.WWW',
                'merchantId' => $nmid,
                'merchantCriteria' => 'UMI'
            ],
            'merchantCategoryCode' => '5732',
            'transactionCurrency' => '360',
            'transactionAmount' => (int) $pembayaran->jumlah,
            'countryCode' => 'ID',
            'merchantName' => 'SMART SHUTTLE',
            'merchantCity' => 'JAKARTA PUSAT',
            'postalCode' => '10310',
            'additionalData' => [
                'billNumber' => $pembayaran->kode_pembayaran,
                'storeLabel' => 'SmartShuttle',
                'customerLabel' => $pembayaran->pemesanan->nama_pemesan ?? 'Customer',
                'terminalLabel' => 'WEB001'
            ]
        ];

        return json_encode($data);
    }

    /**
     * Generate virtual account number
     */
    private function generateVirtualAccount($pembayaran, $bank)
    {
        $prefixes = [
            'BCA' => '3901',
            'MANDIRI' => '88608',
            'BNI' => '881',
            'BRI' => '888'
        ];

        $prefix = $prefixes[$bank] ?? '8888';
        $customerCode = substr(preg_replace('/[^0-9]/', '', $pembayaran->kode_pembayaran), 0, 10);
        $vaNumber = $prefix . str_pad($customerCode, 16 - strlen($prefix), '0', STR_PAD_LEFT);

        return $vaNumber;
    }

    /**
     * Generate signature for simulation
     */
    private function generateSignature($data)
    {
        $stringToSign = implode('', [
            $data['merchantId'],
            $data['transactionId'],
            $data['amount'],
            $this->secretKey
        ]);

        return hash('sha256', $stringToSign);
    }

    /**
     * Check payment status
     */
    public function checkStatus($transactionId)
    {
        // Simulate status checking
        $statuses = ['PENDING', 'PAID', 'EXPIRED', 'FAILED'];
        $weights = [30, 40, 20, 10];

        // Find payment record
        $pembayaran = Pembayaran::where('paylabs_transaction_id', $transactionId)->first();

        if (!$pembayaran) {
            return [
                'responseCode' => '01',
                'responseMessage' => 'TRANSACTION_NOT_FOUND'
            ];
        }

        // For simulation, we'll randomly determine status based on time
        if ($pembayaran->waktu_kadaluarsa < now()) {
            $status = 'EXPIRED';
        } else {
            // Weighted random for demo
            $random = mt_rand(1, 100);
            $cumulative = 0;
            $status = 'PENDING';

            foreach ($weights as $index => $weight) {
                $cumulative += $weight;
                if ($random <= $cumulative) {
                    $status = $statuses[$index];
                    break;
                }
            }
        }

        $pembayaran->update([
            'paylabs_status' => $status,
            'status' => $this->mapPaylabsStatus($status)
        ]);

        if ($status === 'PAID') {
            $pembayaran->update([
                'waktu_pembayaran' => now(),
                'status' => 'berhasil'
            ]);

            // Update related pemesanan
            $pembayaran->pemesanan->update([
                'status' => 'dibayar',
                'tanggal_pembayaran' => now()->toDateString(),
                'waktu_pembayaran' => now(),
                'metode_pembayaran' => $pembayaran->metode
            ]);
        }

        return [
            'responseCode' => $status === 'PAID' ? '00' : '02',
            'responseMessage' => $status,
            'transactionId' => $transactionId,
            'merchantOrderId' => $pembayaran->kode_pembayaran,
            'amount' => (int) $pembayaran->jumlah,
            'currency' => 'IDR',
            'paymentChannel' => $pembayaran->metode,
            'status' => $status,
            'paidAmount' => $status === 'PAID' ? (int) $pembayaran->jumlah : 0,
            'paidTime' => $status === 'PAID' ? now()->format('Y-m-d H:i:s') : null,
            'signature' => $this->generateSignature([
                'merchantId' => $this->merchantId,
                'transactionId' => $transactionId,
                'amount' => (int) $pembayaran->jumlah,
                'status' => $status
            ])
        ];
    }

    /**
     * Map Paylabs status to local status
     */
    private function mapPaylabsStatus($paylabsStatus)
    {
        $mapping = [
            'PENDING' => 'menunggu',
            'PAID' => 'berhasil',
            'EXPIRED' => 'kadaluarsa',
            'FAILED' => 'gagal'
        ];

        return $mapping[$paylabsStatus] ?? 'menunggu';
    }

    /**
     * Simulate payment callback (webhook)
     */
    public function simulateCallback($transactionId, $status = 'PAID')
    {
        $pembayaran = Pembayaran::where('paylabs_transaction_id', $transactionId)->first();

        if (!$pembayaran) {
            return false;
        }

        $pembayaran->update([
            'paylabs_status' => $status,
            'status' => $this->mapPaylabsStatus($status),
            'waktu_pembayaran' => $status === 'PAID' ? now() : null
        ]);

        if ($status === 'PAID') {
            $pembayaran->pemesanan->update([
                'status' => 'dibayar',
                'tanggal_pembayaran' => now()->toDateString(),
                'waktu_pembayaran' => now(),
                'metode_pembayaran' => $pembayaran->metode
            ]);
        }

        return true;
    }
}
