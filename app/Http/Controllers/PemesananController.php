<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Pemesanan;
use App\Models\DetailPenumpang;
use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PemesananController extends Controller
{
    /**
     * Tampilkan form pemesanan
     */
    public function index(Request $request)
    {
        // Validasi parameter
        $validator = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwals,id',
            'penumpang' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return redirect()->route('customer.search')->withErrors($validator)->withInput();
        }

        // Ambil data jadwal
        $jadwal = Jadwal::with(['shuttle', 'rutes'])
            ->where('id', $request->jadwal_id)
            ->where('status', 'tersedia')
            ->where('kursi_tersedia', '>=', $request->penumpang)
            ->first();

        if (!$jadwal) {
            return redirect()->route('customer.search')
                ->with('error', 'Jadwal tidak tersedia atau kursi sudah penuh');
        }

        // Cek kota asal dan tujuan dari request (jika ada)
        $kotaAsal = $request->get('kota_asal');
        $kotaTujuan = $request->get('kota_tujuan');
        
        // Jika tidak ada di request, ambil dari jadwal
        if (!$kotaAsal || !$kotaTujuan) {
            $kotaAsal = $jadwal->rute_pertama->kota_asal ?? null;
            $kotaTujuan = $jadwal->rute_terakhir->kota_tujuan ?? null;
        }

        // Ambil data user jika login
        $user = session()->get('user');
        $userData = null;
        
        if ($user && isset($user['id'])) {
            $userModel = \App\Models\User::find($user['id']);
            if ($userModel) {
                $userData = [
                    'nama' => $userModel->name,
                    'email' => $userModel->email,
                    'telepon' => $userModel->telepon ?? null
                ];
            }
        }

        // Ambil daftar promo aktif
        $promoList = Promo::where('status', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_berakhir', '>=', now())
            ->get();

        return view('customer.pesan', compact(
            'jadwal', 
            'userData',
            'promoList',
            'kotaAsal',
            'kotaTujuan'
        ));
    }

    /**
     * Proses validasi promo
     */
    public function validasiPromo(Request $request)
    {
        $request->validate([
            'kode_promo' => 'required|string',
            'total_harga' => 'required|numeric'
        ]);

        $promo = Promo::where('kode_promo', strtoupper($request->kode_promo))
            ->where('status', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_berakhir', '>=', now())
            ->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa'
            ]);
        }

        // Cek kuota
        if ($promo->kuota && $promo->terpakai >= $promo->kuota) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota promo sudah habis'
            ]);
        }

        // Cek minimal pembelian
        if ($request->total_harga < $promo->minimal_pembelian) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($promo->minimal_pembelian, 0, ',', '.')
            ]);
        }

        // Hitung diskon
        $diskon = $promo->hitungDiskon($request->total_harga);
        $totalBayar = $request->total_harga - $diskon;

        return response()->json([
            'success' => true,
            'promo' => [
                'nama' => $promo->nama_promo,
                'kode' => $promo->kode_promo,
                'jenis_diskon' => $promo->jenis_diskon,
                'nilai_diskon' => $promo->nilai_diskon,
                'maksimal_diskon' => $promo->maksimal_diskon,
                'deskripsi' => $promo->deskripsi
            ],
            'diskon' => $diskon,
            'total_bayar' => $totalBayar
        ]);
    }

    /**
     * Proses pemesanan tiket (SETELAH ini redirect ke KURSI)
     */
  public function prosesPemesanan(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'jadwal_id' => 'required|exists:jadwals,id',
        'jumlah_penumpang' => 'required|integer|min:1|max:10',
        'nama_pemesan' => 'required|string|max:100',
        'telepon_pemesan' => 'required|string|max:20',
        'email_pemesan' => 'required|email|max:100',
        'penumpang.*.nama_lengkap' => 'required|string|max:100',
        'penumpang.*.nik' => 'required|string|max:20|min:16',
        'penumpang.*.jenis_kelamin' => 'required|string|in:L,P',
        'penumpang.*.telepon' => 'required|string|min:10|max:15', // TAMBAHAN VALIDASI TELEPON
        'kode_promo' => 'nullable|string|exists:promo,kode_promo',
        'catatan' => 'nullable|string|max:500',
    ], [
        'penumpang.*.nama_lengkap.required' => 'Nama lengkap penumpang harus diisi',
        'penumpang.*.nik.required' => 'NIK penumpang wajib diisi',
        'penumpang.*.nik.min' => 'NIK harus 16 digit',
        'penumpang.*.jenis_kelamin.required' => 'Jenis kelamin penumpang harus dipilih',
        'penumpang.*.telepon.required' => 'Nomor telepon penumpang wajib diisi', // PESAN ERROR BARU
        'penumpang.*.telepon.min' => 'Nomor telepon minimal 10 digit',
        'penumpang.*.telepon.max' => 'Nomor telepon maksimal 15 digit',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Terjadi kesalahan dalam pengisian data');
    }

    DB::beginTransaction();

    try {
        // Ambil data jadwal
        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        
        // Cek ketersediaan kursi
        if ($jadwal->kursi_tersedia < $request->jumlah_penumpang) {
            throw new \Exception('Kursi tidak tersedia. Sisa kursi: ' . $jadwal->kursi_tersedia);
        }

        // Hitung harga total
        $hargaTotal = $jadwal->harga_total * $request->jumlah_penumpang;
        $diskon = 0;
        $promoId = null;

        // Validasi promo jika ada
        if ($request->kode_promo) {
            $promo = Promo::where('kode_promo', strtoupper($request->kode_promo))
                ->where('status', true)
                ->whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_berakhir', '>=', now())
                ->first();
                
            if ($promo) {
                $diskon = $promo->hitungDiskon($hargaTotal);
                $promoId = $promo->id;
            }
        }

        $totalBayar = $hargaTotal - $diskon;

        // Generate kode booking
        $kodeBooking = $this->generateKodeBooking();

        // Buat pemesanan
        $pemesanan = Pemesanan::create([
            'kode_booking' => $kodeBooking,
            'customer_id' => Auth::id() ?? null,
            'jadwal_id' => $jadwal->id,
            'jumlah_penumpang' => $request->jumlah_penumpang,
            'harga_total' => $hargaTotal,
            'diskon' => $diskon,
            'total_bayar' => $totalBayar,
            'nama_pemesan' => $request->nama_pemesan,
            'telepon_pemesan' => $request->telepon_pemesan,
            'email_pemesan' => $request->email_pemesan,
            'catatan' => $request->catatan,
            'status' => 'menunggu_pembayaran',
            'waktu_kadaluarsa' => now()->addHours(24), // Kadaluarsa dalam 24 jam
            'kode_promo' => $request->kode_promo,
        ]);

        // Simpan detail penumpang
        if ($request->has('penumpang')) {
            foreach ($request->penumpang as $index => $dataPenumpang) {
                DetailPenumpang::create([
                    'pemesanan_id' => $pemesanan->id,
                    'nama_lengkap' => $dataPenumpang['nama_lengkap'],
                    'nik' => $dataPenumpang['nik'],
                    'jenis_kelamin' => $dataPenumpang['jenis_kelamin'],
                    'telepon' => $dataPenumpang['telepon'], // TAMBAHAN: Simpan telepon
                    'nomor_kursi' => null // Akan diisi saat pilih kursi
                ]);
            }
        }

        // Update kuota promo jika digunakan
        if ($promoId) {
            $promo = Promo::find($promoId);
            if ($promo && $promo->kuota) {
                $promo->terpakai += 1;
                $promo->save();
            }
        }

        // Update kursi tersedia di jadwal
        $jadwal->kursi_tersedia -= $request->jumlah_penumpang;
        if ($jadwal->kursi_tersedia <= 0) {
            $jadwal->status = 'penuh';
        }
        $jadwal->save();

        DB::commit();

        // Redirect ke halaman kursi
        return redirect()->route('customer.kursi', ['pemesanan_id' => $pemesanan->id])
            ->with('success', 'Pemesanan berhasil! Silakan pilih kursi untuk penumpang.');

    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', 'Gagal melakukan pemesanan: ' . $e->getMessage())
            ->withInput();
    }
}
    /**
     * Generate kode booking
     */
    private function generateKodeBooking()
    {
        $prefix = 'SS';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        
        return $prefix . $date . $random;
    }

    /**
     * Tampilkan halaman pembayaran
     */
    public function pembayaran($kodeBooking)
    {
        $pemesanan = Pemesanan::with(['jadwal', 'detailPenumpang'])
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

        return view('customer.pembayaran', compact('pemesanan'));
    }

    /**
     * Tampilkan riwayat pemesanan
     */
    public function riwayat()
    {
        $user = session()->get('user');
        
        if (!$user) {
            return redirect()->route('customer.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $pemesanan = Pemesanan::with(['jadwal'])
            ->where('customer_id', $user['id'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.riwayat', compact('pemesanan'));
    }

    /**
     * Tampilkan detail pemesanan
     */
    public function detail($kodeBooking)
    {
        $pemesanan = Pemesanan::with(['jadwal', 'detailPenumpang'])
            ->where('kode_booking', $kodeBooking)
            ->firstOrFail();

        // Cek akses
        $user = session()->get('user');
        if ($user && $pemesanan->customer_id && $pemesanan->customer_id != $user['id']) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini');
        }

        return view('customer.detail_pemesanan', compact('pemesanan'));
    }
    /**
 * Tampilkan e-ticket
 */
public function showETicket($kode_booking)
{
    if (!session()->has('user')) {
        return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
    }

    $user = session()->get('user');
    
    // Ambil data pemesanan
    $pemesanan = Pemesanan::with([
        'jadwal.shuttle', 
        'detailPenumpang',
        'transaksi',
        'jadwal.rutes'
    ])->where('kode_booking', $kode_booking)
      ->where('customer_id', $user['id'])
      ->first();

    if (!$pemesanan) {
        return redirect()->route('customer.riwayat')
            ->with('error', 'Pemesanan tidak ditemukan.');
    }

    // Cek status pembayaran
    if ($pemesanan->status_pembayaran !== 'berhasil' && $pemesanan->status_pemesanan !== 'dibayar') {
        return redirect()->route('customer.riwayat')
            ->with('error', 'Pemesanan belum dibayar.');
    }

    // Format data untuk e-ticket
    $jadwal = $pemesanan->jadwal;
    
    // Ambil rute pertama dan terakhir
    $rutePertama = $jadwal->rutes->first();
    $ruteTerakhir = $jadwal->rutes->last();
    
    // Hitung estimasi waktu (contoh: 3.5 jam perjalanan)
    $waktuBerangkat = Carbon::parse($jadwal->waktu_keberangkatan);
    $waktuSampai = $waktuBerangkat->copy()->addHours(3)->addMinutes(30);
    
    $data = [
        'pemesanan' => $pemesanan,
        'jadwal' => $jadwal,
        'from' => $rutePertama->kota_asal ?? 'Jakarta',
        'to' => $ruteTerakhir->kota_tujuan ?? 'Jatinangor',
        'date' => Carbon::parse($jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY'),
        'time' => $waktuBerangkat->format('H:i'),
        'estimasi_sampai' => $waktuSampai->format('H:i'),
        'customer_name' => $pemesanan->nama_pemesan,
        'customer_phone' => $pemesanan->telepon_pemesan,
        'customer_email' => $pemesanan->email_pemesan,
        'penumpang' => $pemesanan->detailPenumpang,
        'shuttle' => $jadwal->shuttle,
        'nomor_kursi' => $pemesanan->detailPenumpang->first()->nomor_kursi ?? '03',
        'kode_booking' => $pemesanan->kode_booking,
        'user' => $user
    ];

    return view('customer.e_ticket', $data);
}

/**
 * Download e-ticket sebagai PDF
 */
public function downloadETicket($kode_booking)
{
    // Implementasi PDF download nanti
    return redirect()->route('customer.e_ticket', ['kode_booking' => $kode_booking])
        ->with('info', 'Fitur download PDF akan segera tersedia.');
}
}