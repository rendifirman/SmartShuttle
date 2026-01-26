<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MetodePembayaran;
use App\Models\MembershipPayment;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Services\PaylabsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected PaylabsService $paylabsService;

    public function __construct(PaylabsService $paylabsService)
    {
        $this->paylabsService = $paylabsService;
    }

    public function getNotifyUrlFromEnv()
    {
        return env('PAYLABS_NOTIFY_URL');
    }

    /**
     * List active payment methods.
     */
    public function getPaymentMethods()
    {
        $methods = MetodePembayaran::aktif()->get();

        return response()->json([
            'success' => true,
            'data' => $methods,
        ]);
    }

    /**
     * Create payment for a booking.
     * Expected body: { kode_booking, payment_method }
     */
    public function createPayment(Request $request)
    {
        Log::info('=== PAYLABS CREATE PAYMENT START ===', $request->all());

        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'kode_booking' => 'required|string',
                'payment_method' => 'required|string',
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $pemesanan = Pemesanan::where('kode_booking', $request->kode_booking)->first();
            if (!$pemesanan) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan not found',
                ], 404);
            }

            // Check if user is authenticated
            if (!Auth::check()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                ], 401);
            }

            // Check if user owns this booking
            if ($pemesanan->customer_id && (int) $pemesanan->customer_id !== (int) Auth::id()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to create payment for this booking',
                ], 403);
            }

            $method = MetodePembayaran::where('kode', $request->payment_method)
                ->where('aktif', true)
                ->first();

            if (!$method) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method not found',
                ], 404);
            }

            $existingPayment = Pembayaran::where('pemesanan_id', $pemesanan->id)
                ->whereIn('status', ['menunggu', 'diproses'])
                ->orderByDesc('id')
                ->first();

            if ($existingPayment && $existingPayment->waktu_kadaluarsa && now()->lt($existingPayment->waktu_kadaluarsa)) {
                $pembayaran = $existingPayment;
            } else {
                $expiryMinutes = (int) config('paylabs.payment.expiry_minutes', 30);

                $pembayaran = Pembayaran::create([
                    'pemesanan_id' => $pemesanan->id,
                    'kode_pembayaran' => 'PAY-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
                    'jumlah' => $pemesanan->total_bayar,
                    'metode' => $method->kode,
                    'status' => 'menunggu',
                    'nama_bank' => $method->jenis ?? null,
                    'instruksi_pembayaran' => is_string($method->instruksi) ? $method->instruksi : json_encode($method->instruksi),
                    'waktu_kadaluarsa' => now()->addMinutes($expiryMinutes),
                ]);
            }

            $paylabsResponse = null;

            if ($method->is_paylabs) {
                $paylabsResponse = $this->paylabsService->createPayment(
                    $pembayaran,
                    $method->paylabs_channel_code,
                    $method->paylabs_channel_name
                );

                if (!($paylabsResponse['success'] ?? false)) {
                    throw new \Exception('Paylabs error: ' . ($paylabsResponse['error'] ?? 'Unknown error'));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment request created successfully',
                'data' => [
                    'payment' => $pembayaran->fresh(),
                    'paylabs_response' => $paylabsResponse,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('PAYLABS CREATE PAYMENT ERROR: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get payment status (local + optionally query Paylabs).
     */
    public function getPaymentStatus($kodePembayaran)
    {
        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)->first();
            if (!$pembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found',
                ], 404);
            }

            $statusData = [
                'local_status' => $pembayaran->status,
                'paylabs_status' => $pembayaran->paylabs_status,
                'merchantTradeNo' => $pembayaran->kode_pembayaran,
                'platformTradeNo' => $pembayaran->platform_trade_no,
                'amount' => (float) $pembayaran->jumlah,
            ];

            // Query Paylabs if we have identifiers and not PAID
            if ($pembayaran->platform_trade_no && $pembayaran->paylabs_status !== 'PAID') {
                $paylabsStatus = $this->paylabsService->checkStatus($pembayaran->kode_pembayaran, $pembayaran->platform_trade_no);
                $statusData['paylabs_query'] = $paylabsStatus;
            }

            $isExpired = false;
            $remainingTime = 0;
            if ($pembayaran->waktu_kadaluarsa) {
                $remainingTime = max(0, now()->diffInSeconds($pembayaran->waktu_kadaluarsa, false));
                $isExpired = $remainingTime <= 0;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'payment' => $pembayaran,
                    'status' => $statusData,
                    'is_expired' => $isExpired,
                    'remaining_time' => $remainingTime,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('PAYLABS Get payment status error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'kodePembayaran' => $kodePembayaran,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get QR image/url for a payment.
     */
    public function getQRCode($kodePembayaran)
    {
        $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)->first();
        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'qr_code' => $pembayaran->qr_code,
                'qris_url' => $pembayaran->qris_url,
            ],
        ]);
    }

    /**
     * Legacy callback endpoint (deprecated).
     */
    public function callback(Request $request)
    {
        Log::info('PAYLABS legacy callback received (deprecated)', $request->all());

        return response()->json([
            'success' => false,
            'message' => 'Deprecated callback endpoint. Use /api/payment/callback-v23 for Paylabs v2.3.',
        ], 410);
    }

    /**
     * Paylabs QRIS v2.3 callback.
     */
    public function callbackV23(Request $request)
    {
        Log::info('=== PAYLABS v2.3 CALLBACK RECEIVED ===', $request->all());

        DB::beginTransaction();

        try {
            $timestamp = $request->header('X-TIMESTAMP');
            $signature = $request->header('X-SIGNATURE');

            $endpointPath = $request->getPathInfo();
            $rawBody = $request->getContent();

            if (!$this->paylabsService->verifySignatureV23($rawBody, $signature, $timestamp, $endpointPath)) {
                Log::error('PAYLABS v2.3 Callback signature verification failed', [
                    'headers' => $request->headers->all(),
                    'body' => $request->all(),
                ]);

                return response()->json([
                    'errCode' => '99',
                    'errCodeDes' => 'Invalid signature',
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'errCode' => 'required|string',
                'merchantId' => 'required|string',
                'platformTradeNo' => 'required|string',
                'merchantTradeNo' => 'required|string',
                'amount' => 'required|numeric',
                'status' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errCode' => '99',
                    'errCodeDes' => 'Invalid data',
                ], 400);
            }

            $pembayaran = Pembayaran::where('kode_pembayaran', $request->merchantTradeNo)
                ->orWhere('platform_trade_no', $request->platformTradeNo)
                ->first();

            if (!$pembayaran) {
                return response()->json([
                    'errCode' => '99',
                    'errCodeDes' => 'Payment not found',
                ], 404);
            }

            $paylabsStatus = $this->mapQRISStatusToPaylabs($request->status);
            $localStatus = $this->mapPaylabsStatusToLocal($paylabsStatus);

            $updateData = [
                'paylabs_status' => $paylabsStatus,
                'status' => $localStatus,
                'paylabs_response' => json_encode($request->all()),
                'paylabs_raw_response' => json_encode($request->all()),
                'updated_at' => now(),
            ];

            foreach (['successTime' => 'success_time', 'expiredTime' => 'expired_time', 'rrn' => 'rrn', 'tid' => 'tid', 'payer' => 'payer_name', 'phoneNumber' => 'payer_phone', 'issuerId' => 'issuer_id'] as $from => $to) {
                if ($request->has($from)) {
                    $updateData[$to] = $request->input($from);
                }
            }

            if ($paylabsStatus === 'PAID' && $localStatus === 'berhasil') {
                $updateData['waktu_pembayaran'] = now();
                $this->updatePemesananAfterPayment($pembayaran);
            }

            $pembayaran->update($updateData);

            DB::commit();

            return response()->json([
                'errCode' => '0',
                'errCodeDes' => 'Success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('PAYLABS v2.3 Callback processing error: ' . $e->getMessage(), [
                'request' => $request->all(),
            ]);

            return response()->json([
                'errCode' => '99',
                'errCodeDes' => 'Callback processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**     * Paylabs v2.3 callback for membership payments.
     */
    public function callbackMembershipV23(Request $request)
    {
        Log::info('=== PAYLABS v2.3 MEMBERSHIP CALLBACK RECEIVED ===', $request->all());

        DB::beginTransaction();

        try {
            $timestamp = $request->header('X-TIMESTAMP');
            $signature = $request->header('X-SIGNATURE');

            $endpointPath = $request->getPathInfo();
            $rawBody = $request->getContent();

            if (!$this->paylabsService->verifySignatureV23($rawBody, $signature, $timestamp, $endpointPath)) {
                Log::error('PAYLABS v2.3 Membership Callback signature verification failed', [
                    'headers' => $request->headers->all(),
                    'body' => $request->all(),
                ]);

                return response()->json([
                    'errCode' => '99',
                    'errCodeDes' => 'Invalid signature',
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'errCode' => 'required|string',
                'merchantId' => 'required|string',
                'platformTradeNo' => 'required|string',
                'merchantTradeNo' => 'required|string',
                'amount' => 'required|numeric',
                'status' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errCode' => '99',
                    'errCodeDes' => 'Invalid data',
                ], 400);
            }

            $membershipPayment = MembershipPayment::where('transaction_id', $request->merchantTradeNo)
                ->orWhere('platform_trade_no', $request->platformTradeNo)
                ->first();

            if (!$membershipPayment) {
                Log::warning('Membership payment not found', [
                    'merchantTradeNo' => $request->merchantTradeNo,
                    'platformTradeNo' => $request->platformTradeNo,
                ]);

                return response()->json([
                    'errCode' => '99',
                    'errCodeDes' => 'Membership payment not found',
                ], 404);
            }

            $paylabsStatus = $this->mapQRISStatusToPaylabs($request->status);
            $localStatus = $this->mapPaylabsStatusToLocal($paylabsStatus);

            $updateData = [
                'paylabs_response' => json_encode($request->all()),
                'paylabs_raw_response' => json_encode($request->all()),
                'payment_status' => $localStatus === 'berhasil' ? 'success' : $localStatus,
                'updated_at' => now(),
            ];

            foreach (['successTime' => 'success_time', 'expiredTime' => 'expired_time', 'rrn' => 'rrn', 'tid' => 'tid', 'payer' => 'payer_name', 'phoneNumber' => 'payer_phone', 'issuerId' => 'issuer_id'] as $from => $to) {
                if ($request->has($from)) {
                    $updateData[$to] = $request->input($from);
                }
            }

            if ($paylabsStatus === 'PAID' && $localStatus === 'berhasil') {
                $updateData['paid_at'] = now();
                // Activate membership when payment successful
                $this->activateMembershipAfterPayment($membershipPayment);
            }

            $membershipPayment->update($updateData);

            DB::commit();

            Log::info('Membership payment callback processed successfully', [
                'transaction_id' => $membershipPayment->transaction_id,
                'status' => $paylabsStatus,
            ]);

            return response()->json([
                'errCode' => '0',
                'errCodeDes' => 'Success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('PAYLABS v2.3 Membership Callback processing error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'errCode' => '99',
                'errCodeDes' => 'Callback processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**     * Test Paylabs connection.
     */
    public function testConnection()
    {
        try {
            $result = $this->paylabsService->testConnection();

            return response()->json([
                'success' => (bool) ($result['success'] ?? false),
                'message' => ($result['success'] ?? false) ? 'Paylabs connection successful' : 'Paylabs connection failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test connection failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Protected route: simulate payment success.
     */
    public function simulatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_pembayaran' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $pembayaran = Pembayaran::where('kode_pembayaran', $request->kode_pembayaran)->first();
        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        $pembayaran->update([
            'status' => 'berhasil',
            'paylabs_status' => 'PAID',
            'waktu_pembayaran' => now(),
        ]);

        $this->updatePemesananAfterPayment($pembayaran);

        return response()->json(['success' => true, 'data' => $pembayaran->fresh()]);
    }

    /**
     * DEV: Generate QRIS directly to Paylabs (v2.3) for Postman testing.
     */
    public function devPaylabsQrisCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'productName' => 'required|string|max:100',
            'merchantTradeNo' => 'nullable|string|max:32',
            'notifyUrl' => 'nullable|url|max:200',
            'feeType' => 'nullable|in:BEN,OUR',
            'productInfo' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->paylabsService->qrisCreateV23($request->all());
            return response()->json($result, $result['success'] ? 200 : 502);
        } catch (\Exception $e) {
            Log::error('DEV PAYLABS QRIS CREATE error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DEV: Query QRIS order status (v2.3).
     */
    public function devPaylabsQrisQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchantTradeNo' => 'required_without:rrn|string|max:32',
            'rrn' => 'required_without:merchantTradeNo|string|max:32',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->paylabsService->qrisQueryV23($request->all());
            return response()->json($result, $result['success'] ? 200 : 502);
        } catch (\Exception $e) {
            Log::error('DEV PAYLABS QRIS QUERY error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DEV: Cancel QRIS order (v2.3).
     */
    public function devPaylabsQrisCancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchantTradeNo' => 'required|string|max:32',
            'platformTradeNo' => 'required|string|max:32',
            'qrCode' => 'nullable|string|max:300',
            'productName' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->paylabsService->qrisCancelV23($request->all());
            return response()->json($result, $result['success'] ? 200 : 502);
        } catch (\Exception $e) {
            Log::error('DEV PAYLABS QRIS CANCEL error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function updatePemesananAfterPayment(Pembayaran $pembayaran): void
    {
        $pemesanan = $pembayaran->pemesanan;
        if ($pemesanan) {
            $pemesanan->update([
                'status' => 'dibayar',
                'tanggal_pembayaran' => now()->toDateString(),
                'waktu_pembayaran' => now(),
                'metode_pembayaran' => $pembayaran->metode,
                'status_pembayaran' => 'paid',
            ]);
        }

        if (!Transaksi::where('pembayaran_id', $pembayaran->id)->exists()) {
            Transaksi::create([
                'pembayaran_id' => $pembayaran->id,
                'pemesanan_id' => $pembayaran->pemesanan_id,
                'kode_transaksi' => Transaksi::generateKodeTransaksi(),
                'jumlah' => $pembayaran->jumlah,
                'biaya_admin' => 0,
                'total' => $pembayaran->jumlah,
                'waktu_transaksi' => now(),
            ]);
        }
    }

    private function mapPaylabsStatusToLocal(string $paylabsStatus): string
    {
        $mapping = [
            'PENDING' => 'menunggu',
            'PROCESSING' => 'diproses',
            'PAID' => 'berhasil',
            'EXPIRED' => 'kadaluarsa',
            'FAILED' => 'gagal',
            'CANCELLED' => 'dibatalkan',
        ];

        return $mapping[$paylabsStatus] ?? 'menunggu';
    }

    private function mapQRISStatusToPaylabs(string $qrisStatus): string
    {
        $mapping = [
            '01' => 'PENDING',
            '02' => 'PAID',
            '09' => 'FAILED',
            '06' => 'CANCELLED',
        ];

        return $mapping[$qrisStatus] ?? $qrisStatus;
    }
    /**
     * DEV: Generate Virtual Account directly to Paylabs (v2.3) for Postman testing.
     */
    public function devPaylabsVaCreate(Request $request)
{
    try {
        // Validate minimal required fields - update to match your database
        $validated = $request->validate([
            'requestId' => 'sometimes|string',
            'merchantId' => 'sometimes|string',
            'paymentType' => 'required|string',
            'amount' => 'required|numeric|min:1000',
            'merchantTradeNo' => 'required|string|unique:pembayaran,kode_pembayaran',
            'payer' => 'sometimes|string',
            'productName' => 'sometimes|string',
            'productInfo' => 'sometimes|array',
        ]);

        // Set default values if not provided
        $payload = array_merge([
            'requestId' => $validated['requestId'] ?? ('DEV-VA-' . time() . '-' . Str::random(6)),
            'merchantId' => config('paylabs.mid', '010529'),
            'storeId' => config('paylabs.store_id', ''),
            'notifyUrl' => config('paylabs.callback_url'),
            'feeType' => 'BEN',
            'productName' => $request->input('productName', 'Smart Shuttle Ticket'),
            'productInfo' => $request->input('productInfo', [[
                'id' => 'DEV001',
                'name' => 'Development Test Product',
                'price' => $validated['amount'],
                'type' => 'Test',
                'quantity' => 1
            ]]),
            'payer' => $validated['payer'] ?? 'Dev Test User',
        ], $validated);

        // Remove empty storeId
        if (empty($payload['storeId'])) {
            unset($payload['storeId']);
        }

        Log::info('DEV VA Create Request:', $payload);

        $paylabsService = new PaylabsService();
        $result = $paylabsService->vaCreateV23($payload);

        return response()->json($result);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('DEV VA Create Error:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to create VA payment',
            'error' => $e->getMessage()
        ], 500);
    }
}
    /**
     * DEV: Query Virtual Account order status (v2.3).
     */
    public function devPaylabsVaQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchantTradeNo' => 'required_without:platformTradeNo|string|max:32',
            'platformTradeNo' => 'required_without:merchantTradeNo|string|max:32',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->paylabsService->vaQueryV23($request->all());
            return response()->json($result, $result['success'] ? 200 : 502);
        } catch (\Exception $e) {
            Log::error('DEV PAYLABS VA QUERY error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Activate membership after successful payment
     */
    private function activateMembershipAfterPayment(MembershipPayment $membershipPayment): void
    {
        try {
            $user = $membershipPayment->user;
            if (!$user) {
                Log::warning('User not found for membership payment', [
                    'membership_payment_id' => $membershipPayment->id,
                ]);
                return;
            }

            $user->update([
                'membership_status' => 'active',
                'membership_start_date' => now(),
                'membership_end_date' => now()->addMonths(12),
                'membership_fee' => $membershipPayment->total_amount,
                'membership_payment_method' => $membershipPayment->payment_method,
                'membership_payment_status' => 'success',
                'membership_transaction_id' => $membershipPayment->transaction_id,
                'membership_level' => 'Bronze',
                'member_point' => 0,
                'loyalty_point' => 0,
            ]);

            Log::info('Membership activated after payment', [
                'user_id' => $user->id,
                'transaction_id' => $membershipPayment->transaction_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to activate membership: ' . $e->getMessage(), [
                'membership_payment_id' => $membershipPayment->id,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
