<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\DetailPenumpang;
use App\Models\KursiTerpesan;
use App\Models\Shuttle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KursiController extends Controller
{
   // GANTI method index DI KursiController.php:

/**
 * Tampilkan halaman pemilihan kursi (DIPERBAIKI tanpa real-time lock)
 */
public function index(Request $request)
{
    $validator = Validator::make($request->all(), [
        'pemesanan_id' => 'required|exists:pemesanan,id',
    ]);

    if ($validator->fails()) {
        return redirect()->route('customer.beranda')
            ->with('error', 'Parameter pemesanan tidak valid');
    }

    try {
        // Ambil data pemesanan
        $pemesanan = Pemesanan::with(['jadwal.shuttle', 'detailPenumpang'])
            ->where('id', $request->pemesanan_id)
            ->where('status', 'menunggu_pembayaran')
            ->firstOrFail();

        // HANYA AMBIL KURSI YANG SUDAH DIPESAN PERMANEN
        $kursiTerpesanPermanen = KursiTerpesan::where('jadwal_id', $pemesanan->jadwal_id)
            ->where('status', 'terpesan')
            ->whereHas('pemesanan', function($query) {
                $query->whereNotIn('status', ['dibatalkan', 'expired']);
            })
            ->pluck('nomor_kursi')
            ->toArray();

        // Ambil kursi yang sudah dipilih oleh PEMESANAN INI (jika ada)
        $kursiSaya = $pemesanan->detailPenumpang()
            ->whereNotNull('nomor_kursi')
            ->pluck('nomor_kursi')
            ->toArray();

        // Generate layout dengan status
        $shuttle = $pemesanan->jadwal->shuttle;
        $layoutKursi = $shuttle->getLayoutWithStatus($pemesanan->jadwal_id);

        // Update layout dengan status yang tepat
        foreach ($layoutKursi as &$kursi) {
            if (in_array($kursi['nomor'], $kursiTerpesanPermanen)) {
                // KURSI DIPESAN OLEH USER LAIN
                if (in_array($kursi['nomor'], $kursiSaya)) {
                    // TAPI INI ADALAH KURSI SAYA (sudah dipilih sebelumnya)
                    $kursi['status'] = 'selected';
                    $kursi['class'] = 'selected';
                    $kursi['icon'] = 'fa-user-check';
                } else {
                    // KURSI ORANG LAIN
                    $kursi['status'] = 'terpesan';
                    $kursi['class'] = 'sold';
                    $kursi['icon'] = 'fa-lock';
                }
            } elseif (in_array($kursi['nomor'], $kursiSaya)) {
                // KURSI SUDAH DIPILIH OLEH SAYA
                $kursi['status'] = 'selected';
                $kursi['class'] = 'selected';
                $kursi['icon'] = 'fa-user-check';
            } else {
                // KURSI TERSEDIA
                $kursi['status'] = 'tersedia';
                $kursi['class'] = 'available';
                $kursi['icon'] = 'fa-check';
            }
        }

        return view('customer.kursi', [
            'pemesanan' => $pemesanan,
            'layoutKursi' => $layoutKursi,
            'shuttle' => $shuttle,
            'kursiSaya' => $kursiSaya,
            'kursiTerpesan' => $kursiTerpesanPermanen
        ]);

    } catch (\Exception $e) {
        Log::error('Error in kursi index: ' . $e->getMessage());
        return redirect()->route('customer.riwayat')
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
    // GANTI method prosesKursi DI KursiController.php:

/**
 * **PERBAIKAN UTAMA: Proses pemilihan kursi dengan validasi kuat**
 */
public function prosesKursi(Request $request)
{
    $validator = Validator::make($request->all(), [
        'pemesanan_id' => 'required|exists:pemesanan,id',
        'kursi' => 'required|array|min:1',
        'kursi.*' => 'required|string|distinct'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Terjadi kesalahan dalam pemilihan kursi');
    }

    DB::beginTransaction();

    try {
        $pemesanan = Pemesanan::with(['detailPenumpang', 'jadwal.shuttle'])
            ->where('id', $request->pemesanan_id)
            ->where('status', 'menunggu_pembayaran')
            ->firstOrFail();

        // VALIDASI 1: Jumlah kursi harus sama dengan jumlah penumpang
        if (count($request->kursi) !== $pemesanan->jumlah_penumpang) {
            throw new \Exception('Jumlah kursi harus sama dengan jumlah penumpang');
        }

        // VALIDASI 2: Cek apakah kursi masih tersedia
        $kursiTerpesan = KursiTerpesan::where('jadwal_id', $pemesanan->jadwal_id)
            ->whereIn('nomor_kursi', $request->kursi)
            ->where('status', 'terpesan')
            ->whereHas('pemesanan', function($query) {
                $query->whereNotIn('status', ['dibatalkan', 'expired']);
            })
            ->pluck('nomor_kursi')
            ->toArray();

        if (!empty($kursiTerpesan)) {
            throw new \Exception(
                "Kursi " . implode(', ', $kursiTerpesan) .
                " sudah dipesan oleh penumpang lain. Silakan pilih kursi lain."
            );
        }

        // HAPUS KURSI LAMA jika ada (untuk edit)
        KursiTerpesan::where('pemesanan_id', $pemesanan->id)->delete();

        // SIMPAN KURSI BARU sebagai 'terpesan' (PERMANEN)
        $detailPenumpang = DetailPenumpang::where('pemesanan_id', $pemesanan->id)->get();

        foreach ($detailPenumpang as $index => $penumpang) {
            $nomorKursi = $request->kursi[$index] ?? null;

            if ($nomorKursi) {
                // SIMPAN KE KURSI_TERPESAN dengan status 'terpesan'
                KursiTerpesan::create([
                    'jadwal_id' => $pemesanan->jadwal_id,
                    'nomor_kursi' => $nomorKursi,
                    'detail_penumpang_id' => $penumpang->id,
                    'pemesanan_id' => $pemesanan->id,
                    'status' => 'terpesan'
                ]);

                // Update nomor kursi di detail penumpang
                $penumpang->update(['nomor_kursi' => $nomorKursi]);
            }
        }

        // Update timestamp pemesanan
        $pemesanan->touch();

        DB::commit();

        return redirect('/customer/detail-pesanan/' . $pemesanan->kode_booking)
            ->with('success', 'Kursi berhasil dipilih! Silakan lanjutkan ke pembayaran.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in prosesKursi: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Gagal memilih kursi: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * **NEW: Real-time seat status for polling**
     */
    public function realTimeSeatStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwals,id',
            'pemesanan_id' => 'required|exists:pemesanan,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal'
            ], 422);
        }

        try {
            $jadwal = Jadwal::with('shuttle')->findOrFail($request->jadwal_id);
            $layoutKursi = $jadwal->shuttle->getLayoutWithStatus($request->jadwal_id);

            $seatStatus = [];
            $expiredSeats = [];

            // Get seats booked by current pemesanan
            $mySeats = KursiTerpesan::where('jadwal_id', $request->jadwal_id)
                ->where('pemesanan_id', $request->pemesanan_id)
                ->pluck('nomor_kursi')
                ->toArray();

            foreach ($layoutKursi as $kursi) {
                $isMine = in_array($kursi['nomor'], $mySeats);
                $seatStatus[$kursi['nomor']] = [
                    'status' => $kursi['status'],
                    'is_mine' => $isMine
                ];
            }

            // Check for expired locks (seats locked more than 5 minutes ago)
            $expiredLocks = KursiTerpesan::where('jadwal_id', $request->jadwal_id)
                ->where('created_at', '<', now()->subMinutes(5))
                ->whereHas('pemesanan', function($query) {
                    $query->where('status', 'menunggu_pembayaran');
                })
                ->pluck('nomor_kursi')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'seat_status' => $seatStatus,
                    'expired_seats' => $expiredLocks
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in realTimeSeatStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * **NEW: Show order details after seat selection**
     */
    public function detailPesanan($kode)
    {
        try {
            // Find the order by booking code
            $pemesanan = Pemesanan::with([
                'jadwal.shuttle',
                'detailPenumpang',
                'transaksi'
            ])->where('kode_booking', $kode)
              ->where('status', 'menunggu_pembayaran')
              ->first();

            if (!$pemesanan) {
                return redirect()->route('customer.beranda')
                    ->with('error', 'Pemesanan tidak ditemukan atau sudah diproses.');
            }

            // Prepare data for the view (similar to CustomerController)
            $jadwal = $pemesanan->jadwal;
            $rute = $jadwal->rutes->first();

            $from = $rute ? $rute->kota_asal : 'Kota Asal';
            $to = $rute ? $rute->kota_tujuan : 'Kota Tujuan';
            $date = $jadwal->tanggal_keberangkatan;
            $time = $jadwal->waktu_keberangkatan;

            $customer_name = $pemesanan->nama_pemesan;
            $customer_phone = $pemesanan->telepon_pemesan;
            $customer_email = $pemesanan->email_pemesan;

            $penumpang = $pemesanan->detailPenumpang;
            $total = $pemesanan->total_bayar;

            return view('customer.detail_pesanan', [
                'pemesanan' => $pemesanan,
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'time' => $time,
                'customer_name' => $customer_name,
                'customer_phone' => $customer_phone,
                'customer_email' => $customer_email,
                'penumpang' => $penumpang,
                'total' => $total
            ]);

        } catch (\Exception $e) {
            Log::error('Error in detailPesanan: ' . $e->getMessage(), [
                'kode' => $kode,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('customer.riwayat')
                ->with('error', 'Terjadi kesalahan saat memuat detail pesanan.');
        }
    }
}
