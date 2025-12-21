<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\MetodePembayaran;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\PaylabsSimulator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    protected $paylabsSimulator;

    public function __construct(PaylabsSimulator $paylabsSimulator)
    {
        $this->paylabsSimulator = $paylabsSimulator;
    }

    /**
     * Tampilkan halaman pembayaran
     */
    public function index($kode_booking)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $pemesanan = Pemesanan::with(['jadwal', 'detailPenumpang', 'jadwal.rutes', 'jadwal.shuttle'])
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

        // Jika belum ada pembayaran, buat baru dengan default metode QRIS
        if (!$pembayaran) {
            $pembayaran = $this->buatPembayaran($pemesanan);
        }

        // Ambil metode pembayaran yang aktif
        $metodePembayaran = MetodePembayaran::where('aktif', true)
            ->orderBy('urutan', 'asc')
            ->get();

        // Siapkan data untuk view
        $rutePertama = $pemesanan->jadwal->rutes->first();
        $ruteTerakhir = $pemesanan->jadwal->rutes->last();

        $data = [
            'pemesanan' => $pemesanan,
            'pembayaran' => $pembayaran,
            'metodePembayaran' => $metodePembayaran,
            'from' => $rutePertama->kota_asal ?? 'Kota Asal',
            'to' => $ruteTerakhir->kota_tujuan ?? 'Kota Tujuan',
            'date' => Carbon::parse($pemesanan->jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY'),
            'time' => Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->format('H:i'),
            'customer_name' => $pemesanan->nama_pemesan ?? 'Nama Pemesan',
            'customer_phone' => $pemesanan->telepon_pemesan ?? 'Nomor Telepon',
            'customer_email' => $pemesanan->email_pemesan ?? 'Email',
            'total' => $pemesanan->total_bayar,
            'penumpang' => $pemesanan->detailPenumpang,
            'shuttle' => $pemesanan->jadwal->shuttle
        ];

        return view('customer.pembayaran', $data);
    }

    /**
     * Buat data pembayaran baru
     */
    private function buatPembayaran($pemesanan)
    {
        $pembayaran = Pembayaran::create([
            'pemesanan_id' => $pemesanan->id,
            'kode_pembayaran' => Pembayaran::generateKodePembayaran(),
            'jumlah' => $pemesanan->total_bayar,
            'metode' => 'qris', // Default metode
            'status' => 'menunggu',
            'waktu_kadaluarsa' => now()->addMinutes(30),
        ]);

        // Create Paylabs payment request for default method
        $method = MetodePembayaran::where('kode', 'qris')->first();
        if ($method && $method->is_paylabs) {
            $paylabsResponse = $this->paylabsSimulator->createPayment(
                $pembayaran,
                $method->paylabs_channel_code,
                $method->paylabs_channel_name
            );
        }

        return $pembayaran;
    }

    /**
     * Proses pemilihan metode pembayaran
     */
    public function pilihMetode(Request $request, $kode_booking)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $request->validate([
            'metode' => 'required|string|exists:metode_pembayaran,kode'
        ]);

        $pemesanan = Pemesanan::where('kode_booking', $kode_booking)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        $pembayaran = Pembayaran::where('pemesanan_id', $pemesanan->id)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->firstOrFail();

        DB::beginTransaction();

        try {
            // Update metode pembayaran
            $pembayaran->update([
                'metode' => $request->metode,
                'waktu_kadaluarsa' => now()->addMinutes(30),
            ]);

            // Get payment method
            $method = MetodePembayaran::where('kode', $request->metode)->first();

            if ($method && $method->is_paylabs) {
                // Create Paylabs payment request
                $paylabsResponse = $this->paylabsSimulator->createPayment(
                    $pembayaran,
                    $method->paylabs_channel_code,
                    $method->paylabs_channel_name
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'Metode pembayaran berhasil dipilih');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Pilih metode pembayaran error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memilih metode pembayaran');
        }
    }

    /**
     * Simulasi pembayaran berhasil
     */
    public function simulasiPembayaran($kodePembayaran, $status = 'success')
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)
                ->whereIn('status', ['menunggu', 'diproses'])
                ->firstOrFail();

            // Check if payment is expired
            if ($pembayaran->waktu_kadaluarsa < now()) {
                return redirect()->back()
                    ->with('error', 'Pembayaran telah kadaluarsa');
            }

            DB::beginTransaction();

            // Update status pembayaran
            $pembayaran->update([
                'status' => 'berhasil',
                'waktu_pembayaran' => now(),
                'paylabs_status' => 'PAID'
            ]);

            // Update status pemesanan
            $pembayaran->pemesanan->update([
                'status' => 'dibayar',
                'tanggal_pembayaran' => now()->toDateString(),
                'waktu_pembayaran' => now(),
                'metode_pembayaran' => $pembayaran->metode
            ]);

            // Buat transaksi
            Transaksi::create([
                'pembayaran_id' => $pembayaran->id,
                'pemesanan_id' => $pembayaran->pemesanan_id,
                'kode_transaksi' => Transaksi::generateKodeTransaksi(),
                'jumlah' => $pembayaran->jumlah,
                'biaya_admin' => 0,
                'total' => $pembayaran->jumlah,
                'waktu_transaksi' => now()
            ]);

            // Tambah loyalty points
            $user = Auth::user();
            $pointsData = null;

            if ($user) {
                $pointsData = $this->addLoyaltyPoints($user);
            }

            DB::commit();

            // Return JSON for AJAX request
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil diproses',
                    'points_added' => true,
                    'member_points_added' => 100,
                    'loyalty_points_added' => $pointsData['loyalty_points_added'] ?? 0,
                    'membership_level' => $pointsData['new_level'] ?? 'Bronze'
                ]);
            }

            // Redirect untuk web request
            return redirect()->route('customer.riwayat')
                ->with('success', 'Pembayaran berhasil! Tiket Anda sudah aktif.')
                ->with('points_added', true)
                ->with('member_points_added', 100)
                ->with('loyalty_points_added', $pointsData['loyalty_points_added'] ?? 0)
                ->with('membership_level', $pointsData['new_level'] ?? 'Bronze');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Simulasi pembayaran error: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tambah loyalty points untuk user
     */
    private function addLoyaltyPoints($user)
    {
        // Tambah 100 member points untuk setiap transaksi
        $user->member_point += 100;

        // Tambah loyalty points berdasarkan membership level
        $loyaltyPointsToAdd = $this->calculateLoyaltyPoints($user->membership_level);
        $user->loyalty_point += $loyaltyPointsToAdd;

        // Update membership level jika perlu
        $newLevel = $this->updateMembershipLevel($user);
        $user->membership_level = $newLevel;

        $user->save();

        // Catat penambahan points di log
        \Log::info('Loyalty points added', [
            'user_id' => $user->id,
            'member_point_added' => 100,
            'loyalty_point_added' => $loyaltyPointsToAdd,
            'new_member_points' => $user->member_point,
            'new_loyalty_points' => $user->loyalty_point,
            'membership_level' => $user->membership_level
        ]);

        return [
            'loyalty_points_added' => $loyaltyPointsToAdd,
            'new_level' => $user->membership_level
        ];
    }

    /**
     * Hitung loyalty points berdasarkan membership level
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
     * Update membership level berdasarkan total member points
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
     * Cek status pembayaran via AJAX
     */
    public function cekStatus($kodePembayaran)
    {
        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)
                ->firstOrFail();

            // Jika menggunakan Paylabs, cek status terbaru
            if ($pembayaran->paylabs_transaction_id) {
                $status = $this->paylabsSimulator->checkStatus($pembayaran->paylabs_transaction_id);
            } else {
                $status = ['status' => $pembayaran->status];
            }

            return response()->json([
                'success' => true,
                'status' => $pembayaran->status,
                'paylabs_status' => $pembayaran->paylabs_status,
                'status_text' => $pembayaran->status_text,
                'waktu_kadaluarsa' => $pembayaran->waktu_kadaluarsa,
                'is_kadaluarsa' => $pembayaran->waktu_kadaluarsa < now(),
                'remaining_time' => max(0, now()->diffInSeconds($pembayaran->waktu_kadaluarsa)),
                'is_paid' => $pembayaran->status === 'berhasil'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook untuk callback dari Paylabs
     */
    public function webhook(Request $request)
    {
        \Log::info('Paylabs webhook received', $request->all());

        $validated = $request->validate([
            'merchantId' => 'required|string',
            'transactionId' => 'required|string',
            'merchantOrderId' => 'required|string',
            'status' => 'required|string',
            'signature' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $request->merchantOrderId)
                ->orWhere('paylabs_transaction_id', $request->transactionId)
                ->first();

            if (!$pembayaran) {
                \Log::error('Payment not found for webhook', $request->all());
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Map Paylabs status to local status
            $localStatus = $this->mapPaylabsStatus($request->status);

            $pembayaran->update([
                'paylabs_status' => $request->status,
                'status' => $localStatus,
                'paylabs_response' => json_encode($request->all()),
                'waktu_pembayaran' => $request->status === 'PAID' ? now() : null
            ]);

            if ($request->status === 'PAID') {
                // Update pemesanan
                $pembayaran->pemesanan->update([
                    'status' => 'dibayar',
                    'tanggal_pembayaran' => now()->toDateString(),
                    'waktu_pembayaran' => now(),
                    'metode_pembayaran' => $pembayaran->metode
                ]);

                // Create transaksi
                Transaksi::create([
                    'pembayaran_id' => $pembayaran->id,
                    'pemesanan_id' => $pembayaran->pemesanan_id,
                    'kode_transaksi' => Transaksi::generateKodeTransaksi(),
                    'jumlah' => $pembayaran->jumlah,
                    'biaya_admin' => 0,
                    'total' => $pembayaran->jumlah,
                    'waktu_transaksi' => now()
                ]);

                // Add loyalty points
                $user = User::find($pembayaran->pemesanan->customer_id);
                if ($user) {
                    $this->addLoyaltyPoints($user);
                }
            }

            DB::commit();

            \Log::info('Paylabs webhook processed successfully', [
                'transactionId' => $request->transactionId,
                'status' => $request->status
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Paylabs webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
            'FAILED' => 'gagal',
            'CANCELLED' => 'dibatalkan'
        ];

        return $mapping[$paylabsStatus] ?? 'menunggu';
    }

    /**
     * Generate QR code untuk pembayaran
     */
    public function generateQRCode($kodePembayaran)
    {
        $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)
            ->firstOrFail();

        // Generate QR code URL
        $qrContent = "SMARTSHUTTLE|{$pembayaran->kode_pembayaran}|{$pembayaran->jumlah}";
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" .
                     urlencode($qrContent) . "&format=png";

        return response()->json([
            'qr_code_url' => $qrCodeUrl,
            'payment_code' => $pembayaran->kode_pembayaran
        ]);
    }
}
