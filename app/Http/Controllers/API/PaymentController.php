<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Services\PaylabsSimulator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paylabsSimulator;

    public function __construct(PaylabsSimulator $paylabsSimulator)
    {
        $this->paylabsSimulator = $paylabsSimulator;
    }

    /**
     * Create payment request
     */
    public function createPayment(Request $request)
    {
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

        DB::beginTransaction();

        try {
            // Get pemesanan
            $pemesanan = Pemesanan::where('kode_booking', $request->kode_booking)->first();

            // Check if payment already exists
            $existingPayment = Pembayaran::where('pemesanan_id', $pemesanan->id)
                ->where('status', 'menunggu')
                ->first();

            if ($existingPayment) {
                $pembayaran = $existingPayment;
            } else {
                // Create new payment
                $pembayaran = Pembayaran::create([
                    'pemesanan_id' => $pemesanan->id,
                    'kode_pembayaran' => Pembayaran::generateKodePembayaran(),
                    'jumlah' => $pemesanan->total_bayar,
                    'metode' => $request->payment_method,
                    'status' => 'menunggu',
                    'waktu_kadaluarsa' => now()->addMinutes(30),
                ]);
            }

            // Get payment method details
            $method = \App\Models\MetodePembayaran::where('kode', $request->payment_method)->first();

            if (!$method) {
                throw new \Exception('Payment method not found');
            }

            // Create Paylabs payment request
            $paylabsResponse = $this->paylabsSimulator->createPayment(
                $pembayaran,
                $method->paylabs_channel_code,
                $method->paylabs_channel_name
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment request created successfully',
                'data' => [
                    'payment' => $pembayaran,
                    'paylabs_response' => $paylabsResponse
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus($kodePembayaran)
    {
        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)->first();

            if (!$pembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            // If using Paylabs, check status
            if ($pembayaran->paylabs_transaction_id) {
                $status = $this->paylabsSimulator->checkStatus($pembayaran->paylabs_transaction_id);
            } else {
                $status = [
                    'status' => $pembayaran->status,
                    'transactionId' => null,
                    'amount' => (int) $pembayaran->jumlah
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'payment' => $pembayaran,
                    'status' => $status,
                    'is_expired' => $pembayaran->waktu_kadaluarsa < now(),
                    'remaining_time' => max(0, now()->diffInSeconds($pembayaran->waktu_kadaluarsa))
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available payment methods
     */
    public function getPaymentMethods()
    {
        try {
            $methods = \App\Models\MetodePembayaran::aktif()
                ->orderBy('urutan')
                ->get()
                ->map(function ($method) {
                    return [
                        'code' => $method->kode,
                        'name' => $method->nama,
                        'type' => $method->jenis,
                        'description' => $method->deskripsi,
                        'admin_fee' => (float) $method->biaya_admin,
                        'admin_fee_formatted' => 'Rp ' . number_format($method->biaya_admin, 0, ',', '.'),
                        'estimation' => $method->estimasi_waktu,
                        'is_paylabs' => (bool) $method->is_paylabs,
                        'image' => $method->gambar ? asset('storage/' . $method->gambar) : null,
                        'instructions' => json_decode($method->instruksi, true) ?? []
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $methods
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment methods: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simulate payment (for demo)
     */
    public function simulatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_pembayaran' => 'required|exists:pembayaran,kode_pembayaran',
            'status' => 'required|in:success,failed,expired'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $request->kode_pembayaran)->first();

            $paylabsStatus = $this->mapStatusToPaylabs($request->status);

            // Update payment status
            if ($pembayaran->paylabs_transaction_id) {
                $this->paylabsSimulator->simulateCallback($pembayaran->paylabs_transaction_id, $paylabsStatus);
            } else {
                $localStatus = $this->mapStatusToLocal($request->status);

                $pembayaran->update([
                    'status' => $localStatus,
                    'waktu_pembayaran' => $request->status === 'success' ? now() : null
                ]);

                if ($request->status === 'success') {
                    $pembayaran->pemesanan->update([
                        'status' => 'dibayar',
                        'tanggal_pembayaran' => now()->toDateString(),
                        'waktu_pembayaran' => now(),
                        'metode_pembayaran' => $pembayaran->metode
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment simulation completed',
                'data' => [
                    'payment' => $pembayaran->fresh(),
                    'pemesanan' => $pembayaran->pemesanan
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to simulate payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get QR code for payment
     */
    public function getQRCode($kodePembayaran)
    {
        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)->first();

            if (!$pembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            // If QR code already exists
            if ($pembayaran->qr_code) {
                $qrCodeUrl = $pembayaran->qr_code;
            } else {
                // Generate QR code URL
                $qrData = $pembayaran->qris_raw_data ? json_decode($pembayaran->qris_raw_data, true) : [];

                // Create QR code data
                $qrContent = $this->generateQRContent($pembayaran, $qrData);

                // Use QR server
                $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" .
                             urlencode($qrContent) . "&format=png&margin=10";

                $pembayaran->update(['qr_code' => $qrCodeUrl]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'qr_code_url' => $qrCodeUrl,
                    'payment_code' => $pembayaran->kode_pembayaran,
                    'amount' => (int) $pembayaran->jumlah,
                    'expired_time' => $pembayaran->waktu_kadaluarsa
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR content
     */
    private function generateQRContent($pembayaran, $qrData)
    {
        if (!empty($qrData)) {
            // Use existing QR data
            return json_encode($qrData);
        }

        // Generate simple QR content for demo
        $content = [
            'type' => 'PAYMENT',
            'merchant' => 'SMART SHUTTLE',
            'payment_code' => $pembayaran->kode_pembayaran,
            'amount' => (int) $pembayaran->jumlah,
            'currency' => 'IDR',
            'timestamp' => now()->timestamp,
            'expiry' => $pembayaran->waktu_kadaluarsa->timestamp
        ];

        return json_encode($content);
    }

    /**
     * Map status to Paylabs format
     */
    private function mapStatusToPaylabs($status)
    {
        $mapping = [
            'success' => 'PAID',
            'failed' => 'FAILED',
            'expired' => 'EXPIRED'
        ];

        return $mapping[$status] ?? 'PENDING';
    }

    /**
     * Map status to local format
     */
    private function mapStatusToLocal($status)
    {
        $mapping = [
            'success' => 'berhasil',
            'failed' => 'gagal',
            'expired' => 'kadaluarsa'
        ];

        return $mapping[$status] ?? 'menunggu';
    }

    /**
     * Payment callback (webhook) from Paylabs
     */
    public function callback(Request $request)
    {
        \Log::info('Paylabs callback received', $request->all());

        $validator = Validator::make($request->all(), [
            'merchantId' => 'required|string',
            'transactionId' => 'required|string',
            'merchantOrderId' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'paymentChannel' => 'required|string',
            'status' => 'required|string',
            'signature' => 'required|string'
        ]);

        if ($validator->fails()) {
            \Log::error('Paylabs callback validation failed', $validator->errors()->toArray());
            return response()->json(['success' => false, 'message' => 'Invalid callback data'], 400);
        }

        DB::beginTransaction();

        try {
            // Find payment
            $pembayaran = Pembayaran::where('kode_pembayaran', $request->merchantOrderId)
                ->orWhere('paylabs_transaction_id', $request->transactionId)
                ->first();

            if (!$pembayaran) {
                \Log::error('Payment not found for callback', ['merchantOrderId' => $request->merchantOrderId]);
                throw new \Exception('Payment not found');
            }

            // Update payment status
            $localStatus = $this->mapPaylabsStatusToLocal($request->status);

            $pembayaran->update([
                'paylabs_status' => $request->status,
                'status' => $localStatus,
                'paylabs_response' => json_encode($request->all()),
                'waktu_pembayaran' => $request->status === 'PAID' ? now() : null
            ]);

            // Update pemesanan if payment successful
            if ($request->status === 'PAID') {
                $pembayaran->pemesanan->update([
                    'status' => 'dibayar',
                    'tanggal_pembayaran' => now()->toDateString(),
                    'waktu_pembayaran' => now(),
                    'metode_pembayaran' => $pembayaran->metode
                ]);

                // Add loyalty points
                $user = $pembayaran->pemesanan->customer;
                if ($user) {
                    $this->addLoyaltyPoints($user);
                }
            }

            DB::commit();

            \Log::info('Paylabs callback processed successfully', [
                'transactionId' => $request->transactionId,
                'status' => $request->status
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Paylabs callback processing error: ' . $e->getMessage(), [
                'transactionId' => $request->transactionId ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Callback processing failed'
            ], 500);
        }
    }

    /**
     * Map Paylabs status to local status
     */
    private function mapPaylabsStatusToLocal($paylabsStatus)
    {
        $mapping = [
            'PENDING' => 'menunggu',
            'PAID' => 'berhasil',
            'EXPIRED' => 'kadaluarsa',
            'FAILED' => 'gagal',
            'CANCELLED' => 'dibatalkan'
        ];

        return $mapping[$paylabsStatus] ?? 'menunggu';
    }

    /**
     * Add loyalty points
     */
    private function addLoyaltyPoints($user)
    {
        $user->member_point += 100;

        // Add loyalty points based on membership level
        $loyaltyPoints = $this->calculateLoyaltyPoints($user->membership_level);
        $user->loyalty_point += $loyaltyPoints;

        // Update membership level if needed
        $newLevel = $this->updateMembershipLevel($user);
        $user->membership_level = $newLevel;

        $user->save();
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
}
