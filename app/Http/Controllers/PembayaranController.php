<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\MetodePembayaran;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\PaylabsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    protected $paylabsService;

    public function __construct(PaylabsService $paylabsService)
    {
        $this->paylabsService = $paylabsService;
    }

    /**
     * Tampilkan halaman pembayaran
     */
    public function index($kode_booking)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $pemesanan = Pemesanan::with(['jadwal', 'detailPenumpang', 'jadwal.rutes', 'jadwal.shuttle', 'user'])
            ->where('kode_booking', $kode_booking)
            ->firstOrFail();

        // Check if user is authorized to view this payment
        if ($pemesanan->customer_id != Auth::id()) {
            return redirect()->route('customer.riwayat')
                ->with('error', 'Anda tidak memiliki akses ke pembayaran ini');
        }

        // Cek status pemesanan
        if ($pemesanan->status != 'menunggu_pembayaran') {
            return redirect()->route('customer.detail_pemesanan', ['kode_booking' => $kode_booking])
                ->with('info', 'Pemesanan ini sudah diproses');
        }

        // Cek apakah sudah kadaluarsa
        if ($pemesanan->waktu_kadaluarsa && $pemesanan->waktu_kadaluarsa < now()) {
            $pemesanan->status = 'dibatalkan';
            $pemesanan->save();

            return redirect()->route('customer.riwayat')
                ->with('error', 'Pemesanan telah kadaluarsa');
        }

        // Ambil data pembayaran jika sudah ada
        $pembayaran = Pembayaran::where('pemesanan_id', $pemesanan->id)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->first();

        // Jika belum ada pembayaran, buat baru
        if (!$pembayaran) {
            $pembayaran = $this->buatPembayaran($pemesanan);
        }

        // Jika ada Paylabs transaction ID, cek status terbaru
        if ($pembayaran->paylabs_transaction_id) {
            $this->refreshPaymentStatus($pembayaran);
        }

        // Ambil metode pembayaran yang aktif
        $metodePembayaran = MetodePembayaran::where('aktif', true)
            ->orderBy('urutan', 'asc')
            ->get();

        // Siapkan data untuk view
        $rutePertama = $pemesanan->jadwal->rutes->first();
        $ruteTerakhir = $pemesanan->jadwal->rutes->last();

        // Hitung sisa waktu dalam detik
        $waktu_kadaluarsa = Carbon::parse($pembayaran->waktu_kadaluarsa);
        $sekarang = Carbon::now();
        $sisa_waktu_detik = max(0, $sekarang->diffInSeconds($waktu_kadaluarsa, false));

        // Format payment data untuk ditampilkan
        $paymentData = null;
        if ($pembayaran->paylabs_response) {
            $response = json_decode($pembayaran->paylabs_response, true);
            $paymentData = $this->formatPaymentDataForView($response, $pembayaran->metode);
        }

        // Get payment method instructions
        $currentMethod = MetodePembayaran::where('kode', $pembayaran->metode)->first();
        $instruksi = $currentMethod ? $currentMethod->instruksi_array : [];

        // Prepare tariff and pricing data
        $selectedTarif = null;
        $availableTarifs = [];
        $totalTarif = 0;
        $jumlahPenumpang = $pemesanan->jumlah_penumpang;

        try {
            if ($pemesanan->jadwal && $pemesanan->jadwal->rutes && $pemesanan->jadwal->rutes->isNotEmpty()) {
                $ruteObj = $pemesanan->jadwal->rutes->first();
                $mt = $ruteObj->getActiveMasterTarif();
                if ($mt) {
                    $selectedTarif = $mt->formatTarif();
                    $base = $mt->harga_dasar ?? $ruteObj->harga_dasar ?? $pemesanan->jadwal->harga_total ?? 0;
                    $selectedTarif['calculated_price'] = (float) $mt->hitungTarif($base);
                } else {
                    $selectedTarif = ['harga_dasar' => $ruteObj->harga_dasar ?? null];
                }

                // Collect all active master tariffs for this route (availableTarifs)
                $tarifCollection = $ruteObj->masterTarifs()->where('status','aktif')
                    ->where(function($q){
                        $q->whereNull('tanggal_berlaku')->orWhere('tanggal_berlaku','<=',now());
                    })->where(function($q){
                        $q->whereNull('tanggal_kadaluarsa')->orWhere('tanggal_kadaluarsa','>=',now());
                    })->get();

                if ($tarifCollection->isNotEmpty()) {
                    $availableTarifs = $tarifCollection->map(function($t) use ($ruteObj, $pemesanan){
                        $fmt = $t->formatTarif();
                        $base = $t->harga_dasar ?? $ruteObj->harga_dasar ?? $pemesanan->jadwal->harga_total ?? 0;
                        $final = (float) $t->hitungTarif($base);
                        $fmt['final_price'] = $final;
                        $fmt['delta'] = $final - (float) $base;
                        return $fmt;
                    })->toArray();

                    // Calculate total tarif from all available tarifs
                    foreach ($availableTarifs as $tarif) {
                        $totalTarif += ($tarif['final_price'] ?? 0) * $jumlahPenumpang;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to get selected/available tariff in PembayaranController: ' . $e->getMessage());
        }

        $data = [
            'pemesanan' => $pemesanan,
            'pembayaran' => $pembayaran,
            'metodePembayaran' => $metodePembayaran,
            'from' => $rutePertama->kota_asal ?? 'Kota Asal',
            'to' => $ruteTerakhir->kota_tujuan ?? 'Kota Tujuan',
            'date' => $pemesanan->jadwal->tanggal_keberangkatan ?? now(),
            'time' => Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->format('H:i'),
            'customer_name' => $pemesanan->nama_pemesan ?? 'Nama Pemesan',
            'customer_phone' => $pemesanan->telepon_pemesan ?? 'Nomor Telepon',
            'customer_email' => $pemesanan->email_pemesan ?? 'Email',
            'total' => $pemesanan->total_bayar,
            'penumpang' => $pemesanan->detailPenumpang,
            'shuttle' => $pemesanan->jadwal->shuttle,
            'sisa_waktu_detik' => $sisa_waktu_detik,
            'payment_data' => $paymentData,
            'instruksi' => $instruksi,
            'selectedTarif' => $selectedTarif,
            'availableTarifs' => $availableTarifs,
            'totalTarif' => $totalTarif,
            'diskon' => $pemesanan->diskon ?? 0,
        ];

        return view('customer.pembayaran', $data);
    }

    /**
     * Refresh payment status from Paylabs
     */
    private function refreshPaymentStatus($pembayaran)
    {
        if (!$pembayaran->paylabs_transaction_id || $pembayaran->status === 'berhasil') {
            return;
        }

        try {
            $status = $this->paylabsService->checkStatus($pembayaran->paylabs_transaction_id);

            if ($status['success'] && $status['status'] !== $pembayaran->paylabs_status) {
                $localStatus = $this->mapPaylabsStatusToLocal($status['status']);

                $pembayaran->update([
                    'paylabs_status' => $status['status'],
                    'status' => $localStatus,
                    'waktu_pembayaran' => $status['status'] === 'PAID' ? now() : null
                ]);

                if ($status['status'] === 'PAID') {
                    $this->updatePemesananAfterPayment($pembayaran);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to refresh payment status: ' . $e->getMessage());
        }
    }

    /**
     * Buat data pembayaran baru
     */
    private function buatPembayaran($pemesanan)
    {
        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::create([
                'pemesanan_id' => $pemesanan->id,
                'kode_pembayaran' => 'PAY' . date('Ymd') . strtoupper(Str::random(6)),
                'jumlah' => $pemesanan->total_bayar,
                'metode' => 'qris', // Default metode
                'status' => 'menunggu',
                'waktu_kadaluarsa' => now()->addMinutes(30),
            ]);

            // Create Paylabs payment request for default method (QRIS)
            $method = MetodePembayaran::where('kode', 'qris')->first();
            if ($method && $method->is_paylabs) {
                $paylabsResponse = $this->paylabsService->createPayment(
                    $pembayaran,
                    $method->paylabs_channel_code,
                    $method->paylabs_channel_name
                );

                if (!$paylabsResponse['success']) {
                    Log::warning('Failed to create Paylabs QRIS payment, continuing with basic payment', [
                        'error' => $paylabsResponse['error'],
                        'payment_id' => $pembayaran->id
                    ]);
                    // Don't throw exception, allow payment to be created without Paylabs
                } else {
                    Log::info('Paylabs QRIS payment created successfully', [
                        'payment_id' => $pembayaran->id,
                        'transaction_id' => $paylabsResponse['transaction_id'] ?? null
                    ]);
                }
            }

            DB::commit();
            return $pembayaran;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function formatPaymentDataForView($response, $method)
    {
        $data = [];

        if (isset($response['qrCode'])) {
            $data['qr_code'] = $response['qrCode'];
            $data['qr_content'] = $response['qrContent'] ?? null;
        }

        if (isset($response['vaNumber'])) {
            $data['virtual_account'] = $response['vaNumber'];
            $data['bank_name'] = $response['bankName'] ?? 'Bank Transfer';
        }

        if (isset($response['deeplink'])) {
            $data['deeplink'] = $response['deeplink'];
        }

        if (isset($response['checkoutUrl'])) {
            $data['checkout_url'] = $response['checkoutUrl'];
        }

        return $data;
    }

    /**
     * Proses pemilihan metode pembayaran
     */
    public function pilihMetode(Request $request, $kode_booking)
    {
        if (!Auth::check()) {
            // Check if this is an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu'
                ], 401);
            }
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $request->validate([
            'metode' => 'required|string|exists:metode_pembayaran,kode'
        ]);

        $pemesanan = Pemesanan::where('kode_booking', $kode_booking)
            ->where('customer_id', Auth::id())
            ->first();

        if (!$pemesanan) {
            // Check if this is an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak ditemukan atau Anda tidak memiliki akses'
                ], 404);
            }
            return redirect()->route('customer.riwayat')
                ->with('error', 'Pemesanan tidak ditemukan atau Anda tidak memiliki akses');
        }

        $pembayaran = Pembayaran::where('pemesanan_id', $pemesanan->id)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->firstOrFail();

        DB::beginTransaction();

        try {
            // Update metode pembayaran
            $pembayaran->update([
                'metode' => $request->metode,
                'waktu_kadaluarsa' => now()->addMinutes(30), // Changed from 20 to 30 minutes to match timer
            ]);

            // Get payment method
            $method = MetodePembayaran::where('kode', $request->metode)->first();

            if ($method && $method->is_paylabs) {
                // Create Paylabs payment request
                $paylabsResponse = $this->paylabsService->createPayment(
                    $pembayaran,
                    $method->paylabs_channel_code,
                    $method->paylabs_channel_name
                );

                if (!$paylabsResponse['success']) {
                    throw new \Exception('Gagal membuat pembayaran di Paylabs: ' . $paylabsResponse['error']);
                }
            }

            DB::commit();

            // Check if this is an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Metode pembayaran berhasil dipilih'
                ]);
            }

            return redirect()->back()->with('success', 'Metode pembayaran berhasil dipilih');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Pilih metode pembayaran error: ' . $e->getMessage());

            // Check if this is an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memilih metode pembayaran: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memilih metode pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Update pemesanan setelah pembayaran berhasil
     */
    private function updatePemesananAfterPayment($pembayaran)
    {
        $pembayaran->pemesanan->update([
            'status' => 'dibayar',
            'tanggal_pembayaran' => now()->toDateString(),
            'waktu_pembayaran' => now(),
            'metode_pembayaran' => $pembayaran->metode
        ]);

        // Mark seats as booked (terpesan) after successful payment
        \App\Models\KursiTerpesan::markSeatsAsBooked($pembayaran->pemesanan_id);

        // Create transaction
        Transaksi::create([
            'pembayaran_id' => $pembayaran->id,
            'pemesanan_id' => $pembayaran->pemesanan_id,
            'kode_transaksi' => 'TRX' . date('Ymd') . strtoupper(Str::random(6)),
            'jumlah' => $pembayaran->jumlah,
            'biaya_admin' => 0,
            'total' => $pembayaran->jumlah,
            'waktu_transaksi' => now()
        ]);

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
     * Cek status pembayaran via AJAX
     */
    public function cekStatus($kodePembayaran)
    {
        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)
                ->firstOrFail();

            // Refresh status from Paylabs if applicable
            if ($pembayaran->paylabs_transaction_id && $pembayaran->status !== 'berhasil') {
                $this->refreshPaymentStatus($pembayaran);
                $pembayaran->refresh();
            }

            // Hitung sisa waktu dengan benar
            $sekarang = Carbon::now();
            $kadaluarsa = Carbon::parse($pembayaran->waktu_kadaluarsa);
            $remaining_seconds = max(0, $sekarang->diffInSeconds($kadaluarsa, false));

            return response()->json([
                'success' => true,
                'data' => [
                    'status' => $pembayaran->status,
                    'paylabs_status' => $pembayaran->paylabs_status,
                    'status_text' => $this->getStatusText($pembayaran->status),
                    'waktu_kadaluarsa' => $pembayaran->waktu_kadaluarsa,
                    'is_kadaluarsa' => $remaining_seconds <= 0,
                    'remaining_time' => $remaining_seconds,
                    'is_paid' => $pembayaran->status === 'berhasil',
                    'qr_code' => $pembayaran->qr_code,
                    'no_virtual_account' => $pembayaran->no_virtual_account,
                    'nama_bank' => $pembayaran->nama_bank,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get status text
     */
    private function getStatusText($status)
    {
        $statuses = [
            'menunggu' => 'Menunggu Pembayaran',
            'diproses' => 'Pembayaran Diproses',
            'berhasil' => 'Pembayaran Berhasil',
            'gagal' => 'Pembayaran Gagal',
            'kadaluarsa' => 'Pembayaran Kadaluarsa'
        ];

        return $statuses[$status] ?? $status;
    }

    /**
     * Webhook untuk callback dari Paylabs
     */
    public function webhook(Request $request)
    {
        Log::info('=== PAYLABS WEBHOOK RECEIVED ===', $request->all());

        DB::beginTransaction();

        try {
            // Verify signature
            $signature = $request->input('signature');
            $data = $request->except('signature');

            if (!$this->paylabsService->verifySignatureV23($data, $signature, $request->header('X-TIMESTAMP', ''), '/payment/v2.3/callback')) {
                Log::error('PAYLABS Webhook signature verification failed');
                return response()->json(['success' => false, 'message' => 'Invalid signature'], 400);
            }

            $validated = $request->validate([
                'merchantId' => 'required|string',
                'transactionId' => 'required|string',
                'merchantTradeNo' => 'required|string',
                'status' => 'required|string',
                'signature' => 'required|string'
            ]);

            $pembayaran = Pembayaran::where('kode_pembayaran', $request->merchantTradeNo)
                ->orWhere('paylabs_transaction_id', $request->transactionId)
                ->first();

            if (!$pembayaran) {
                Log::error('Payment not found for webhook', $request->all());
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Map Paylabs status to local status
            $localStatus = $this->mapPaylabsStatusToLocal($request->status);

            $pembayaran->update([
                'paylabs_status' => $request->status,
                'status' => $localStatus,
                'paylabs_response' => json_encode($request->all()),
                'raw_callback_payload' => json_encode($request->all()),
                'waktu_pembayaran' => $request->status === 'PAID' ? now() : null
            ]);

            if ($request->status === 'PAID') {
                // Update pemesanan
                $this->updatePemesananAfterPayment($pembayaran);

                // Add loyalty points
                $user = User::find($pembayaran->pemesanan->customer_id);
                if ($user) {
                    $this->addLoyaltyPoints($user);
                }
            }

            DB::commit();

            Log::info('PAYLABS Webhook processed successfully', [
                'transactionId' => $request->transactionId,
                'status' => $request->status
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PAYLABS Webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

            Log::info('Loyalty points added via webhook', [
                'user_id' => $user->id,
                'points_added' => 100,
                'loyalty_points_added' => $loyaltyPoints,
                'new_level' => $newLevel
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to add loyalty points in webhook: ' . $e->getMessage());
        }
    }

    /**
     * Calculate loyalty points
     */
    private function calculateLoyaltyPoints($membershipLevel)
    {
        switch ($membershipLevel) {
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
     * Simulasi pembayaran berhasil (untuk testing)
     */
    public function simulasiPembayaran($kodePembayaran, $status = 'berhasil')
    {
        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)
                ->firstOrFail();

            // Cek jika pembayaran sudah berhasil
            if ($pembayaran->status === 'berhasil') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran sudah berhasil diproses sebelumnya'
                ], 400);
            }

            // Update pembayaran
            $pembayaran->update([
                'status' => $status,
                'paylabs_status' => 'PAID',
                'waktu_pembayaran' => now(),
                'paylabs_response' => json_encode([
                    'simulated' => true,
                    'status' => 'PAID',
                    'transactionId' => 'SIM' . strtoupper(Str::random(10)),
                    'merchantTradeNo' => $kodePembayaran
                ])
            ]);

            // Update pemesanan
            $this->updatePemesananAfterPayment($pembayaran);

            // Add loyalty points
            $user = User::find($pembayaran->pemesanan->customer_id);
            if ($user) {
                $this->addLoyaltyPoints($user);
            }

            DB::commit();

            Log::info('Payment simulation successful', [
                'kode_pembayaran' => $kodePembayaran,
                'status' => $status,
                'pemesanan_id' => $pembayaran->pemesanan_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disimulasikan',
                'data' => [
                    'kode_pembayaran' => $kodePembayaran,
                    'status' => $status,
                    'points_added' => $user ? 100 : 0,
                    'loyalty_points_added' => $user ? $this->calculateLoyaltyPoints($user->membership_level) : 0,
                    'membership_level' => $user ? $user->membership_level : null
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment simulation failed: ' . $e->getMessage(), [
                'kode_pembayaran' => $kodePembayaran,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mensimulasikan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
