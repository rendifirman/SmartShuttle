<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\DetailPenumpang;
use App\Models\Promo;
use App\Models\Transaksi;
use App\Models\MetodePembayaran;
use App\Models\User;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $pemesanan = Pemesanan::with([
                'jadwal.shuttle',
                'detailPenumpang',
                'transaksi'
            ])
            ->where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $pemesanan,
                'message' => 'Data pemesanan berhasil diambil'
            ]);
        } catch (\Exception $e) {
            Log::error('API Pemesanan Index Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('API Pemesanan Store Request:', $request->all());

        // Validasi input
        $validator = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwals,id',
            'jumlah_penumpang' => 'required|integer|min:1|max:10',
            'nama_pemesan' => 'required|string|max:100',
            'telepon_pemesan' => 'required|string|max:20',
            'email_pemesan' => 'required|email|max:100',
            'penumpang' => 'required|array|min:1',
            'penumpang.*.nama_lengkap' => 'required|string|max:100',
            'penumpang.*.nik' => 'required|string|size:16',
            'penumpang.*.jenis_kelamin' => 'required|string|in:L,P',
            'penumpang.*.telepon' => 'required|string|min:10|max:15',
            'kode_promo' => 'nullable|string',
            'catatan' => 'nullable|string|max:500',
            'outlet_asal_id' => 'nullable|exists:outlets,id',
            'outlet_tujuan_id' => 'nullable|exists:outlets,id',
        ], [
            'penumpang.*.nama_lengkap.required' => 'Nama lengkap penumpang harus diisi',
            'penumpang.*.nik.required' => 'NIK penumpang wajib diisi',
            'penumpang.*.nik.size' => 'NIK harus 16 digit',
            'penumpang.*.jenis_kelamin.required' => 'Jenis kelamin penumpang harus dipilih',
            'penumpang.*.telepon.required' => 'Nomor telepon penumpang wajib diisi',
            'penumpang.*.telepon.min' => 'Nomor telepon minimal 10 digit',
            'penumpang.*.telepon.max' => 'Nomor telepon maksimal 15 digit',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Ensure user is authenticated
            $user = $request->user();
            if (!$user) {
                Log::warning('API Pemesanan Store: No authenticated user found', [
                    'headers' => $request->headers->all(),
                    'bearer_token' => $request->bearerToken(),
                    'has_authorization' => $request->hasHeader('Authorization')
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            Log::info('API Pemesanan Store: User authenticated', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

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
            $kodePromo = null;

            // Validasi promo jika ada
            if ($request->kode_promo) {
                $promo = Promo::where('kode_promo', strtoupper($request->kode_promo))
                    ->where('status', true)
                    ->whereDate('tanggal_mulai', '<=', now())
                    ->whereDate('tanggal_berakhir', '>=', now())
                    ->first();

                if ($promo) {
                    // Cek kuota
                    if ($promo->kuota && $promo->terpakai >= $promo->kuota) {
                        throw new \Exception('Kuota promo sudah habis');
                    }

                    // Cek minimal pembelian
                    if ($hargaTotal < $promo->minimal_pembelian) {
                        throw new \Exception('Minimal pembelian Rp ' . number_format($promo->minimal_pembelian, 0, ',', '.'));
                    }

                    $diskon = $promo->calculateDiscount($hargaTotal);
                    $promoId = $promo->id;
                    $kodePromo = $promo->kode_promo;
                }
            }

            $totalBayar = $hargaTotal - $diskon;

            // Generate kode booking
            $kodeBooking = $this->generateKodeBooking();

            // Buat pemesanan
            $pemesanan = Pemesanan::create([
                'kode_booking' => $kodeBooking,
                'customer_id' => $user->id,
                'jadwal_id' => $jadwal->id,
                'jumlah_penumpang' => $request->jumlah_penumpang,
                'harga_total' => $hargaTotal,
                'diskon' => $diskon,
                'total_bayar' => $totalBayar,
                'nama_pemesan' => $request->nama_pemesan,
                'telepon_pemesan' => $request->telepon_pemesan,
                'email_pemesan' => $request->email_pemesan,
                'catatan' => $request->catatan,
                'outlet_asal_id' => $request->outlet_asal_id,
                'outlet_tujuan_id' => $request->outlet_tujuan_id,
                'status' => 'menunggu_pembayaran',
                'waktu_kadaluarsa' => now()->addHours(24),
                'kode_promo' => $kodePromo,
                'created_by' => $user->id,
            ]);

            // Simpan detail penumpang
            foreach ($request->penumpang as $index => $dataPenumpang) {
                DetailPenumpang::create([
                    'pemesanan_id' => $pemesanan->id,
                    'nama_lengkap' => $dataPenumpang['nama_lengkap'],
                    'nik' => $dataPenumpang['nik'],
                    'jenis_kelamin' => $dataPenumpang['jenis_kelamin'],
                    'telepon' => $dataPenumpang['telepon'],
                    'nomor_kursi' => null // Akan diisi saat pilih kursi
                ]);
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

            // Tambah poin member jika user adalah member aktif
            $user = $request->user();
            if ($user && $user->isMemberActive()) {
                // Tambah member points (100 per pembelian)
                $user->addMemberPoints(100);

                // Tambah loyalty points berdasarkan level membership
                $loyaltyPointsToAdd = $user->calculateLoyaltyPointsToAdd();
                $user->addLoyaltyPoints($loyaltyPointsToAdd);
            }

            DB::commit();

            // Load relasi untuk response
            $pemesanan->load(['jadwal', 'detailPenumpang', 'jadwal.shuttle']);

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dibuat!',
                'data' => $pemesanan
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('API Pemesanan Store Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pemesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $kode_booking)
    {
        try {
            $user = $request->user();

            $pemesanan = Pemesanan::with([
                'jadwal.shuttle',
                'detailPenumpang',
                'transaksi',
                'outletAsal',
                'outletTujuan'
            ])
            ->where('kode_booking', $kode_booking)
            ->where('customer_id', $user->id)
            ->first();

            if (!$pemesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $pemesanan,
                'message' => 'Detail pemesanan berhasil diambil'
            ]);
        } catch (\Exception $e) {
            Log::error('API Pemesanan Show Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_booking)
    {
        Log::info('API Pemesanan Update Request:', [
            'kode_booking' => $kode_booking,
            'data' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'nama_pemesan' => 'sometimes|required|string|max:100',
            'telepon_pemesan' => 'sometimes|required|string|max:20',
            'email_pemesan' => 'sometimes|required|email|max:100',
            'catatan' => 'nullable|string|max:500',
            'outlet_asal_id' => 'nullable|exists:outlets,id',
            'outlet_tujuan_id' => 'nullable|exists:outlets,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();

            $pemesanan = Pemesanan::where('kode_booking', $kode_booking)
                ->where('customer_id', $user->id)
                ->first();

            if (!$pemesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak ditemukan'
                ], 404);
            }

            // Hanya bisa update jika status masih menunggu pembayaran atau menunggu konfirmasi
            if (!in_array($pemesanan->status, ['menunggu_pembayaran', 'menunggu_konfirmasi'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak dapat diupdate karena sudah diproses.'
                ], 400);
            }

            // Update data pemesanan
            $updateData = $request->only([
                'nama_pemesan',
                'telepon_pemesan',
                'email_pemesan',
                'catatan',
                'outlet_asal_id',
                'outlet_tujuan_id'
            ]);

            $updateData['updated_by'] = $user->id;

            $pemesanan->update($updateData);

            DB::commit();

            // Load relasi untuk response
            $pemesanan->load(['jadwal', 'detailPenumpang']);

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil diperbarui.',
                'data' => $pemesanan
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('API Pemesanan Update Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel the specified resource.
     */
    public function cancel(Request $request, $kode_booking)
    {
        DB::beginTransaction();

        try {
            $user = $request->user();

            $pemesanan = Pemesanan::where('kode_booking', $kode_booking)
                ->where('customer_id', $user->id)
                ->first();

            if (!$pemesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak ditemukan'
                ], 404);
            }

            // Hanya bisa dibatalkan jika status masih menunggu pembayaran
            if (!in_array($pemesanan->status, ['menunggu_pembayaran', 'menunggu_konfirmasi'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak dapat dibatalkan karena sudah diproses.'
                ], 400);
            }

            // Kembalikan kursi tersedia
            $jadwal = $pemesanan->jadwal;
            $jadwal->kursi_tersedia += $pemesanan->jumlah_penumpang;

            // Jika status sebelumnya tidak tersedia, ubah jadi tersedia
            if ($jadwal->status === 'penuh') {
                $jadwal->status = 'tersedia';
            }

            $jadwal->save();

            // Update status pemesanan
            $pemesanan->status = 'dibatalkan';
            $pemesanan->save();

            // Update transaksi jika ada
            if ($pemesanan->transaksi) {
                $pemesanan->transaksi->status = 'dibatalkan';
                $pemesanan->transaksi->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dibatalkan.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('API Pemesanan Cancel Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Request $request, $kode_booking)
    {
        DB::beginTransaction();

        try {
            $user = $request->user();

            $pemesanan = Pemesanan::where('kode_booking', $kode_booking)
                ->where('customer_id', $user->id)
                ->first();

            if (!$pemesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak ditemukan'
                ], 404);
            }

            // Hanya bisa dihapus jika status masih menunggu pembayaran atau dibatalkan
            if (!in_array($pemesanan->status, ['menunggu_pembayaran', 'dibatalkan'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak dapat dihapus karena sudah diproses.'
                ], 400);
            }

            // Soft delete dengan mengisi deleted_by
            $pemesanan->deleted_by = $user->id;
            $pemesanan->save();
            $pemesanan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('API Pemesanan Destroy Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get riwayat pemesanan.
     */
    public function riwayat(Request $request)
    {
        try {
            $user = $request->user();

            $pemesanan = Pemesanan::with([
                'jadwal.shuttle',
                'detailPenumpang',
                'transaksi'
            ])
            ->where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $pemesanan,
                'message' => 'Riwayat pemesanan berhasil diambil'
            ]);
        } catch (\Exception $e) {
            Log::error('API Pemesanan Riwayat Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Proses pemilihan kursi.
     */
    public function pilihKursi(Request $request, $kode_booking)
    {
        $validator = Validator::make($request->all(), [
            'kursi' => 'required|array',
            'kursi.*.penumpang_id' => 'required|exists:detail_penumpang,id',
            'kursi.*.nomor_kursi' => 'required|string|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();

            $pemesanan = Pemesanan::with(['jadwal.shuttle', 'detailPenumpang'])
                ->where('kode_booking', $kode_booking)
                ->where('customer_id', $user->id)
                ->first();

            if (!$pemesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak ditemukan'
                ], 404);
            }

            // Validasi jumlah kursi sama dengan jumlah penumpang
            if (count($request->kursi) !== $pemesanan->jumlah_penumpang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah kursi yang dipilih harus sama dengan jumlah penumpang.'
                ], 400);
            }

            // Validasi kursi unik
            $nomorKursi = array_column($request->kursi, 'nomor_kursi');
            if (count($nomorKursi) !== count(array_unique($nomorKursi))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setiap penumpang harus memiliki kursi yang berbeda.'
                ], 400);
            }

            // Cek kursi yang sudah terisi di jadwal ini
            $kursiTerisi = DetailPenumpang::whereHas('pemesanan', function($query) use ($pemesanan) {
                $query->where('jadwal_id', $pemesanan->jadwal_id)
                    ->where('id', '!=', $pemesanan->id)
                    ->where('status', '!=', 'dibatalkan');
            })->whereNotNull('nomor_kursi')
              ->pluck('nomor_kursi')
              ->toArray();

            // Validasi kursi tidak terisi oleh pemesanan lain
            foreach ($request->kursi as $dataKursi) {
                if (in_array($dataKursi['nomor_kursi'], $kursiTerisi)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kursi ' . $dataKursi['nomor_kursi'] . ' sudah dipesan oleh penumpang lain.'
                    ], 400);
                }
            }

            // Update kursi untuk setiap penumpang
            foreach ($request->kursi as $dataKursi) {
                $detailPenumpang = DetailPenumpang::where('id', $dataKursi['penumpang_id'])
                    ->where('pemesanan_id', $pemesanan->id)
                    ->first();

                if ($detailPenumpang) {
                    $detailPenumpang->nomor_kursi = $dataKursi['nomor_kursi'];
                    $detailPenumpang->save();
                }
            }

            // Update status pemesanan jika belum dibayar
            if ($pemesanan->status === 'menunggu_pembayaran') {
                $pemesanan->status = 'diproses';
                $pemesanan->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemilihan kursi berhasil!',
                'data' => $pemesanan->load('detailPenumpang')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('API Pilih Kursi Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Proses pembayaran.
     */
    public function bayar(Request $request, $kode_booking)
    {
        $validator = Validator::make($request->all(), [
            'metode_pembayaran' => 'required|string|exists:metode_pembayaran,kode'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();

            $pemesanan = Pemesanan::where('kode_booking', $kode_booking)
                ->where('customer_id', $user->id)
                ->first();

            if (!$pemesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak ditemukan'
                ], 404);
            }

            // Cek status pemesanan
            if (!in_array($pemesanan->status, ['menunggu_pembayaran', 'diproses'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak dapat dibayar karena status sudah ' . $pemesanan->status
                ], 400);
            }

            // Ambil metode pembayaran
            $metodePembayaran = MetodePembayaran::where('kode', $request->metode_pembayaran)
                ->where('aktif', true)
                ->first();

            if (!$metodePembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Metode pembayaran tidak tersedia'
                ], 400);
            }

            // Update pemesanan
            $pemesanan->metode_pembayaran = $request->metode_pembayaran;
            $pemesanan->status = 'menunggu_pembayaran';
            $pemesanan->tanggal_pembayaran_terakhir = Carbon::now()->addHours(24);
            $pemesanan->save();

            // Generate kode pembayaran
            $kodePembayaran = 'PAY' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

            // Buat transaksi
            $transaksi = Transaksi::create([
                'pemesanan_id' => $pemesanan->id,
                'kode_transaksi' => $kodePembayaran,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah' => $pemesanan->total_bayar,
                'status' => 'pending',
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses. Silakan selesaikan pembayaran dalam 24 jam.',
                'data' => [
                    'pemesanan' => $pemesanan,
                    'transaksi' => $transaksi,
                    'metode_pembayaran' => $metodePembayaran
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('API Bayar Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get e-ticket.
     */
    public function eTicket(Request $request, $kode_booking)
    {
        try {
            $user = $request->user();

            $pemesanan = Pemesanan::with([
                'jadwal.shuttle',
                'detailPenumpang',
                'transaksi',
                'jadwal.rutes'
            ])
            ->where('kode_booking', $kode_booking)
            ->where('customer_id', $user->id)
            ->first();

            if (!$pemesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan tidak ditemukan'
                ], 404);
            }

            // Cek status pembayaran
            if ($pemesanan->status !== 'dibayar' &&
                ($pemesanan->transaksi && $pemesanan->transaksi->status !== 'success')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemesanan belum dibayar atau belum dikonfirmasi.'
                ], 400);
            }

            // Format data e-ticket
            $jadwal = $pemesanan->jadwal;
            $rutePertama = $jadwal->rutes->first();
            $ruteTerakhir = $jadwal->rutes->last();

            $waktuBerangkat = Carbon::parse($jadwal->waktu_keberangkatan);
            $waktuSampai = $waktuBerangkat->copy()->addHours(3)->addMinutes(30);

            $eTicket = [
                'kode_booking' => $pemesanan->kode_booking,
                'tanggal_pemesanan' => $pemesanan->created_at->format('d-m-Y H:i'),
                'status' => $pemesanan->status,
                'customer' => [
                    'nama' => $pemesanan->nama_pemesan,
                    'telepon' => $pemesanan->telepon_pemesan,
                    'email' => $pemesanan->email_pemesan
                ],
                'perjalanan' => [
                    'dari' => $rutePertama->kota_asal ?? 'Kota Asal',
                    'ke' => $ruteTerakhir->kota_tujuan ?? 'Kota Tujuan',
                    'tanggal' => Carbon::parse($jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY'),
                    'waktu_berangkat' => $waktuBerangkat->format('H:i'),
                    'waktu_tiba' => $waktuSampai->format('H:i'),
                    'durasi' => '3 jam 30 menit'
                ],
                'shuttle' => [
                    'nama' => $jadwal->shuttle->nama_shuttle ?? 'Smart Shuttle',
                    'nomor_polisi' => $jadwal->shuttle->nomor_polisi ?? '-',
                    'fasilitas' => $jadwal->shuttle->fasilitas ?? 'AC, WiFi, Charger'
                ],
                'penumpang' => $pemesanan->detailPenumpang->map(function($penumpang) {
                    return [
                        'nama' => $penumpang->nama_lengkap,
                        'nik' => $penumpang->nik,
                        'jenis_kelamin' => $penumpang->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                        'telepon' => $penumpang->telepon,
                        'kursi' => $penumpang->nomor_kursi ?? 'Belum dipilih'
                    ];
                }),
                'pembayaran' => [
                    'subtotal' => $pemesanan->harga_total,
                    'diskon' => $pemesanan->diskon,
                    'total' => $pemesanan->total_bayar,
                    'metode' => $pemesanan->metode_pembayaran,
                    'status_pembayaran' => $pemesanan->transaksi->status ?? 'pending'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $eTicket,
                'message' => 'E-ticket berhasil diambil'
            ]);

        } catch (\Exception $e) {
            Log::error('API E-Ticket Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate promo code (public API).
     */
    public function validatePromoAPI(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_promo' => 'required|string',
            'total_amount' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $promoCode = strtoupper($request->kode_promo);

            $promo = Promo::where('kode_promo', $promoCode)
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
            if ($request->total_amount < $promo->minimal_pembelian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimal pembelian Rp ' . number_format($promo->minimal_pembelian, 0, ',', '.')
                ]);
            }

            // Hitung diskon
            $diskon = $promo->calculateDiscount($request->total_amount);
            $totalAfterDiscount = $request->total_amount - $diskon;

            return response()->json([
                'success' => true,
                'message' => 'Kode promo berhasil divalidasi!',
                'data' => [
                    'promo' => [
                        'id' => $promo->id,
                        'nama' => $promo->nama_promo,
                        'kode' => $promo->kode_promo,
                        'jenis_diskon' => $promo->jenis_diskon,
                        'nilai_diskon' => $promo->nilai_diskon,
                        'maksimal_diskon' => $promo->maksimal_diskon,
                        'deskripsi' => $promo->deskripsi,
                        'minimal_pembelian' => $promo->minimal_pembelian
                    ],
                    'diskon' => $diskon,
                    'total_after_discount' => $totalAfterDiscount
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('API Validate Promo Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate kode booking.
     */
    private function generateKodeBooking()
    {
        $prefix = 'SS';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));

        $kode = $prefix . $date . $random;

        // Cek unik
        while (Pemesanan::where('kode_booking', $kode)->exists()) {
            $random = strtoupper(substr(md5(uniqid()), 0, 6));
            $kode = $prefix . $date . $random;
        }

        return $kode;
    }
}
