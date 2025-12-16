<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\MetodePembayaran;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * Tampilkan halaman pembayaran
     */
    public function index($kodeBooking)
    {
        $pemesanan = Pemesanan::with(['jadwal', 'detailPenumpang', 'jadwal.rutes'])
            ->where('kode_booking', $kodeBooking)
            ->firstOrFail();

        // Cek status pemesanan
        if ($pemesanan->status != 'menunggu_pembayaran') {
            return redirect()->route('customer.riwayat')
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
            ->where('status', 'menunggu')
            ->first();

        // Jika belum ada pembayaran, buat baru
        if (!$pembayaran) {
            $pembayaran = $this->buatPembayaran($pemesanan);
        }

        // Ambil metode pembayaran yang aktif
        $metodePembayaran = MetodePembayaran::aktif()->get();

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
            'penumpang' => $pemesanan->detailPenumpang
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
            'metode' => 'qris',
            'status' => 'menunggu',
            'waktu_kadaluarsa' => now()->addMinutes(30), // 30 menit untuk bayar
        ]);

        return $pembayaran;
    }

    /**
     * Proses pemilihan metode pembayaran
     */
    public function pilihMetode(Request $request, $kodeBooking)
    {
        $request->validate([
            'metode' => 'required|string'
        ]);

        $pemesanan = Pemesanan::where('kode_booking', $kodeBooking)
            ->firstOrFail();

        $pembayaran = Pembayaran::where('pemesanan_id', $pemesanan->id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        // Update metode pembayaran
        $pembayaran->update([
            'metode' => $request->metode,
            'waktu_kadaluarsa' => now()->addMinutes(30),
        ]);

        // Generate nomor virtual account jika metode VA
        if (str_contains($request->metode, '_va')) {
            $this->generateVirtualAccount($pembayaran, $request->metode);
        }

        return redirect()->back();
    }

    /**
     * Generate nomor virtual account
     */
    private function generateVirtualAccount($pembayaran, $metode)
    {
        $prefix = '';
        $bank = '';

        switch ($metode) {
            case 'bca_va':
                $prefix = '812';
                $bank = 'BCA';
                break;
            case 'mandiri_va':
                $prefix = '88608';
                $bank = 'Mandiri';
                break;
            case 'bni_va':
                $prefix = '881';
                $bank = 'BNI';
                break;
            case 'bri_va':
                $prefix = '888';
                $bank = 'BRI';
                break;
        }

        // Format: Prefix + Kode Booking (angka saja) + Check Digit
        $kodeAngka = preg_replace('/[^0-9]/', '', $pembayaran->kode_pembayaran);
        $noVA = $prefix . substr($kodeAngka, 0, 10 - strlen($prefix));
        $noVA = str_pad($noVA, 16, '0', STR_PAD_RIGHT);

        $pembayaran->update([
            'no_virtual_account' => $noVA,
            'nama_bank' => $bank,
            'instruksi_pembayaran' => $this->getInstruksiPembayaran($metode, $noVA)
        ]);
    }

    /**
     * Get instruksi pembayaran
     */
    private function getInstruksiPembayaran($metode, $noVA)
    {
        $instruksi = [];

        switch ($metode) {
            case 'bca_va':
                $instruksi = [
                    "ATM BCA:",
                    "1. Pilih menu 'Transaksi Lainnya'",
                    "2. Pilih 'Transfer'",
                    "3. Pilih 'Ke Rekening BCA Virtual Account'",
                    "4. Masukkan nomor VA: $noVA",
                    "5. Ikuti instruksi selanjutnya",
                    "",
                    "Mobile Banking BCA:",
                    "1. Pilih menu 'm-BCA'",
                    "2. Pilih 'm-Transfer'",
                    "3. Pilih 'BCA Virtual Account'",
                    "4. Masukkan nomor VA: $noVA",
                    "5. Ikuti instruksi selanjutnya"
                ];
                break;

            case 'mandiri_va':
                $instruksi = [
                    "ATM Mandiri:",
                    "1. Pilih menu 'Bayar/Beli'",
                    "2. Pilih 'Multi Payment'",
                    "3. Masukkan kode perusahaan: 88888",
                    "4. Masukkan nomor VA: $noVA",
                    "5. Ikuti instruksi selanjutnya",
                    "",
                    "Livin by Mandiri:",
                    "1. Pilih menu 'Pembayaran'",
                    "2. Pilih 'Virtual Account'",
                    "3. Masukkan nomor VA: $noVA",
                    "4. Ikuti instruksi selanjutnya"
                ];
                break;
        }

        return implode("\n", $instruksi);
    }

    /**
     * Simulasi pembayaran berhasil
     */
    public function simulasiPembayaran($kodePembayaran)
    {
        // Cek jika request adalah AJAX
        if (request()->expectsJson()) {
            return $this->processPaymentAjax($kodePembayaran);
        }
        
        // Proses biasa untuk non-AJAX
        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)
                ->where('status', 'menunggu')
                ->firstOrFail();

            // Update status pembayaran
            $pembayaran->update([
                'status' => 'berhasil',
                'waktu_pembayaran' => now()
            ]);

            // Update status pemesanan
            $pembayaran->pemesanan->update([
                'status' => 'dibayar',
                'tanggal_pembayaran' => now()->toDateString(),
                'waktu_pembayaran' => now(),
                'metode_pembayaran' => $pembayaran->metode
            ]);

            // Buat transaksi
            $transaksi = Transaksi::create([
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

            // Redirect ke halaman riwayat
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
     * Proses pembayaran untuk AJAX request
     */
    private function processPaymentAjax($kodePembayaran)
    {
        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)
                ->where('status', 'menunggu')
                ->firstOrFail();

            // Update status pembayaran
            $pembayaran->update([
                'status' => 'berhasil',
                'waktu_pembayaran' => now()
            ]);

            // Update status pemesanan
            $pembayaran->pemesanan->update([
                'status' => 'dibayar',
                'tanggal_pembayaran' => now()->toDateString(),
                'waktu_pembayaran' => now(),
                'metode_pembayaran' => $pembayaran->metode
            ]);

            // Buat transaksi
            $transaksi = Transaksi::create([
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

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses',
                'points_added' => true,
                'member_points_added' => 100,
                'loyalty_points_added' => $pointsData['loyalty_points_added'] ?? 0,
                'membership_level' => $pointsData['new_level'] ?? 'Bronze'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('AJAX Pembayaran error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
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
            case 'Bronze':
                return 50;
            case 'Silver':
                return 60;
            case 'Gold':
                return 80;
            case 'Platinum':
                return 100;
            default:
                return 50;
        }
    }

    /**
     * Update membership level berdasarkan total member points
     */
    private function updateMembershipLevel($user)
    {
        $points = $user->member_point;
        
        if ($points >= 4500) {
            return 'Platinum';
        } elseif ($points >= 2500) {
            return 'Gold';
        } elseif ($points >= 1000) {
            return 'Silver';
        } else {
            return 'Bronze';
        }
    }

    /**
     * Webhook untuk callback dari payment gateway
     */
    public function webhook(Request $request)
    {
        $validated = $request->validate([
            'kode_pembayaran' => 'required|string',
            'status' => 'required|string',
            'signature' => 'required|string'
        ]);

        $pembayaran = Pembayaran::where('kode_pembayaran', $request->kode_pembayaran)
            ->first();

        if (!$pembayaran) {
            return response()->json(['error' => 'Pembayaran tidak ditemukan'], 404);
        }

        DB::beginTransaction();

        try {
            $pembayaran->update([
                'status' => $request->status,
                'waktu_pembayaran' => now()
            ]);

            if ($request->status === 'berhasil') {
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
                $user = User::find($pembayaran->pemesanan->customer_id);
                
                if ($user) {
                    $this->addLoyaltyPoints($user);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Cek status pembayaran
     */
    public function cekStatus($kodePembayaran)
    {
        $pembayaran = Pembayaran::where('kode_pembayaran', $kodePembayaran)
            ->firstOrFail();

        return response()->json([
            'status' => $pembayaran->status,
            'status_text' => $pembayaran->status_text,
            'waktu_kadaluarsa' => $pembayaran->waktu_kadaluarsa,
            'is_kadaluarsa' => $pembayaran->is_kadaluarsa
        ]);
    }
}