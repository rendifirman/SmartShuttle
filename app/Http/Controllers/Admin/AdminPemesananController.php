<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\DetailPenumpang;
use App\Models\Promo;
use App\Models\Rute;
use App\Models\RuteJadwal;
use App\Models\User;
use App\Models\DriverJadwal;

class AdminPemesananController extends Controller
{
    /**
     * Get jadwal by rute and date
     */
    public function getJadwal(Request $request)
    {
        try {
            $rute_id = $request->query('rute_id');
            $tanggal = $request->query('tanggal');

            if (!$rute_id || !$tanggal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rute dan tanggal harus diisi',
                    'jadwal' => []
                ]);
            }

            // Get rute jadwals
            $ruteJadwals = RuteJadwal::where('rute_id', $rute_id)->get();
            $jadwalIds = $ruteJadwals->pluck('jadwal_id')->toArray();

            // Get jadwals with matching date and available seats
            $jadwals = Jadwal::whereIn('id', $jadwalIds)
                ->whereDate('tanggal_keberangkatan', $tanggal)
                ->with(['shuttle', 'rutes'])
                ->where('kursi_tersedia', '>', 0)
                ->get();

            return response()->json([
                'success' => true,
                'jadwal' => $jadwals->map(function($j) {
                    return [
                        'id' => $j->id,
                        'waktu_keberangkatan' => $j->waktu_keberangkatan,
                        'harga_total' => $j->harga_total,
                        'kursi_tersedia' => $j->kursi_tersedia,
                            'shuttle' => [
                                'id' => $j->shuttle->id ?? null,
                                'nama_shuttle' => $j->shuttle->nama_shuttle ?? 'N/A',
                                'fasilitas' => $j->shuttle->fasilitas ?? null,
                                'kapasitas' => $j->shuttle->kapasitas ?? null
                            ]
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting jadwal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'jadwal' => []
            ], 500);
        }
    }

    /**
     * Show admin booking page that mirrors customer flow
     */
    public function showCreatePage()
    {
        // Load necessary data for the admin booking page
        $rutes = Rute::all();
        $customers = User::select('id','name','phone','email')->get();

        return view('admin.transaksi.pemesanan_admin_flow', compact('rutes', 'customers'));
    }

    /**
     * Validate promo code
     */
    public function validatePromo(Request $request)
    {
        try {
            $kode = strtoupper($request->query('kode'));

            if (!$kode) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Kode promo tidak boleh kosong'
                ]);
            }

            $promo = Promo::where('kode_promo', $kode)
                ->where('status', true)
                ->whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_berakhir', '>=', now())
                ->first();

