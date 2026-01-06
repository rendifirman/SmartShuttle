<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Services\PaylabsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paylabsService;

    public function __construct(PaylabsService $paylabsService)
    {
        $this->paylabsService = $paylabsService;
    }

    /**
     * Create payment request
     */
    public function createPayment(Request $request)
    {
        Log::info('=== PAYLABS CREATE PAYMENT START ===', $request->all());

        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'kode_booking' => 'required|exists:pemesanan,kode_booking',
                'payment_method' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            Log::info('Validation passed');

            // Get pemesanan
            $pemesanan = Pemesanan::where('kode_booking', $request->kode_booking)->first();

            if (!$pemesanan) {
                throw new \Exception('Pemesanan not found');
            }

            Log::info('Pemesanan found', ['id' => $pemesanan->id, 'kode' => $pemesanan->kode_booking]);

            // Check if user is authorized
            if (Auth::check() && $pemesanan->customer_id != Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this booking'
                ], 403);
            }

            // Check if payment already exists
            $existingPayment = Pembayaran::where('pemesanan_id', $pemesanan->id)
                ->whereIn('status', ['menunggu', 'diproses'])
                ->first();

            if ($existingPayment) {
                $pembayaran = $existingPayment;
                Log::info('Using existing payment', ['kode_pembayaran' => $pembayaran->kode_pembayaran]);
            } else {
                // Create new payment
                $pembayaran = Pembayaran::create([
                    'pemesanan_id' => $pemesanan->id,
                    'kode_pembayaran' => 'PAY' . date('Ymd') . strtoupper(Str::random(6)),
                    'jumlah' => $pemesanan->total_bayar,
                    'metode' => $request->payment_method,
                    'status' => 'menunggu',
                    'waktu_kadaluarsa' => now()->addMinutes(30),
                ]);
                Log::info('New payment created', ['kode_pembayaran' => $pembayaran->kode_pembayaran]);
            }

            // Get payment method details
            $method = \App\Models\MetodePembayaran::where('kode', $request->payment_method)->first();

            if (!$method) {
                throw new \Exception('Payment method not found: ' . $request->payment_method);
            }

            Log::info('Payment method found', [
                'method' => $method->nama,
                'is_paylabs' => $method->is_paylabs,
                'channel_code' => $method->paylabs_channel_code
            ]);

            $paylabsResponse = null;

            // If method uses Paylabs, call Paylabs API
            if ($method && $method->is_paylabs) {
                Log::info('Calling Paylabs API for payment creation');

                $paylabsResponse = $this->paylabsService->createPayment(
                    $pembayaran,
                    $method->paylabs_channel_code,
                    $method->paylabs_channel_name
                );

                if (!$paylabsResponse['success']) {
                    throw new \Exception('Paylabs error: ' . $paylabsResponse['error']);
                }

                Log::info('Paylabs payment created successfully', [
                    'transaction_id' => $paylabsResponse['transaction_id']
                ]);
            } else {
                // For non-Paylabs methods (fallback)
                Log::info('Using non-Paylabs method, creating simple response');

                $paylabsResponse = [
                    'success' => true,
                    'transaction_id' => 'LOCAL' . time(),
                    'payment_data' => [
                        'transaction_id' => 'LOCAL' . time(),
                        'amount' => (int) $pembayaran->jumlah,
                        'status' => 'PENDING',
                        'expired_time' => $pembayaran->waktu_kadaluarsa->toDateTimeString(),
                        'qr_code' => $method->kode === 'qris' ?
                            'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' .
                            urlencode('SMARTSHUTTLE|' . $pembayaran->kode_pembayaran . '|' . $pembayaran->jumlah) : null
                    ]
                ];
            }

            DB::commit();

            Log::info('=== PAYLABS CREATE PAYMENT SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Payment request created successfully',
                'data' => [
                    'payment' => [
                        'id' => $pembayaran->id,
                        'kode_pembayaran' => $pembayaran->kode_pembayaran,
                        'jumlah' => $pembayaran->jumlah,
                        'metode' => $pembayaran->metode,
                        'status' => $pembayaran->status,
                        'waktu_kadaluarsa' => $pembayaran->waktu_kadaluarsa,
                        'pemesanan_id' => $pembayaran->pemesanan_id,
                        'paylabs_transaction_id' => $pembayaran->paylabs_transaction_id,
                        'qr_code' => $pembayaran->qr_code,
                        'no_virtual_account' => $pembayaran->no_virtual_account,
                        'nama_bank' => $pembayaran->nama_bank,
                        'checkout_url' => $pembayaran->checkout_url ?? null,
                    ],
                    'paylabs_response' => $paylabsResponse
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('PAYLABS CREATE PAYMENT ERROR: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage(),
                'debug' => env('APP_DEBUG') ? [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ] : null
            ], 500);
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus($kodePembayaran)
    {
        try {
            Log::info('PAYLABS: Getting payment status for: ' . $kodePembayaran);

            $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)->first();

            if (!$pembayaran) {
                Log::warning('Payment not found: ' . $kodePembayaran);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            Log::info('Payment found', [
                'id' => $pembayaran->id,
                'status' => $pembayaran->status,
                'paylabs_status' => $pembayaran->paylabs_status,
                'paylabs_transaction_id' => $pembayaran->paylabs_transaction_id
            ]);

            $statusData = [
                'status' => $pembayaran->status,
                'transactionId' => $pembayaran->paylabs_transaction_id,
                'amount' => (int) $pembayaran->jumlah,
                'paylabs_status' => $pembayaran->paylabs_status
            ];

            // If there's a Paylabs transaction ID, check status from Paylabs
            if ($pembayaran->paylabs_transaction_id && $pembayaran->paylabs_status !== 'PAID') {
                $paylabsStatus = $this->paylabsService->checkStatus($pembayaran->paylabs_transaction_id);

                if ($paylabsStatus['success']) {
                    $statusData['paylabs_status'] = $paylabsStatus['status'];
                    $statusData['payment_time'] = $paylabsStatus['paymentTime'];

                    // Update local status if changed
                    if ($paylabsStatus['status'] !== $pembayaran->paylabs_status) {
                        $localStatus = $this->mapPaylabsStatusToLocal($paylabsStatus['status']);

                        $pembayaran->update([
                            'paylabs_status' => $paylabsStatus['status'],
                            'status' => $localStatus,
                            'waktu_pembayaran' => $paylabsStatus['status'] === 'PAID' ? now() : null
                        ]);

                        if ($paylabsStatus['status'] === 'PAID') {
                            $this->updatePemesananAfterPayment($pembayaran);
                        }
                    }
                }
            }

            // Calculate expiry time
            $isExpired = false;
            $remainingTime = 0;

            if ($pembayaran->waktu_kadaluarsa) {
                $now = now();
                $expiry = \Carbon\Carbon::parse($pembayaran->waktu_kadaluarsa);
                $isExpired = $expiry < $now;
                $remainingTime = max(0, $now->diffInSeconds($expiry, false));
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'payment' => [
                        'id' => $pembayaran->id,
                        'kode_pembayaran' => $pembayaran->kode_pembayaran,
                        'jumlah' => $pembayaran->jumlah,
                        'metode' => $pembayaran->metode,
                        'status' => $pembayaran->status,
                        'paylabs_status' => $pembayaran->paylabs_status,
                        'waktu_kadaluarsa' => $pembayaran->waktu_kadaluarsa,
                        'waktu_pembayaran' => $pembayaran->waktu_pembayaran,
                        'created_at' => $pembayaran->created_at,
                        'updated_at' => $pembayaran->updated_at,
                        'qr_code' => $pembayaran->qr_code,
                        'no_virtual_account' => $pembayaran->no_virtual_account,
                        'nama_bank' => $pembayaran->nama_bank,
                        'checkout_url' => $pembayaran->checkout_url ?? null,
                    ],
                    'status' => $statusData,
                    'is_expired' => $isExpired,
                    'remaining_time' => $remainingTime,
                    'is_paid' => $pembayaran->status === 'berhasil'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('PAYLABS Get payment status error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'kodePembayaran' => $kodePembayaran
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment status: ' . $e->getMessage(),
                'debug' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Payment callback (webhook) from Paylabs
     */
    public function callback(Request $request)
    {
        Log::info('=== PAYLABS CALLBACK RECEIVED ===', $request->all());

        DB::beginTransaction();

        try {
            // Verify signature
            $signature = $request->input('signature');
            $data = $request->except('signature');

            if (!$this->paylabsService->verifySignature($data, $signature)) {
                Log::error('PAYLABS Callback signature verification failed', $request->all());
                return response()->json(['success' => false, 'message' => 'Invalid signature'], 400);
            }

            $validator = Validator::make($request->all(), [
                'merchantId' => 'required|string',
                'transactionId' => 'required|string',
                'merchantTradeNo' => 'required|string',
                'amount' => 'required|numeric',
                'currency' => 'required|string',
                'paymentChannel' => 'required|string',
                'status' => 'required|string',
                'signature' => 'required|string'
            ]);

            if ($validator->fails()) {
                Log::error('PAYLABS Callback validation failed', $validator->errors()->toArray());
                return response()->json(['success' => false, 'message' => 'Invalid callback data'], 400);
            }

            // Find payment
            $pembayaran = Pembayaran::where('kode_pembayaran', $request->merchantTradeNo)
                ->orWhere('paylabs_transaction_id', $request->transactionId)
                ->first();

            if (!$pembayaran) {
                Log::error('Payment not found for callback', ['merchantTradeNo' => $request->merchantTradeNo]);
                throw new \Exception('Payment not found');
            }

            Log::info('Payment found for callback', [
                'payment_id' => $pembayaran->id,
                'status' => $request->status
            ]);

            // Update payment status
            $localStatus = $this->mapPaylabsStatusToLocal($request->status);

            $updateData = [
                'paylabs_status' => $request->status,
                'status' => $localStatus,
                'paylabs_response' => json_encode($request->all()),
                'updated_at' => now(),
            ];

            if ($request->status === 'PAID') {
                $updateData['waktu_pembayaran'] = now();
            }

            $pembayaran->update($updateData);

            // Update pemesanan if payment successful
            if ($request->status === 'PAID') {
                $this->updatePemesananAfterPayment($pembayaran);
            }

            DB::commit();

            Log::info('PAYLABS Callback processed successfully', [
                'transactionId' => $request->transactionId,
                'status' => $request->status
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('PAYLABS Callback processing error: ' . $e->getMessage(), [
                'transactionId' => $request->input('transactionId', 'unknown'),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Callback processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update pemesanan after successful payment
     */
    private function updatePemesananAfterPayment($pembayaran)
    {
        $pembayaran->pemesanan->update([
            'status' => 'dibayar',
            'tanggal_pembayaran' => now()->toDateString(),
            'waktu_pembayaran' => now(),
            'metode_pembayaran' => $pembayaran->metode
        ]);

        // Create transaction record
        \App\Models\Transaksi::create([
            'pembayaran_id' => $pembayaran->id,
            'pemesanan_id' => $pembayaran->pemesanan_id,
            'kode_transaksi' => 'TRX' . date('Ymd') . strtoupper(Str::random(6)),
            'jumlah' => $pembayaran->jumlah,
            'biaya_admin' => 0,
            'total' => $pembayaran->jumlah,
            'waktu_transaksi' => now()
        ]);

        // Add loyalty points if user exists
        if ($pembayaran->pemesanan->customer) {
            $this->addLoyaltyPoints($pembayaran->pemesanan->customer);
        }

        Log::info('Pemesanan updated after payment', [
            'pemesanan_id' => $pembayaran->pemesanan_id,
            'status' => 'dibayar'
        ]);
    }

    /**
     * Map Paylabs status to local status
     */
    private function mapPaylabsStatusToLocal($paylabsStatus)
    {
        $mapping = [
            'PENDING' => 'menunggu',
            'PROCESSING' => 'diproses',
            'PAID' => 'berhasil',
            'EXPIRED' => 'kadaluarsa',
            'FAILED' => 'gagal',
            'CANCELLED' => 'dibatalkan',
            'REFUNDED' => 'dikembalikan'
        ];

        return $mapping[$paylabsStatus] ?? 'menunggu';
    }

    /**
     * Add loyalty points
     */
    private function addLoyaltyPoints($user)
    {
        try {
            $user->member_point += 100;

            // Add loyalty points based on membership level
            $loyaltyPoints = $this->calculateLoyaltyPoints($user->membership_level);
            $user->loyalty_point += $loyaltyPoints;

            // Update membership level if needed
            $newLevel = $this->updateMembershipLevel($user);
            $user->membership_level = $newLevel;

            $user->save();

            Log::info('Loyalty points added', [
                'user_id' => $user->id,
                'points_added' => 100,
                'loyalty_points_added' => $loyaltyPoints,
                'new_level' => $newLevel
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to add loyalty points: ' . $e->getMessage());
        }
    }

    /**
     * Calculate loyalty points
     */
    private function calculateLoyaltyPoints($level)
    {
        switch ($level) {
            case 'Bronze': return 50;
            case 'Silver': return 60;
            case 'Gold': return 80;
            case 'Platinum': return 100;
            default: return 50;
        }
    }

    /**
     * Update membership level
     */
    private function updateMembershipLevel($user)
    {
        $points = $user->member_point;

        if ($points >= 4500) return 'Platinum';
        if ($points >= 2500) return 'Gold';
        if ($points >= 1000) return 'Silver';
        return 'Bronze';
    }

    /**
     * Test Paylabs connection
     */
    public function testConnection()
    {
        try {
            $result = $this->paylabsService->testConnection();

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Paylabs connection successful' : 'Paylabs connection failed',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test connection failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
