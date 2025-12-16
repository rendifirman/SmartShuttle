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
                'detailPenumpang'
            ])->where('id', $request->pemesanan_id)
              ->where('status', 'menunggu_pembayaran')
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
                if (method_exists($shuttle, 'initLayoutIfEmpty')) {
                    $shuttle->initLayoutIfEmpty();
                } else {
                    // Fallback: generate layout default
                    $shuttle->layout_kursi = json_encode(KursiTerpesan::generateLayoutKursi($shuttle->total_kursi ?? 9));
                    $shuttle->save();
                }
            }

            // Ambil layout dengan status terkini
            if (method_exists($shuttle, 'getLayoutWithStatus')) {
                $layoutKursi = $shuttle->getLayoutWithStatus($pemesanan->jadwal_id);
            } else {
                // Fallback: generate layout dari model KursiTerpesan
                $layoutKursi = KursiTerpesan::generateLayoutKursi($shuttle->total_kursi ?? 9);
            }

            // Backup: jika masih kosong, generate default
            if (empty($layoutKursi)) {
                $layoutKursi = KursiTerpesan::generateLayoutKursi(9);
            }

            // Ambil kursi terpesan (hanya untuk validasi tambahan)
            $kursiTerpesan = KursiTerpesan::where('jadwal_id', $pemesanan->jadwal_id)
                ->whereIn('status', ['terpesan', 'terisi'])
                ->pluck('nomor_kursi')
                ->toArray();

            return view('customer.kursi', compact(
                'pemesanan',
                'kursiTerpesan',
                'layoutKursi',
                'shuttle'
            ));

        } catch (\Exception $e) {
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
                ->firstOrFail();

            // Validasi 1: Jumlah kursi harus sama dengan jumlah penumpang
            if (count($request->kursi) !== $pemesanan->jumlah_penumpang) {
                throw new \Exception('Jumlah kursi harus sama dengan jumlah penumpang');
            }

            // Validasi 2: Ambil layout FIX dari shuttle untuk validasi kursi
            $shuttle = $pemesanan->jadwal->shuttle;

            // Ambil layout kursi
            if (method_exists($shuttle, 'getLayoutWithStatus')) {
                $layoutKursi = $shuttle->getLayoutWithStatus($pemesanan->jadwal_id);
            } else {
                $layoutKursi = KursiTerpesan::generateLayoutKursi($shuttle->total_kursi ?? 9);
            }

            $validKursiNumbers = array_column($layoutKursi, 'nomor');

            // Validasi 3: Cek apakah semua kursi yang dipilih valid
            foreach ($request->kursi as $nomorKursi) {
                if (!in_array($nomorKursi, $validKursiNumbers)) {
                    throw new \Exception("Kursi {$nomorKursi} tidak valid dalam layout shuttle");
                }
            }

            // Validasi 4: Cek apakah kursi sudah terpesan (double-check)
            foreach ($request->kursi as $nomorKursi) {
                $kursiTerpesan = KursiTerpesan::where('jadwal_id', $pemesanan->jadwal_id)
                    ->where('nomor_kursi', $nomorKursi)
                    ->whereIn('status', ['terpesan', 'terisi'])
                    ->exists();

                if ($kursiTerpesan) {
                    throw new \Exception("Kursi {$nomorKursi} sudah terpesan. Silakan pilih kursi lain.");
                }
            }

            // Simpan kursi untuk setiap penumpang
            $detailPenumpang = $pemesanan->detailPenumpang;

            foreach ($detailPenumpang as $index => $penumpang) {
                $nomorKursi = $request->kursi[$index] ?? null;

                if ($nomorKursi) {
                    // Update nomor kursi di detail penumpang
                    $penumpang->nomor_kursi = $nomorKursi;
                    $penumpang->save();

                    // Buat record di kursi_terpesan
                    KursiTerpesan::updateOrCreate(
                        [
                            'jadwal_id' => $pemesanan->jadwal_id,
                            'nomor_kursi' => $nomorKursi
                        ],
                        [
                            'detail_penumpang_id' => $penumpang->id,
                            'pemesanan_id' => $pemesanan->id,
                            'status' => 'terpesan'
                        ]
                    );
                }
            }

            DB::commit();

            // Redirect ke detail pesanan
            return redirect()->route('customer.detail_pesanan', ['kode' => $pemesanan->kode_booking])
                ->with('success', 'Kursi berhasil dipilih! Silakan konfirmasi detail pesanan.');

        } catch (\Exception $e) {
            DB::rollBack();

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

            // Siapkan data untuk view
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

            if (method_exists($shuttle, 'getLayoutWithStatus')) {
                $layoutKursi = $shuttle->getLayoutWithStatus($jadwalId);
            } else {
                $layoutKursi = KursiTerpesan::generateLayoutKursi($shuttle->total_kursi ?? 9);
            }

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
                'kursi_tersedia' => count($availableSeats)
            ]);

        } catch (\Exception $e) {
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
            KursiTerpesan::where('pemesanan_id', $pemesananId)
                ->delete();

            // Reset nomor kursi di detail penumpang
            DetailPenumpang::where('pemesanan_id', $pemesananId)
                ->update(['nomor_kursi' => null]);

            DB::commit();

            return redirect()->route('customer.kursi', ['pemesanan_id' => $pemesananId])
                ->with('success', 'Pemilihan kursi dibatalkan. Silakan pilih kursi kembali.');

        } catch (\Exception $e) {
            DB::rollBack();

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

        try {
            $jadwal = Jadwal::with('shuttle')->find($request->jadwal_id);

            // Ambil layout kursi
            $shuttle = $jadwal->shuttle;
            if (method_exists($shuttle, 'getLayoutWithStatus')) {
                $layoutKursi = $shuttle->getLayoutWithStatus($request->jadwal_id);
            } else {
                $layoutKursi = KursiTerpesan::generateLayoutKursi($shuttle->total_kursi ?? 9);
            }

            $validKursiNumbers = array_column($layoutKursi, 'nomor');

            $terpesan = [];
            $invalid = [];

            foreach ($request->kursi as $nomorKursi) {
                // Cek apakah nomor kursi valid
                if (!in_array($nomorKursi, $validKursiNumbers)) {
                    $invalid[] = $nomorKursi;
                    continue;
                }

                // Cek apakah kursi sudah terpesan
                $kursiTerpesan = KursiTerpesan::where('jadwal_id', $request->jadwal_id)
                    ->where('nomor_kursi', $nomorKursi)
                    ->whereIn('status', ['terpesan', 'terisi'])
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
                    $message[] = 'Kursi ' . implode(', ', $terpesan) . ' sudah terpesan.';
                }

                return response()->json([
                    'success' => false,
                    'message' => implode(' ', $message)
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kursi tersedia dan valid.'
            ]);

        } catch (\Exception $e) {
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