            if (!$promo) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Kode promo tidak valid atau sudah kadaluarsa'
                ]);
            }

            // Check kuota
            if ($promo->kuota && $promo->terpakai >= $promo->kuota) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Kuota promo sudah habis'
                ]);
            }

            return response()->json([
                'valid' => true,
                'nama' => $promo->nama_promo,
                'kode' => $promo->kode_promo,
                'deskripsi' => $promo->deskripsi,
                'diskon' => $promo->diskon,
                'message' => 'Kode promo valid'
            ]);
        } catch (\Exception $e) {
            Log::error('Error validating promo: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get taken seats for a jadwal
     */
    public function getTakenSeats($jadwal_id)
    {
        try {
            $taken = DetailPenumpang::whereHas('pemesanan', function($q) use ($jadwal_id) {
                $q->where('id_jadwal', $jadwal_id)
                  ->where('status', '!=', 'cancelled');
            })->whereNotNull('nomor_kursi')
              ->pluck('nomor_kursi')
              ->toArray();

            return response()->json([ 'taken' => $taken ]);
        } catch (\Exception $e) {
            Log::error('Error getting taken seats: ' . $e->getMessage());
            return response()->json([ 'taken' => [] ], 500);
        }
    }

    /**
     * Create pemesanan from admin
     */
    public function createPemesanan(Request $request)
    {
        Log::info('Admin Creating Pemesanan:', $request->all());

        try {
            // Validasi input
            $validated = $request->validate([
                'customer_id' => 'nullable|exists:users,id',
                'nama_pemesan' => 'required|string|max:100',
                'telepon_pemesan' => 'required|string|max:20',
                'email_pemesan' => 'required|email|max:100',
                'jadwal_id' => 'required|exists:jadwals,id',
                'jumlah_penumpang' => 'required|integer|min:1|max:10',
                'penumpang' => 'required|array|min:1',
                'penumpang.*.nama_lengkap' => 'required|string|max:100',
                'penumpang.*.nik' => 'required|string|size:16',
                'penumpang.*.jenis_kelamin' => 'required|string|in:L,P',
                'penumpang.*.telepon' => 'required|string|min:10|max:15',
                'penumpang.*.email' => 'required|email|max:100',
                'penumpang.*.nomor_kursi' => 'nullable|string',
                'kode_promo' => 'nullable|string',
                'catatan' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            // Get jadwal
            $jadwal = Jadwal::findOrFail($validated['jadwal_id']);

            // Check kursi tersedia
            if ($jadwal->kursi_tersedia < $validated['jumlah_penumpang']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah kursi tersedia tidak cukup'
                ], 422);
            }

            // Calculate harga
            $hargaPerSeat = $jadwal->harga_total;
            $ruteJadwal = RuteJadwal::where('jadwal_id', $jadwal->id)->first();
            if ($ruteJadwal) {
                $rute = Rute::find($ruteJadwal->rute_id);
                if ($rute) {
                    $masterTarif = $rute->getActiveMasterTarif();
                    if ($masterTarif) {
                        $base = $masterTarif->harga_dasar ?? $rute->harga_dasar ?? $jadwal->harga_total;
                        $hargaPerSeat = (float)$masterTarif->hitungTarif($base);
                    } else {
                        $hargaPerSeat = $rute->harga_dasar ?? $jadwal->harga_total;
                    }
                }
            }

            $hargaTotal = $hargaPerSeat * $validated['jumlah_penumpang'];
            $diskon = 0;
            $promoId = null;

            // Validate promo jika ada
            if (!empty($validated['kode_promo'])) {
                $promo = Promo::where('kode_promo', strtoupper($validated['kode_promo']))
                    ->where('status', true)
                    ->whereDate('tanggal_mulai', '<=', now())
                    ->whereDate('tanggal_berakhir', '>=', now())
                    ->first();

                if ($promo) {
                    if ($promo->kuota && $promo->terpakai >= $promo->kuota) {
                        throw new \Exception('Kuota promo sudah habis');
                    }

                    if ($hargaTotal < $promo->minimal_pembelian) {
                        throw new \Exception('Minimal pembelian Rp ' . number_format($promo->minimal_pembelian));
                    }

                    $diskon = round($hargaTotal * ($promo->diskon / 100));
                    $promoId = $promo->id;
                }
            }

            $totalAfterDiscount = $hargaTotal - $diskon;

            // Generate kode booking
            $kodeBooking = 'BK' . strtoupper(Str::random(8)) . date('YmdHis');

            // Create pemesanan
            $pemesanan = Pemesanan::create([
                'customer_id' => $validated['customer_id'],
                'kode_booking' => $kodeBooking,
                'id_jadwal' => $validated['jadwal_id'],
                'nama_pemesan' => $validated['nama_pemesan'],
                'telepon_pemesan' => $validated['telepon_pemesan'],
                'email_pemesan' => $validated['email_pemesan'],
                'jumlah_penumpang' => $validated['jumlah_penumpang'],
                'harga_total' => (int) $hargaTotal,
                'diskon' => $diskon,
                'promo_id' => $promoId,
                'total_bayar' => $totalAfterDiscount,
                'status' => 'pending',
                'catatan' => $validated['catatan'] ?? null,
                'created_by' => auth('admin')->id(),
            ]);

            // Create detail penumpang
            foreach ($validated['penumpang'] as $index => $penumpangData) {
                DetailPenumpang::create([
                    'pemesanan_id' => $pemesanan->id,
                    'nama_lengkap' => $penumpangData['nama_lengkap'],
                    'nik' => $penumpangData['nik'],
                    'jenis_kelamin' => $penumpangData['jenis_kelamin'],
                    'telepon' => $penumpangData['telepon'],
                    'email' => $penumpangData['email'],
                    'nomor_kursi' => $penumpangData['nomor_kursi'] ?? null,
                ]);
            }

            // Update kursi tersedia
            $jadwal->decrement('kursi_tersedia', $validated['jumlah_penumpang']);

            // Update promo usage
            if ($promoId) {
                Promo::find($promoId)->increment('terpakai');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dibuat',
                'data' => [
                    'kode_booking' => $pemesanan->kode_booking,
                    'id' => $pemesanan->id
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating pemesanan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete pemesanan
     */
    public function deletePemesanan($id)
    {
        try {
            $pemesanan = Pemesanan::findOrFail($id);

            // Check if can be deleted
            if ($pemesanan->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pemesanan dengan status pending yang dapat dihapus'
                ], 422);
            }

            DB::beginTransaction();

            // Return kursi tersedia
            $jadwal = $pemesanan->jadwal;
            if ($jadwal) {
                $jadwal->increment('kursi_tersedia', $pemesanan->jumlah_penumpang);
            }

            // Return promo usage
            if ($pemesanan->promo_id) {
                Promo::find($pemesanan->promo_id)->decrement('terpakai');
            }

            // Delete detail penumpang
            DetailPenumpang::where('pemesanan_id', $pemesanan->id)->delete();

            // Delete pemesanan
            $pemesanan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting pemesanan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Redirect admin ke customer pesan dengan session admin
     */
    public function adminBooking(Request $request)
    {
        try {
            $admin = auth('admin')->user();

            // store flag untuk UI indicator
            session([
                'admin_booking_session' => true,
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
                'admin_role' => $admin->getRoleNames()->first(),
            ]);

            // perform a customer login explicitly on the web guard so middleware('auth') sees it
            Auth::guard('web')->login($admin);

            // mirror payload that login() normally stores in session
            session()->put('user', [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'avatar' => $admin->avatar_url ?? null,
                'membership_status' => $admin->membership_status ?? 'non_member',
                'membership_level' => $admin->membership_level ?? null,
            ]);
            session()->save();

            Log::info('Admin starting booking flow (logged in as customer)', [
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_role' => $admin->getRoleNames()->first(),
            ]);

            // redirect ke beranda customer setelah login
            return redirect()->route('customer.beranda')
                ->with('admin_booking', true)
                ->with('admin_name', $admin->name);

        } catch (\Exception $e) {
            Log::error('Error in admin booking redirect: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Kembali ke halaman admin transaksi dari customer pesan
     */
    public function backToAdmin()
    {
        // preserve admin identity: grab admin id before clearing session
        $adminId = session('admin_id');

        // clear admin booking session flags (but do not log out admin guard)
        session()->forget(['admin_booking_session', 'admin_id', 'admin_name', 'admin_email', 'admin_role']);

        // logout only the web/customer guard and clear the customer session payload
        try {
            Auth::guard('web')->logout();
        } catch (\Exception $e) {
            Log::warning('Logout web guard failed: ' . $e->getMessage());
        }
        session()->forget('user');

        // ensure admin guard is still authenticated; if not, re-login admin guard using stored id
        if ($adminId && !auth('admin')->check()) {
            try {
                Auth::guard('admin')->loginUsingId($adminId);
            } catch (\Exception $e) {
                Log::warning('Re-login admin guard failed: ' . $e->getMessage());
            }
        }

        // redirect back to the admin transaksi perjalanan page
        return redirect()->route('admin.perjalanan')
            ->with('success', 'Anda kembali ke halaman admin');
    }
}
