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
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KursiController extends Controller
{
    /**
     * Tampilkan halaman pemilihan kursi (LAYOUT STABIL)
     */
   public function index(Request $request)
    {
        // Validasi parameter
        $validator = Validator::make($request->all(), [
            'pemesanan_id' => 'required|exists:pemesanan,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('customer.riwayat')
                ->with('error', 'Parameter pemesanan tidak valid');
        }

        try {
            // Ambil data pemesanan dengan relasi
            $pemesanan = Pemesanan::with([
                'jadwal.shuttle',
                'detailPenumpang',
                'driverJadwal'
            ])->where('id', $request->pemesanan_id)
              ->where('status', 'menunggu_kursi')
              ->first();

            if (!$pemesanan) {
                return redirect()->route('customer.riwayat')
                    ->with('error', 'Pemesanan tidak ditemukan atau sudah diproses');
            }

            // Cek apakah sudah memilih kursi
            $sudahPilihKursi = $pemesanan->detailPenumpang()
                ->whereNotNull('nomor_kursi')
                ->exists();

            if ($sudahPilihKursi) {
                return redirect()->route('customer.detail_pesanan', ['kode' => $pemesanan->kode_booking])
                    ->with('info', 'Kursi sudah dipilih. Silakan lanjutkan ke pembayaran.');
            }

            // ================ KUNCI UTAMA: LAYOUT STABIL ================
            // Ambil layout kursi yang FIX dari shuttle
            $shuttle = $pemesanan->jadwal->shuttle;

            // Pastikan shuttle memiliki layout yang valid
            if (!$shuttle->layout_kursi || $shuttle->layout_kursi === '[]') {
                // Inisialisasi layout jika kosong
                $shuttle->layout_kursi = json_encode(KursiTerpesan::generateLayoutKursi($shuttle->total_kursi ?? 9));
                $shuttle->save();
            }

            // Ambil layout base dari shuttle
            $layoutKursi = is_array($shuttle->layout_kursi)
                ? $shuttle->layout_kursi
                : json_decode($shuttle->layout_kursi, true);

            if (empty($layoutKursi) || !is_array($layoutKursi)) {
                $layoutKursi = KursiTerpesan::generateLayoutKursi($shuttle->total_kursi ?? 9);
            }

            // ===== BAGIAN PENTING: GET KURSI YANG SUDAH TERPESAN =====
            // Query SEMUA kursi terpesan dari booking lain (exclude booking ini)
            $kursiTerpesanQuery = KursiTerpesan::where('status', 'terpesan')
                ->where('pemesanan_id', '!=', $pemesanan->id)
                ->whereHas('pemesanan', function($query) {
                    $query->whereNotIn('status', ['dibatalkan', 'expired']);
                });

            // Handle BOTH jadwal dan driver_jadwal flows
            if ($pemesanan->id_jadwal_driver) {
                // Use driver jadwal filter
                $kursiTerpesanQuery->where('id_jadwal_driver', $pemesanan->id_jadwal_driver);
            } else {
                // Use jadwal filter
                $kursiTerpesanQuery->where('jadwal_id', $pemesanan->jadwal_id);
            }

            // Ambil semua nomor kursi yang terpesan
            $kursiTerpesan = $kursiTerpesanQuery->pluck('nomor_kursi')->toArray();
            $kursiTerpesan = array_map(function($v){ return trim((string) $v); }, $kursiTerpesan);

            // ===== NORMALISASI LAYOUT DENGAN DETAIL PENUMPANG & KURSI TERPESAN =====
            // Ambil kursi yang dipilih user di booking SEKARANG (belum di-simpan ke database)
            $kursiSaya = $pemesanan->detailPenumpang->pluck('nomor_kursi')->filter()->toArray();
            $kursiSaya = array_map(function($v){ return trim((string) $v); }, $kursiSaya);

            // Normalisasi setiap kursi dengan status yang benar
            foreach ($layoutKursi as &$kursi) {
                $nomor = trim((string) ($kursi['nomor'] ?? ''));

                // PRIORITY 1: Cek apakah kursi sudah terpesan oleh booking lain (SOLD)
                if ($nomor !== '' && in_array($nomor, $kursiTerpesan, true)) {
                    $kursi['status'] = 'terpesan';
                    $kursi['class'] = 'sold';
                    $kursi['icon'] = 'fa-lock';
                }
                // PRIORITY 2: Cek apakah kursi dipilih user sekarang (SELECTED)
                elseif ($nomor !== '' && in_array($nomor, $kursiSaya, true)) {
                    $kursi['status'] = 'selected';
                    $kursi['class'] = 'selected';
                    $kursi['icon'] = 'fa-user-check';
                }
                // PRIORITY 3: Kursi tersedia
                else {
                    $kursi['status'] = 'tersedia';
                    $kursi['class'] = 'available';
                    $kursi['icon'] = 'fa-check';
                }
            }
            unset($kursi); // Unset reference

            // Backup: jika masih kosong, generate default
            if (empty($layoutKursi)) {
                $layoutKursi = KursiTerpesan::generateLayoutKursi(9);
            }

            // Determine selected tariff for display
            $selectedTarif = null;
            try {
                if ($pemesanan->jadwal && $pemesanan->jadwal->rutes && $pemesanan->jadwal->rutes->isNotEmpty()) {
                    $ruteObj = $pemesanan->jadwal->rutes->first();
                    $mt = $ruteObj->getActiveMasterTarif();
                    $selectedTarif = $mt ? $mt->formatTarif() : ['harga_dasar' => $ruteObj->harga_dasar ?? null];
                }
            } catch (\Exception $e) {
                \Log::error('Failed to get selected tariff in KursiController: ' . $e->getMessage());
            }

            // Tentukan apakah menggunakan driver jadwal atau jadwal regular
            $usesDriverJadwal = !empty($pemesanan->id_jadwal_driver) && $pemesanan->driverJadwal;
            $driverJadwal = $usesDriverJadwal ? $pemesanan->driverJadwal : null;
            $jadwal = $pemesanan->jadwal;

            // Hitung harga (sama seperti pesan.blade.php)
            $hargaPerOrang = $jadwal?->harga_total ?? 0;
            $totalTarif = 0;
            $diskon = $pemesanan->diskon ?? 0;
            $subtotal = ($hargaPerOrang * $pemesanan->jumlah_penumpang) + $totalTarif;
            $totalBayar = max(0, $subtotal - $diskon);
            $tarifPerKursi = $pemesanan->jumlah_penumpang > 0 ? $totalTarif / $pemesanan->jumlah_penumpang : 0;

            return view('customer.kursi', compact(
                'pemesanan',
                'kursiTerpesan',
                'layoutKursi',
                'shuttle',
                'selectedTarif',
                'usesDriverJadwal',
                'driverJadwal',
                'jadwal',
                'hargaPerOrang',
                'totalTarif',
                'diskon',
                'subtotal',
                'totalBayar',
                'tarifPerKursi'
            ));

        } catch (\Exception $e) {
            \Log::error('Error in KursiController@index: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->route('customer.riwayat')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Proses pemilihan kursi (VALIDASI GANDA)
     */
       public function prosesKursi(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'pemesanan_id' => 'required|exists:pemesanan,id',
            'jadwal_id' => 'required|exists:jadwals,id',
            'kursi' => 'required|array|min:1',
            'kursi.*' => 'required|string|distinct'
        ], [
            'kursi.required' => 'Silakan pilih kursi untuk setiap penumpang',
            'kursi.*.distinct' => 'Kursi tidak boleh duplikat'
        ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Terjadi kesalahan dalam pemilihan kursi');
    }

    DB::beginTransaction();

        try {
            // Ambil data pemesanan
            $pemesanan = Pemesanan::with(['detailPenumpang', 'jadwal.shuttle'])
                ->where('id', $request->pemesanan_id)
                ->where('status', 'menunggu_pembayaran')
                ->lockForUpdate() // Lock row untuk mencegah race condition
                ->firstOrFail();

            // Validasi 1: Jumlah kursi harus sama dengan jumlah penumpang
            if (count($request->kursi) !== $pemesanan->jumlah_penumpang) {
                throw new \Exception('Jumlah kursi harus sama dengan jumlah penumpang');
            }

            // Validasi 2: Ambil layout FIX dari shuttle untuk validasi kursi
            $shuttle = $pemesanan->jadwal->shuttle;
            $layoutKursi = KursiTerpesan::getLayoutWithStatus($request->jadwal_id, $shuttle->id);
            $validKursiNumbers = array_column($layoutKursi, 'nomor');

            // Validasi 3: Cek apakah semua kursi yang dipilih valid
            $invalidSeats = [];
            foreach ($request->kursi as $nomorKursi) {
                if (!in_array($nomorKursi, $validKursiNumbers)) {
                    $invalidSeats[] = $nomorKursi;
                }
            }

            if (!empty($invalidSeats)) {
                throw new \Exception("Kursi " . implode(', ', $invalidSeats) . " tidak valid dalam layout shuttle");
            }

            // Validasi 4: Cek apakah kursi sudah terpesan (double-check dengan lock)
            $terpesanSeats = [];
            foreach ($request->kursi as $nomorKursi) {
                $query = KursiTerpesan::where('nomor_kursi', $nomorKursi)
                    ->whereIn('status', ['terpesan', 'terisi'])
                    ->lockForUpdate(); // Lock untuk mencegah double booking

                if ($pemesanan->id_jadwal_driver) {
                    $query->where('id_jadwal_driver', $pemesanan->id_jadwal_driver);
                } else {
                    $query->where('jadwal_id', $request->jadwal_id);
                }

                if ($query->exists()) {
                    $terpesanSeats[] = $nomorKursi;
                }
            }

            if (!empty($terpesanSeats)) {
                throw new \Exception("Kursi " . implode(', ', $terpesanSeats) . " sudah terpesan oleh customer lain. Silakan pilih kursi lain.");
            }

            // Simpan kursi untuk setiap penumpang
            $detailPenumpang = $pemesanan->detailPenumpang;

        foreach ($detailPenumpang as $index => $penumpang) {
            $nomorKursi = $request->kursi[$index] ?? null;

                if ($nomorKursi) {
                    // Update nomor kursi di detail penumpang
                    $penumpang->nomor_kursi = $nomorKursi;
                    $penumpang->save();

                    // Buat record di kursi_terpesan dengan status terpesan
                    KursiTerpesan::create([
                        'jadwal_id' => $pemesanan->jadwal_id,
                        'nomor_kursi' => $nomorKursi,
                        'detail_penumpang_id' => $penumpang->id,
                        'pemesanan_id' => $pemesanan->id,
                        'status' => 'terpesan'
                    ]);
                }
            }

            // Update kursi tersedia di jadwal
            $kursiTersediaBaru = $pemesanan->jadwal->kursi_tersedia - $pemesanan->jumlah_penumpang;
            $pemesanan->jadwal->update(['kursi_tersedia' => $kursiTersediaBaru]);

        DB::commit();

            // Redirect ke detail pesanan
            return redirect()->route('customer.detail_pesanan', ['kode' => $pemesanan->kode_booking])
                ->with('success', 'Kursi berhasil dipilih! Silakan konfirmasi detail pesanan.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error in KursiController@prosesKursi: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all()));

            return redirect()->back()
                ->with('error', 'Gagal memilih kursi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan detail pesanan setelah memilih kursi
     */
    public function detailPesanan($kode)
    {
        try {
            // Ambil data pemesanan dengan relasi yang lengkap
            $pemesanan = Pemesanan::with([
                'jadwal.rutes',
                'jadwal.shuttle',
                'detailPenumpang'
            ])->where('kode_booking', $kode)->firstOrFail();

            // Siapkan data untuk view; handle driver_jadwals flow if jadwal is null
            if ($pemesanan->jadwal && $pemesanan->jadwal->rutes && $pemesanan->jadwal->rutes->isNotEmpty()) {
                $rutePertama = $pemesanan->jadwal->rutes->first();
                $ruteTerakhir = $pemesanan->jadwal->rutes->last();

                $data = [
                    'pemesanan' => $pemesanan,
                    'from' => $rutePertama->kota_asal ?? 'Kota Asal',
                    'to' => $ruteTerakhir->kota_tujuan ?? 'Kota Tujuan',
                    'date' => Carbon::parse($pemesanan->jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY'),
                    'time' => Carbon::parse($pemesanan->jadwal->waktu_keberangkatan)->format('H:i'),
                    'customer_name' => $pemesanan->nama_pemesan ?? 'Nama Pemesan',
                    'customer_phone' => $pemesanan->telepon_pemesan ?? 'Nomor Telepon',
                    'customer_email' => $pemesanan->email_pemesan ?? 'Email',
                    'price' => $pemesanan->harga_total,
                    'qty' => $pemesanan->jumlah_penumpang,
                    'subtotal' => $pemesanan->harga_total * $pemesanan->jumlah_penumpang,
                    'discount' => $pemesanan->diskon ?? 0,
                    'total' => $pemesanan->total_bayar,
                    'penumpang' => $pemesanan->detailPenumpang,
                    'shuttle' => $pemesanan->jadwal->shuttle,
                    'kode_booking' => $pemesanan->kode_booking
                ];
            } elseif ($pemesanan->driverJadwal) {
                // driver_jadwals-based booking
                $dj = $pemesanan->driverJadwal;
                $detailRute = $dj->getDetailRute();

                $data = [
                    'pemesanan' => $pemesanan,
                    'from' => $detailRute['kota_asal'] ?? 'Kota Asal',
                    'to' => $detailRute['kota_tujuan'] ?? 'Kota Tujuan',
                    'date' => Carbon::parse($dj->tanggal)->isoFormat('dddd, D MMMM YYYY'),
                    'time' => Carbon::parse($dj->waktu_keberangkatan)->format('H:i'),
                    'customer_name' => $pemesanan->nama_pemesan ?? 'Nama Pemesan',
                    'customer_phone' => $pemesanan->telepon_pemesan ?? 'Nomor Telepon',
                    'customer_email' => $pemesanan->email_pemesan ?? 'Email',
                    'price' => $pemesanan->harga_total,
                    'qty' => $pemesanan->jumlah_penumpang,
                    'subtotal' => $pemesanan->harga_total * $pemesanan->jumlah_penumpang,
                    'discount' => $pemesanan->diskon ?? 0,
                    'total' => $pemesanan->total_bayar,
                    'penumpang' => $pemesanan->detailPenumpang,
                    'shuttle' => $dj->shuttle,
                    'kode_booking' => $pemesanan->kode_booking
                ];
            } else {
                throw new \Exception('Data jadwal/rute tidak lengkap pada pemesanan.');
            }

            // Attach selected tariff info (support jadwal or driver_jadwals)
            $selectedTarif = null;
            try {
                if ($pemesanan->jadwal && $pemesanan->jadwal->rutes && $pemesanan->jadwal->rutes->isNotEmpty()) {
                    $ruteObj = $pemesanan->jadwal->rutes->first();
                    $mt = $ruteObj->getActiveMasterTarif();
                    $selectedTarif = $mt ? $mt->formatTarif() : ['harga_dasar' => $ruteObj->harga_dasar ?? null];
                } elseif ($pemesanan->driverJadwal) {
                    $dj = $pemesanan->driverJadwal;
                    if ($dj->masterTarif) {
                        $mt = $dj->masterTarif;
                        if ($mt && ($mt->status ?? null) === 'aktif') $selectedTarif = $mt->formatTarif();
                    }

                    if (!$selectedTarif) {
                        $ruteObj = $dj->masterRute ?? null;
                        if (!$ruteObj) {
                            $parsed = $dj->getDetailRute();
                            if (!empty($parsed['kota_asal']) && !empty($parsed['kota_tujuan'])) {
                                $ka = trim(strtolower($parsed['kota_asal']));
                                $kt = trim(strtolower($parsed['kota_tujuan']));
                                $ruteObj = Rute::whereRaw('LOWER(kota_asal) = ?', [$ka])
                                    ->whereRaw('LOWER(kota_tujuan) = ?', [$kt])
                                    ->aktif()
                                    ->first();
                            }
                        }

                        if ($ruteObj) {
                            $mt = $ruteObj->getActiveMasterTarif();
                            $selectedTarif = $mt ? $mt->formatTarif() : ['harga_dasar' => $ruteObj->harga_dasar ?? null];
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to get selected tariff for detail_pesanan: ' . $e->getMessage());
            }

            $data['selectedTarif'] = $selectedTarif;
            // also provide available tarifs when possible
            $data['availableTarifs'] = $selectedTarif ? ($selectedTarif ? [$selectedTarif] : []) : [];

            // Cek jika view ada, jika tidak gunakan view sederhana
            if (!view()->exists('customer.detail_pesanan')) {
                // Buat view sederhana untuk testing
                return $this->showSimpleDetail($data);
            }

            return view('customer.detail_pesanan', $data);

        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Detail pesanan error: ' . $e->getMessage());
            \Log::error('Kode booking: ' . $kode);

            return redirect()->route('customer.riwayat')
                ->with('error', 'Detail pesanan tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail sederhana jika view tidak ada
     */
    private function showSimpleDetail($data)
    {
        $html = '<!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Detail Pesanan</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3>Detail Pesanan</h3>
                    </div>
                    <div class="card-body">
                        <h4>Kode Booking: ' . $data['kode_booking'] . '</h4>
                        <p>Status: ' . ($data['pemesanan']->status ?? 'Menunggu Pembayaran') . '</p>

                        <h5 class="mt-4">Informasi Perjalanan</h5>
                        <p>Rute: ' . $data['from'] . ' → ' . $data['to'] . '</p>
                        <p>Tanggal: ' . $data['date'] . '</p>
                        <p>Waktu: ' . $data['time'] . ' WIB</p>

                        <h5 class="mt-4">Penumpang</h5>';

        foreach ($data['penumpang'] as $index => $penumpang) {
            $html .= '<p>' . ($index + 1) . '. ' . $penumpang->nama_lengkap . ' - Kursi: ' . ($penumpang->nomor_kursi ?? 'Belum dipilih') . '</p>';
        }

        $html .= '
                        <h5 class="mt-4">Rincian Harga</h5>
                        <p>Harga per kursi: Rp ' . number_format($data['price'], 0, ',', '.') . '</p>
                        <p>Jumlah penumpang: ' . $data['qty'] . ' orang</p>
                        <p>Subtotal: Rp ' . number_format($data['subtotal'], 0, ',', '.') . '</p>';

        if ($data['discount'] > 0) {
            $html .= '<p>Diskon: -Rp ' . number_format($data['discount'], 0, ',', '.') . '</p>';
        }

        $html .= '
                        <p><strong>Total Bayar: Rp ' . number_format($data['total'], 0, ',', '.') . '</strong></p>

                        <div class="mt-4">
                            <a href="' . route('customer.riwayat') . '" class="btn btn-secondary">Kembali ke Riwayat</a>
                            <button onclick="window.print()" class="btn btn-primary">Cetak Tiket</button>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * API: Get kursi tersedia untuk jadwal tertentu
     */
    public function getKursiTersediaAPI(Request $request, $jadwalId = null)
    {
        try {
            $jadwalId = $jadwalId ?? $request->jadwal_id;

            if (!$jadwalId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal ID diperlukan'
                ], 400);
            }

            $jadwal = Jadwal::with('shuttle')->find($jadwalId);

            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            // Gunakan layout FIX dari shuttle
            $shuttle = $jadwal->shuttle;
            $layoutKursi = KursiTerpesan::getLayoutWithStatus($jadwalId, $shuttle->id, null);

            // Filter hanya kursi tersedia
            $availableSeats = [];
            foreach ($layoutKursi as $kursi) {
                if ($kursi['status'] === 'tersedia') {
                    $availableSeats[] = [
                        'nomor' => $kursi['nomor'],
                        'posisi' => $kursi['posisi'] ?? 'reguler',
                        'tipe' => $kursi['tipe'] ?? 'reguler',
                        'harga_tambahan' => $kursi['harga_tambahan'] ?? 0,
                        'kelas' => ($kursi['tipe'] ?? 'reguler') == 'premium' ? 'Premium' : 'Reguler'
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $availableSeats,
                'total_kursi' => $shuttle->total_kursi,
                'kursi_tersedia' => count($availableSeats),
                'kursi_terpesan' => $shuttle->total_kursi - count($availableSeats)
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getKursiTersediaAPI: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Batalkan pemilihan kursi (KURSI KEMBALI TERSEDIA, LAYOUT TETAP)
     */
     public function batalkanKursi($pemesananId)
    {
        DB::beginTransaction();

        try {
            // Ambil data pemesanan
            $pemesanan = Pemesanan::findOrFail($pemesananId);

            // Hapus kursi terpesan untuk pemesanan ini
            // INI YANG DIPERBAIKI: Hanya hapus data booking, LAYOUT TETAP ADA
            $deleted = KursiTerpesan::where('pemesanan_id', $pemesananId)
                ->delete();

            // Reset nomor kursi di detail penumpang
            DetailPenumpang::where('pemesanan_id', $pemesananId)
                ->update(['nomor_kursi' => null]);

            // Update kursi tersedia di jadwal
            if ($deleted > 0 && $pemesanan->jadwal) {
                $kursiTersediaBaru = $pemesanan->jadwal->kursi_tersedia + $deleted;
                $pemesanan->jadwal->update(['kursi_tersedia' => $kursiTersediaBaru]);
            }

            DB::commit();

            return redirect()->route('customer.kursi', ['pemesanan_id' => $pemesananId])
                ->with('success', 'Pemilihan kursi dibatalkan. Silakan pilih kursi kembali.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error in batalkanKursi: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal membatalkan pemilihan kursi: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan peta kursi untuk admin (LAYOUT STABIL)
     */
    public function petaKursi($jadwalId)
    {
        $jadwal = Jadwal::with(['shuttle', 'kursiTerpesan'])->findOrFail($jadwalId);
        $shuttle = $jadwal->shuttle;

        // Gunakan layout FIX dari shuttle
        if (method_exists($shuttle, 'getLayoutWithStatus')) {
            $layoutKursi = $shuttle->getLayoutWithStatus($jadwalId);
        } else {
            $layoutKursi = KursiTerpesan::generateLayoutKursi($shuttle->total_kursi ?? 9);
        }

        return view('admin.kursi.peta', compact('jadwal', 'shuttle', 'layoutKursi'));
    }

    /**
     * API: Get layout kursi untuk jadwal
     */
    public function getLayoutKursiAPI($jadwalId = null)
    {
        try {
            $jadwalId = $jadwalId ?? request('jadwal_id');

            if (!$jadwalId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal ID diperlukan'
                ], 400);
            }

            $jadwal = Jadwal::with('shuttle')->find($jadwalId);

            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            $shuttle = $jadwal->shuttle;

            if (method_exists($shuttle, 'getLayoutWithStatus')) {
                $layoutKursi = $shuttle->getLayoutWithStatus($jadwalId);
            } else {
                $layoutKursi = KursiTerpesan::generateLayoutKursi($shuttle->total_kursi ?? 9);
            }

            return response()->json([
                'success' => true,
                'data' => $layoutKursi,
                'shuttle' => $shuttle->only(['id', 'nama_shuttle', 'total_kursi'])
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
     * API untuk validasi kursi (sebelum submit form)
     */
     public function validateSeatsAPI(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwals,id',
            'kursi' => 'required|array',
            'kursi.*' => 'required|string',
            'pemesanan_id' => 'nullable|exists:pemesanan,id'
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
            $jadwal = Jadwal::with('shuttle')->find($request->jadwal_id);

            // Ambil layout kursi
            $shuttle = $jadwal->shuttle;
            $layoutKursi = KursiTerpesan::getLayoutWithStatus($request->jadwal_id, $shuttle->id);
            $validKursiNumbers = array_column($layoutKursi, 'nomor');

            $terpesan = [];
            $invalid = [];

            foreach ($request->kursi as $nomorKursi) {
                // Cek apakah nomor kursi valid
                if (!in_array($nomorKursi, $validKursiNumbers)) {
                    $invalid[] = $nomorKursi;
                    continue;
                }

                // Cek apakah kursi sudah terpesan (dengan lock untuk konsistensi)
                $kursiTerpesan = KursiTerpesan::where('jadwal_id', $request->jadwal_id)
                    ->where('nomor_kursi', $nomorKursi)
                    ->whereIn('status', ['terpesan', 'terisi'])
                    ->lockForUpdate()
                    ->exists();

                if ($kursiTerpesan) {
                    $terpesan[] = $nomorKursi;
                }
            }

            if (count($invalid) > 0 || count($terpesan) > 0) {
                $message = [];
                if (count($invalid) > 0) {
                    $message[] = 'Kursi ' . implode(', ', $invalid) . ' tidak valid.';
                }
                if (count($terpesan) > 0) {
                    $message[] = 'Kursi ' . implode(', ', $terpesan) . ' sudah terpesan oleh customer lain.';
                }

                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => implode(' ', $message),
                    'invalid_seats' => $terpesan
                ], 400);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kursi tersedia dan valid.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error in validateSeatsAPI: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * API untuk validasi kursi (alternatif)
     */
    public function validasiKursiAPI(Request $request)
    {
        return $this->validateSeatsAPI($request);
    }
}
