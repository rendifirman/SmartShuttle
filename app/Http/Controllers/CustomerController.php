<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\API\AuthController;
use App\Models\Outlet;
use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\SyaratKetentuan;
use App\Models\MProfilePerusahaan;
use App\Models\KebijakanPrivasi;
use App\Models\Branch;
use App\Models\Promo;
use App\Models\Pemesanan;
use App\Models\DetailPenumpang;
use App\Models\MMasterKontak;
use App\Models\PesanKontak;
use App\Models\MLayanan;
use App\Models\RuteJadwal;
use App\Models\User;
use App\Models\MetodePembayaran;
use App\Models\Transaksi;

use App\Models\MembershipPayment;
use Carbon\Carbon;

// Helper function untuk mendapatkan inisial nama
if (!function_exists('getInitials')) {
    function getInitials($name) {
        if (empty($name)) {
            return 'GU';
        }

        $words = explode(' ', $name);
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }

        // Jika hanya 1 kata, ambil 2 karakter pertama
        if (strlen($initials) == 1) {
            $initials = strtoupper(substr($name, 0, 2));
        } else {
            // Ambil maksimal 2 huruf inisial
            $initials = substr($initials, 0, 2);
        }

        return $initials;
    }
}

class CustomerController extends Controller
{
    // Halaman beranda (bisa diakses tamu)
    public function beranda()
    {
        // Cek jika user sudah login dari session
        /** @var array|null $user */
        $user = session()->get('user');

        // **AMBIL DATA OUTLET AKTIF DIKELOMPOKKAN BERDASARKAN KOTA**
        $outletsGrouped = Outlet::with('branch')
            ->where('status', 'aktif')
            ->orderBy('nama_outlet')
            ->get()
            ->groupBy(function ($outlet) {
                return $outlet->branch ? $outlet->branch->kota : 'Lainnya';
            });

        $layanan = \App\Models\MLayanan::where('status_aktif', true)
            ->orderBy('urutan_tampilan', 'asc')
            ->take(3)
            ->get();

        $profile = MProfilePerusahaan::where('status', 'active')->first();

        return view('customer.beranda', compact('user', 'outletsGrouped', 'layanan', 'profile'));
    }

    public function outlet()
    {
        // Cek jika user sudah login dari session
        $user = session()->get('user');

        // Ambil semua outlet yang aktif
        $outlets = Outlet::with('branch')
            ->where('status', 'aktif')
            ->orderBy('nama_outlet')
            ->get();

        // Ambil data cabang untuk filter
        $branches = Branch::where('status', 'aktif')
            ->orderBy('kota')
            ->get();

        // Ambil data kota unik dari branches untuk filter outlet
        $kotaList = Branch::select('kota')
            ->distinct()
            ->where('status', 'aktif')
            ->orderBy('kota')
            ->pluck('kota')
            ->toArray();

        return view('customer.outlet', compact('user', 'outlets', 'branches', 'kotaList'));
    }

    // Form login
    public function showLogin()
    {
        // Jika sudah login, redirect ke beranda
        if (session()->has('user')) {
            return redirect()->route('customer.beranda')->with('info', 'Anda sudah login!');
        }

        return view('customer.login');
    }

    // Form register
    public function showRegister()
    {
        // Jika sudah login, redirect ke beranda
        if (session()->has('user')) {
            return redirect()->route('customer.beranda')->with('info', 'Anda sudah login!');
        }

        // Ambil data dari tabel khusus
        $syaratKetentuan = SyaratKetentuan::getUntukPengguna();
        $kebijakanPrivasi = KebijakanPrivasi::getAktif();

        return view('customer.register', [
            'syaratKetentuan' => $syaratKetentuan,
            'kebijakanPrivasi' => $kebijakanPrivasi
        ]);
    }

    // Proses register
    public function register(Request $request)
    {
        \Log::info('CustomerController::register - Starting', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'all_data' => $request->all()
        ]);

        try {
            // Tambahkan validasi di sini
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed', // pastikan ada password_confirmation
            ]);

            \Log::info('CustomerController::register - Validation passed', [
                'validated_data' => array_keys($validated)
            ]);

            // Gunakan AuthController untuk registrasi langsung
            $authController = new AuthController();
            $result = $authController->performRegistration([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'password_confirmation' => $request->password_confirmation,
            ]);

            \Log::info('CustomerController::register - performRegistration result', [
                'has_errors' => isset($result['errors']),
                'has_user' => isset($result['user']),
                'has_token' => isset($result['token'])
            ]);

            if (isset($result['errors'])) {
                \Log::warning('CustomerController::register - Validation errors from performRegistration', [
                    'errors' => $result['errors']
                ]);
                return back()->withErrors($result['errors'])->withInput();
            }

            return redirect()->route('customer.login')
                ->with('success', 'Registrasi berhasil! Silakan login.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('CustomerController::register - ValidationException', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('CustomerController::register - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
        }
    }

    // Proses login
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // pastikan remember boolean (true jika dicentang)
            $remember = $request->filled('remember');

            // langsung gunakan auth()->attempt agar session dan cookie remember dibuat oleh guard web
            $credentials = [
                'email' => $validated['email'],
                'password' => $validated['password'],
            ];

            if (!auth()->attempt($credentials, $remember)) {
                return back()->withErrors(['message' => 'Email atau password salah'])->withInput();
            }

            // regenerate session untuk keamanan (prevent session fixation)
            $request->session()->regenerate();

            // ambil user yang ter-auth
            $user = auth()->user();

            // simpan minimal informasi di session untuk tampilan UI (hindari menyimpan model lengkap)
            session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);

            // jika perlu token API untuk UI, buat di sini (opsional)
            // session()->put('token', $user->createToken('SmartShuttle-API')->plainTextToken);

            return redirect()->route('customer.beranda');

        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    // Proses logout
    public function logout(Request $request)
    {
        try {
            // Logout Laravel auth (menghapus session)
            Auth::logout();

            // Hapus remember me cookie jika ada
            $recaller = Auth::getRecallerName(); // nama cookie yang dipakai remember me
            Cookie::queue(Cookie::forget($recaller));

            // Hapus session manual
            session()->forget(['user', 'token']);
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('customer.beranda');

        } catch (\Exception $e) {
            // fallback: hapus session tetap
            session()->forget(['user', 'token']);
            return redirect()->route('customer.beranda');
        }
    }

    // Halaman pencarian shuttle (GET method)
    public function showSearch(Request $request)
    {
        // Ambil semua outlet dan kelompokkan berdasarkan kota
        $outlets = Outlet::with('branch')
            ->where('status', 'aktif')
            ->get()
            ->map(function ($outlet) {
                return [
                    'id' => $outlet->id,
                    'nama_outlet' => $outlet->nama_outlet,
                    'alamat' => $outlet->alamat_lengkap,
                    'kota' => $outlet->branch->kota ?? 'Unknown',
                    'branch_id' => $outlet->branch_id
                ];
            })
            ->toArray();

        // Kelompokkan outlet berdasarkan kota
        $outletsGrouped = [];
        foreach ($outlets as $outlet) {
            $kota = $outlet['kota'];
            if (!isset($outletsGrouped[$kota])) {
                $outletsGrouped[$kota] = [];
            }
            $outletsGrouped[$kota][] = $outlet;
        }

        $data = [
            'outletsGrouped' => $outletsGrouped,
        ];

        // Jika ada parameter pencarian, proses pencarian
        if ($request->has('departure_outlet') && $request->has('destination_outlet')) {
            $searchData = $this->processSearch($request);
            $data = array_merge($data, $searchData);
        }

        return view('customer.search', $data);
    }

    /**
     * Proses pencarian jadwal
     */
    private function processSearch(Request $request)
    {
        $validated = $request->validate([
            'departure_outlet' => 'required|exists:outlets,id',
            'destination_outlet' => 'required|exists:outlets,id|different:departure_outlet',
            'departure_date' => 'required|date|after_or_equal:today',
            'passenger_count' => 'required|integer|min:1|max:10',
        ]);

        // Ambil data outlet asal dan tujuan
        $departureOutlet = Outlet::with('branch')->find($validated['departure_outlet']);
        $destinationOutlet = Outlet::with('branch')->find($validated['destination_outlet']);

        $validated['departure_outlet_data'] = $departureOutlet;
        $validated['destination_outlet_data'] = $destinationOutlet;
        $validated['departure_city'] = $departureOutlet->branch->kota ?? 'Unknown';
        $validated['destination_city'] = $destinationOutlet->branch->kota ?? 'Unknown';

        // Cari jadwal berdasarkan rute yang menghubungkan kedua kota
        $jadwals = $this->findAvailableSchedules(
            $validated['departure_city'],
            $validated['destination_city'],
            $departureOutlet,
            $destinationOutlet,
            $validated['departure_date'],
            $validated['passenger_count']
        );

        return [
            'validated' => $validated,
            'jadwals' => $jadwals
        ];
    }

    /**
     * Mencari jadwal yang tersedia dengan logika yang lebih baik
     */
    private function findAvailableSchedules($departureCity, $destinationCity, $departureOutlet, $destinationOutlet, $departureDate, $passengerCount)
    {
        // Cari semua rute yang mungkin
        // 1. Rute langsung dari departureCity ke destinationCity
        // 2. Rute yang memiliki pemberhentian di departureCity dan destinationCity
        $allRutes = Rute::where('status', 'aktif')->get();
        $validRuteIds = [];

        foreach ($allRutes as $rute) {
            if ($this->isRouteValid($rute, $departureCity, $destinationCity, $departureOutlet->nama_outlet, $destinationOutlet->nama_outlet)) {
                $validRuteIds[] = $rute->id;
            }
        }

        if (empty($validRuteIds)) {
            return collect();
        }

        // Cari jadwal berdasarkan rute yang valid
        $jadwals = Jadwal::with(['shuttle', 'rutes'])
            ->where('tanggal_keberangkatan', $departureDate)
            ->where('status', 'tersedia')
            ->where('kursi_tersedia', '>=', $passengerCount)
            ->whereHas('rutes', function ($query) use ($validRuteIds) {
                $query->whereIn('rute_id', $validRuteIds);
            })
            ->orderBy('waktu_keberangkatan')
            ->get()
            ->map(function ($jadwal) use ($departureCity, $destinationCity) {
                // Hitung harga berdasarkan segment yang sesuai
                $totalHarga = $this->calculatePriceForRoute($jadwal, $departureCity, $destinationCity);
                $jadwal->harga_total = $totalHarga ?? $jadwal->harga_total;

                // Format rute string
                $jadwal->rute_string = $departureCity . ' → ' . $destinationCity;

                return $jadwal;
            });

        return $jadwals;
    }

    /**
     * Cek apakah rute valid untuk pencarian
     */
    private function isRouteValid($rute, $departureCity, $destinationCity, $departureOutlet, $destinationOutlet)
    {
        // Jika rute langsung dari kota asal ke kota tujuan
        if ($rute->kota_asal == $departureCity && $rute->kota_tujuan == $destinationCity) {
            return $this->checkOutletsInRoute($rute, $departureOutlet, $destinationOutlet);
        }

        // Jika departureCity adalah kota asal dan destinationCity ada dalam pemberhentian
        if ($rute->kota_asal == $departureCity) {
            $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
            foreach ($pemberhentian as $stop) {
                if (($stop['kota'] ?? '') == $destinationCity) {
                    return $this->checkOutletsInRoute($rute, $departureOutlet, $destinationOutlet, $destinationCity);
                }
            }
        }

        // Jika destinationCity adalah kota tujuan dan departureCity ada dalam pemberhentian
        if ($rute->kota_tujuan == $destinationCity) {
            $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
            foreach ($pemberhentian as $stop) {
                if (($stop['kota'] ?? '') == $departureCity) {
                    return $this->checkOutletsInRoute($rute, $departureOutlet, $destinationOutlet, null, $departureCity);
                }
            }
        }

        // Jika kedua kota ada dalam pemberhentian (transit)
        $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
        $foundDeparture = false;
        $foundDestination = false;

        foreach ($pemberhentian as $stop) {
            if (($stop['kota'] ?? '') == $departureCity) {
                $foundDeparture = true;
            }
            if (($stop['kota'] ?? '') == $destinationCity) {
                $foundDestination = true;
            }
        }

        if ($foundDeparture && $foundDestination) {
            return $this->checkOutletsInRoute($rute, $departureOutlet, $destinationOutlet, $destinationCity, $departureCity);
        }

        return false;
    }

    /**
     * Cek apakah outlet tersedia dalam rute
     */
    private function checkOutletsInRoute($rute, $departureOutlet, $destinationOutlet, $destCityInStop = null, $depCityInStop = null)
    {
        $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
        $departureValid = false;
        $destinationValid = false;

        // Cek untuk departure outlet
        if ($rute->kota_asal == $depCityInStop || $depCityInStop === null) {
            // Cek dalam pemberhentian untuk departure city
            foreach ($pemberhentian as $stop) {
                if (($stop['kota'] ?? '') == ($depCityInStop ?? $rute->kota_asal)) {
                    if (in_array($departureOutlet, $stop['outlets'] ?? [])) {
                        $departureValid = true;
                        break;
                    }
                }
            }
            // Jika kota asal adalah departure city dan tidak ada dalam pemberhentian, anggap valid
            if (!$departureValid && $rute->kota_asal == ($depCityInStop ?? $rute->kota_asal)) {
                $departureValid = true;
            }
        }

        // Cek untuk destination outlet
        if ($rute->kota_tujuan == $destCityInStop || $destCityInStop === null) {
            // Cek dalam pemberhentian untuk destination city
            foreach ($pemberhentian as $stop) {
                if (($stop['kota'] ?? '') == ($destCityInStop ?? $rute->kota_tujuan)) {
                    if (in_array($destinationOutlet, $stop['outlets'] ?? [])) {
                        $destinationValid = true;
                        break;
                    }
                }
            }
            // Jika kota tujuan adalah destination city dan tidak ada dalam pemberhentian, anggap valid
            if (!$destinationValid && $rute->kota_tujuan == ($destCityInStop ?? $rute->kota_tujuan)) {
                $destinationValid = true;
            }
        }

        return $departureValid && $destinationValid;
    }

    /**
     * Hitung harga berdasarkan segment rute
     */
    private function calculatePriceForRoute($jadwal, $departureCity, $destinationCity)
    {
        // Ambil rute pertama dari jadwal (asumsi satu jadwal satu rute)
        $ruteJadwal = RuteJadwal::where('jadwal_id', $jadwal->id)->first();
        if (!$ruteJadwal) {
            return $jadwal->harga_total;
        }

        $rute = \App\Models\Rute::find($ruteJadwal->rute_id);
        if (!$rute) {
            return $jadwal->harga_total;
        }

        // Jika rute langsung, gunakan harga dasar
        if ($rute->kota_asal == $departureCity && $rute->kota_tujuan == $destinationCity) {
            return $rute->harga_dasar;
        }

        // Hitung harga proporsional berdasarkan jarak (jika ada data jarak)
        if ($rute->jarak) {
            // Asumsi harga per km
            $hargaPerKm = $rute->harga_dasar / $rute->jarak;

            // Untuk sekarang, return harga dasar (bisa dikembangkan lebih lanjut)
            return $rute->harga_dasar;
        }

        return $jadwal->harga_total;
    }

    /**
     * API endpoint untuk pencarian (digunakan untuk AJAX)
     */
    public function search(Request $request)
    {
        return $this->showSearch($request);
    }

    /**
     * Tampilkan halaman pemesanan
     */
    public function showBooking(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'penumpang' => 'required|integer|min:1|max:10',
            'outlet_asal' => 'required|exists:outlets,id',
            'outlet_tujuan' => 'required|exists:outlets,id',
        ]);

        // Ambil data jadwal
        $jadwal = Jadwal::with(['shuttle', 'rutes'])->find($validated['jadwal_id']);

        // Ambil data outlet
        $outletAsal = Outlet::with('branch')->find($validated['outlet_asal']);
        $outletTujuan = Outlet::with('branch')->find($validated['outlet_tujuan']);

        // Cek ketersediaan kursi
        if ($jadwal->kursi_tersedia < $validated['penumpang']) {
            return redirect()->back()
                ->with('error', 'Kursi tidak tersedia. Hanya tersisa ' . $jadwal->kursi_tersedia . ' kursi.');
        }

        // Hitung total harga
        $hargaPerKursi = $jadwal->harga_total; // Harga per kursi
        $totalHarga = $hargaPerKursi * $validated['penumpang'];

        // Cek jika ada promo yang sudah diterapkan di session
        $appliedPromo = session()->get('applied_promo');
        $diskon = 0;
        $totalAfterDiscount = $totalHarga;

        if ($appliedPromo) {
            // Validasi ulang promo untuk memastikan masih valid
            $promo = Promo::find($appliedPromo['id']);
            if ($promo && $promo->is_aktif && $totalHarga >= $promo->minimal_pembelian) {
                $diskon = $promo->hitungDiskon($totalHarga);
                $totalAfterDiscount = $totalHarga - $diskon;
            } else {
                // Jika promo tidak valid, hapus dari session
                session()->forget('applied_promo');
            }
        }

        // Cek loyalty discount di session
        $loyaltyDiscount = session()->get('loyalty_discount');
        $diskonLoyalty = 0;

        if ($loyaltyDiscount) {
            $user = session()->get('user', []);
            if ($loyaltyDiscount['user_id'] == ($user['id'] ?? null)) {
                $diskonLoyalty = $loyaltyDiscount['discount_amount'];
                $totalAfterDiscount -= $diskonLoyalty;

                if ($totalAfterDiscount < 0) {
                    $totalAfterDiscount = 0;
                }
            }
        }

        // Ambil data user dari session jika ada
        $user = session()->get('user', []);

        // Ambil kota asal dan tujuan dari outlet
        $kotaAsal = $outletAsal->branch ? $outletAsal->branch->kota : 'Kota Asal';
        $kotaTujuan = $outletTujuan->branch ? $outletTujuan->branch->kota : 'Kota Tujuan';

        // Cari rute yang sesuai dengan kota asal dan tujuan
        $rutePertama = null;
        $ruteTerakhir = null;
        $ruteString = '';

        if ($jadwal->rutes && $jadwal->rutes->count() > 0) {
            $rutePertama = $jadwal->rutes->first();
            $ruteTerakhir = $jadwal->rutes->last();
            $ruteString = $rutePertama->kota_asal . ' → ' . $ruteTerakhir->kota_tujuan;
        }

        return view('customer.pesan', [
            'jadwal' => $jadwal,
            'penumpang' => $validated['penumpang'],
            'outletAsal' => $outletAsal,
            'outletTujuan' => $outletTujuan,
            'kotaAsal' => $kotaAsal,
            'kotaTujuan' => $kotaTujuan,
            'rute_pertama' => $rutePertama,
            'rute_terakhir' => $ruteTerakhir,
            'rute_string' => $ruteString,
            'totalHarga' => $totalHarga,
            'diskon' => $diskon,
            'diskonLoyalty' => $diskonLoyalty,
            'totalAfterDiscount' => $totalAfterDiscount,
            'appliedPromo' => $appliedPromo,
            'loyaltyDiscount' => $loyaltyDiscount,
            'user' => $user,
        ]);
    }

    /**
     * Validasi promo untuk customer (AJAX endpoint)
     */
    public function validatePromo(Request $request)
    {
        \Log::info('CustomerController::validatePromo - Starting', [
            'promo_code' => $request->promo_code,
            'total_amount' => $request->total_amount,
            'all_data' => $request->all()
        ]);

        try {
            $request->validate([
                'promo_code' => 'required|string',
                'total_amount' => 'required|numeric'
            ]);

            $promoCode = strtoupper($request->promo_code);

            \Log::info('CustomerController::validatePromo - Searching promo:', ['kode_promo' => $promoCode]);

            $promo = Promo::where('kode_promo', $promoCode)
                ->where('status', true)
                ->whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_berakhir', '>=', now())
                ->first();

            if (!$promo) {
                \Log::warning('CustomerController::validatePromo - Promo not found or inactive');
                return response()->json([
                    'success' => false,
                    'message' => 'Kode promo tidak valid atau sudah kadaluarsa'
                ]);
            }

            \Log::info('CustomerController::validatePromo - Promo found:', [
                'id' => $promo->id,
                'nama' => $promo->nama_promo,
                'kuota' => $promo->kuota,
                'terpakai' => $promo->terpakai,
                'minimal_pembelian' => $promo->minimal_pembelian
            ]);

            // Cek kuota
            if ($promo->kuota && $promo->terpakai >= $promo->kuota) {
                \Log::warning('CustomerController::validatePromo - Quota exceeded', [
                    'kuota' => $promo->kuota,
                    'terpakai' => $promo->terpakai
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota promo sudah habis'
                ]);
            }

            // Cek minimal pembelian
            if ($request->total_amount < $promo->minimal_pembelian) {
                \Log::warning('CustomerController::validatePromo - Minimum purchase not met', [
                    'total_amount' => $request->total_amount,
                    'minimal_pembelian' => $promo->minimal_pembelian
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Minimal pembelian Rp ' . number_format($promo->minimal_pembelian, 0, ',', '.')
                ]);
            }

            // Hitung diskon
            $diskon = $promo->hitungDiskon($request->total_amount);
            $totalAfterDiscount = $request->total_amount - $diskon;

            \Log::info('CustomerController::validatePromo - Discount calculated', [
                'diskon' => $diskon,
                'total_after_discount' => $totalAfterDiscount
            ]);

            // Simpan promo ke session
            session()->put('applied_promo', [
                'id' => $promo->id,
                'kode' => $promo->kode_promo,
                'nama' => $promo->nama_promo,
                'deskripsi' => $promo->deskripsi,
                'diskon' => $diskon,
                'total_setelah_diskon' => $totalAfterDiscount
            ]);

            \Log::info('CustomerController::validatePromo - Promo saved to session', [
                'applied_promo' => session()->get('applied_promo')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kode promo berhasil diterapkan!',
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
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('CustomerController::validatePromo - ValidationException', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors()['promo_code'] ?? $e->errors()['total_amount'] ?? ['Data tidak valid'])
            ]);
        } catch (\Exception $e) {
            \Log::error('CustomerController::validatePromo - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Hapus promo dari session (AJAX endpoint)
     */
    public function removePromo(Request $request)
    {
        \Log::info('CustomerController::removePromo - Removing promo from session');

        try {
            session()->forget('applied_promo');

            \Log::info('CustomerController::removePromo - Promo removed successfully');

            return response()->json([
                'success' => true,
                'message' => 'Promo berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('CustomerController::removePromo - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus promo'
            ]);
        }
    }

    /**
     * Proses pemesanan shuttle
     */
    public function prosesPemesanan(Request $request)
    {
        // Debug: lihat data yang masuk
        \Log::info('Proses Pemesanan Request Data:', $request->all());

        // Validasi input
        $validator = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwals,id',
            'jumlah_penumpang' => 'required|integer|min:1|max:10',
            'nama_pemesan' => 'required|string|max:100',
            'telepon_pemesan' => 'required|string|max:20',
            'email_pemesan' => 'required|email|max:100',
            'penumpang' => 'required|array|min:1',
            'penumpang.*.nama_lengkap' => 'required|string|max:100',
            'penumpang.*.nik' => 'required|string|size:16', // Pastikan NIK 16 digit
            'penumpang.*.jenis_kelamin' => 'required|string|in:L,P',
            'kode_promo' => 'nullable|string|exists:promos,kode', // Perhatikan nama tabel
            'catatan' => 'nullable|string',
            'diskon_amount' => 'nullable|numeric',
            'total_after_discount' => 'required|numeric',
        ], [
            'penumpang.*.nama_lengkap.required' => 'Nama lengkap penumpang harus diisi',
            'penumpang.*.nik.required' => 'NIK penumpang harus diisi',
            'penumpang.*.nik.size' => 'NIK harus 16 digit',
            'penumpang.*.jenis_kelamin.required' => 'Jenis kelamin penumpang harus dipilih',
            'penumpang.*.jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation errors:', $validator->errors()->toArray());

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan dalam pengisian data');
        }

        DB::beginTransaction();

        try {
            // Ambil data jadwal
            $jadwal = Jadwal::with('shuttle')->findOrFail($request->jadwal_id);

            // Debug jadwal
            \Log::info('Jadwal found:', ['jadwal_id' => $jadwal->id, 'kursi_tersedia' => $jadwal->kursi_tersedia]);

            // Cek ketersediaan kursi
            if ($jadwal->kursi_tersedia < $request->jumlah_penumpang) {
                throw new \Exception('Kursi tidak tersedia. Sisa kursi: ' . $jadwal->kursi_tersedia);
            }

            // Hitung harga total berdasarkan jumlah penumpang
            $hargaPerOrang = $jadwal->harga_total; // Harga per orang dari jadwal
            $hargaTotal = $hargaPerOrang * $request->jumlah_penumpang;

            // Gunakan diskon dari form jika ada
            $diskon = $request->diskon_amount ?? 0;
            $promoId = null;

            // Jika ada kode promo, validasi dan dapatkan promo_id
            if ($request->kode_promo) {
                $promo = Promo::where('kode', strtoupper($request->kode_promo))
                    ->where('status', true)
                    ->whereDate('tanggal_mulai', '<=', now())
                    ->whereDate('tanggal_berakhir', '>=', now())
                    ->first();

                if ($promo) {
                    $promoId = $promo->id;
                    // Jika diskon dari form 0, hitung ulang
                    if ($diskon == 0 && method_exists($promo, 'hitungDiskon')) {
                        $diskon = $promo->hitungDiskon($hargaTotal);
                    }
                }
            }

            // Cek loyalty discount dari session
            $loyaltyDiscount = session()->get('loyalty_discount');
            $diskonLoyalty = 0;

            if ($loyaltyDiscount) {
                $user = auth()->user();
                if ($loyaltyDiscount['user_id'] == ($user->id ?? null)) {
                    $diskonLoyalty = $loyaltyDiscount['discount_amount'];
                    // Gunakan loyalty points
                    if ($user && $user->isMemberActive()) {
                        $user->useLoyaltyPoints($loyaltyDiscount['points_used']);
                    }
                    session()->forget('loyalty_discount');
                }
            }

            // Hitung total bayar
            $totalBayar = $hargaTotal - $diskon - $diskonLoyalty;

            // Gunakan total_after_discount dari form jika ada
            if ($request->total_after_discount && $request->total_after_discount > 0) {
                $totalBayar = $request->total_after_discount;
            }

            // Generate kode booking
            $kodeBooking = $this->generateKodeBooking();

            // Buat pemesanan
            $pemesanan = Pemesanan::create([
                'kode_booking' => $kodeBooking,
                'customer_id' => auth()->id(),
                'jadwal_id' => $jadwal->id,
                'jumlah_penumpang' => $request->jumlah_penumpang,
                'harga_total' => $hargaTotal,
                'diskon' => $diskon + $diskonLoyalty,
                'total_bayar' => $totalBayar,
                'nama_pemesan' => $request->nama_pemesan,
                'telepon_pemesan' => $request->telepon_pemesan,
                'email_pemesan' => $request->email_pemesan,
                'catatan' => $request->catatan,
                'kode_promo' => $request->kode_promo,
                'status' => 'menunggu_pembayaran',
                'waktu_kadaluarsa' => now()->addHours(24),
            ]);

            \Log::info('Pemesanan created:', ['id' => $pemesanan->id, 'kode_booking' => $kodeBooking]);

            // Simpan detail penumpang
            foreach ($request->penumpang as $index => $dataPenumpang) {
                DetailPenumpang::create([
                    'pemesanan_id' => $pemesanan->id,
                    'nama_lengkap' => $dataPenumpang['nama_lengkap'],
                    'nik' => $dataPenumpang['nik'],
                    'jenis_kelamin' => $dataPenumpang['jenis_kelamin'],
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

            // Jika pemesanan berhasil, tambahkan poin jika user adalah member aktif
            $user = auth()->user();
            if ($user && $user->isMemberActive()) {
                // Tambah member points (100 per pembelian)
                $user->addMemberPoints(100);

                // Tambah loyalty points berdasarkan level membership
                $loyaltyPointsToAdd = $user->calculateLoyaltyPointsToAdd();
                $user->addLoyaltyPoints($loyaltyPointsToAdd);
            }

            DB::commit();

            \Log::info('Pemesanan successful, redirecting to kursi', ['pemesanan_id' => $pemesanan->id]);

            // Redirect ke halaman kursi
            return redirect()->route('customer.kursi', ['pemesanan_id' => $pemesanan->id])
                ->with('success', 'Pemesanan berhasil! Silakan pilih kursi untuk penumpang.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Pemesanan failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal melakukan pemesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Method untuk generate kode booking
    private function generateKodeBooking()
    {
        $prefix = 'SS';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));

        return $prefix . $date . $random;
    }

    /**
     * Halaman pemilihan kursi
     */
    public function showPemilihanKursi(Request $request)
    {
        $validated = $request->validate([
            'kode_booking' => 'required|exists:pemesanans,kode_booking'
        ]);

        $pemesanan = Pemesanan::with(['jadwal.shuttle', 'penumpang'])
            ->where('kode_booking', $validated['kode_booking'])
            ->first();

        // Cek status pemesanan
        if ($pemesanan->status_pemesanan !== 'menunggu_konfirmasi') {
            return redirect()->route('customer.beranda')
                ->with('error', 'Pemesanan ini sudah diproses atau dibatalkan.');
        }

        // Ambil denah shuttle
        $shuttle = $pemesanan->jadwal->shuttle;
        $denahKursi = json_decode($shuttle->denah_kursi, true) ?? [];

        // Ambil kursi yang sudah terisi di jadwal ini
        $kursiTerisi = DetailPenumpang::whereHas('pemesanan', function($query) use ($pemesanan) {
            $query->where('jadwal_id', $pemesanan->jadwal_id)
                ->where('status_pemesanan', '!=', 'dibatalkan');
        })->pluck('nomor_kursi')->filter()->toArray();

        // Ambil kursi yang sudah dipilih di pemesanan ini
        $kursiDipilih = $pemesanan->penumpang->pluck('nomor_kursi')->filter()->toArray();

        return view('customer.pemilihan_kursi', [
            'pemesanan' => $pemesanan,
            'shuttle' => $shuttle,
            'denahKursi' => $denahKursi,
            'kursiTerisi' => $kursiTerisi,
            'kursiDipilih' => $kursiDipilih
        ]);
    }

    /**
     * Proses pemilihan kursi
     */
    public function prosesPemilihanKursi(Request $request)
    {
        $validated = $request->validate([
            'kode_booking' => 'required|exists:pemesanans,kode_booking',
            'kursi' => 'required|array',
            'kursi.*' => 'required|string'
        ]);

        $pemesanan = Pemesanan::with(['penumpang', 'jadwal.shuttle'])
            ->where('kode_booking', $validated['kode_booking'])
            ->first();

        // Validasi jumlah kursi sama dengan jumlah penumpang
        if (count($validated['kursi']) !== $pemesanan->jumlah_penumpang) {
            return redirect()->back()
                ->with('error', 'Jumlah kursi yang dipilih harus sama dengan jumlah penumpang.');
        }

        // Validasi kursi unik
        if (count($validated['kursi']) !== count(array_unique($validated['kursi']))) {
            return redirect()->back()
                ->with('error', 'Setiap penumpang harus memiliki kursi yang berbeda.');
        }

        // Ambil denah shuttle untuk validasi kursi
        $shuttle = $pemesanan->jadwal->shuttle;
        $denahKursi = json_decode($shuttle->denah_kursi, true) ?? [];
        $kursiValid = [];

        // Flatten denah kursi untuk validasi
        foreach ($denahKursi as $baris) {
            foreach ($baris['kursi'] as $kursi) {
                if ($kursi['status'] === 'tersedia') {
                    $kursiValid[] = $kursi['nomor'];
                }
            }
        }

        // Validasi kursi yang dipilih
        foreach ($validated['kursi'] as $kursi) {
            if (!in_array($kursi, $kursiValid)) {
                return redirect()->back()
                    ->with('error', 'Kursi ' . $kursi . ' tidak valid atau tidak tersedia.');
            }
        }

        // Cek kursi yang sudah terisi di jadwal ini
        $kursiTerisi = \App\Models\Penumpang::whereHas('pemesanan', function($query) use ($pemesanan) {
            $query->where('jadwal_id', $pemesanan->jadwal_id)
                ->where('id', '!=', $pemesanan->id)
                ->where('status_pemesanan', '!=', 'dibatalkan');
        })->pluck('nomor_kursi')->filter()->toArray();

        // Validasi kursi tidak terisi oleh pemesanan lain
        foreach ($validated['kursi'] as $kursi) {
            if (in_array($kursi, $kursiTerisi)) {
                return redirect()->back()
                    ->with('error', 'Kursi ' . $kursi . ' sudah dipesan oleh penumpang lain.');
            }
        }

        try {
            // Update kursi untuk setiap penumpang
            foreach ($pemesanan->penumpang as $index => $penumpang) {
                if (isset($validated['kursi'][$index])) {
                    $penumpang->nomor_kursi = $validated['kursi'][$index];
                    $penumpang->save();
                }
            }

            // Update status pemesanan
            $pemesanan->status_pemesanan = 'diproses';
            $pemesanan->save();

            // Redirect ke halaman pembayaran
            return redirect()->route('customer.pembayaran', [
                'kode_booking' => $pemesanan->kode_booking
            ])->with('success', 'Pemilihan kursi berhasil!');

        } catch (\Exception $e) {
            \Log::error('CustomerController::prosesPemilihanKursi - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Halaman pembayaran
     */
    public function showPembayaran(Request $request)
    {
        $validated = $request->validate([
            'kode_booking' => 'required|exists:pemesanans,kode_booking'
        ]);

        $pemesanan = Pemesanan::with(['jadwal', 'penumpang'])
            ->where('kode_booking', $validated['kode_booking'])
            ->first();

        // Cek status pemesanan
        if (!in_array($pemesanan->status_pemesanan, ['diproses', 'menunggu_pembayaran'])) {
            return redirect()->route('customer.beranda')
                ->with('error', 'Pemesanan ini sudah diproses atau dibatalkan.');
        }

        // Ambil metode pembayaran yang tersedia
        $metodePembayaran = MetodePembayaran::where('aktif', true)
            ->orderBy('urutan', 'asc')
            ->get();

        return view('customer.pembayaran', [
            'pemesanan' => $pemesanan,
            'metodePembayaran' => $metodePembayaran
        ]);
    }

    /**
     * Proses pembayaran
     */
    public function prosesPembayaran(Request $request)
    {
        $validated = $request->validate([
            'kode_booking' => 'required|exists:pemesanans,kode_booking',
            'metode_pembayaran' => 'required|exists:metode_pembayaran,kode'
        ]);

        try {
            $pemesanan = \App\Models\Pemesanan::find($validated['kode_booking']);

            // Update status pembayaran
            $pemesanan->metode_pembayaran = $validated['metode_pembayaran'];
            $pemesanan->status_pembayaran = 'menunggu_pembayaran';
            $pemesanan->status_pemesanan = 'menunggu_pembayaran';
            $pemesanan->tanggal_pembayaran_terakhir = Carbon::now()->addHours(24); // Batas waktu 24 jam
            $pemesanan->save();

            // Generate kode pembayaran (contoh: menggunakan kombinasi)
            $kodePembayaran = 'PAY' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

            // Simpan transaksi pembayaran
            $transaksi = new \App\Models\Transaksi();
            $transaksi->pemesanan_id = $pemesanan->id;
            $transaksi->kode_transaksi = $kodePembayaran;
            $transaksi->metode_pembayaran = $validated['metode_pembayaran'];
            $transaksi->jumlah = $pemesanan->total_bayar;
            $transaksi->status = 'pending';
            $transaksi->save();

            // Redirect ke halaman konfirmasi pembayaran
            return redirect()->route('customer.konfirmasi-pembayaran', [
                'kode_transaksi' => $kodePembayaran
            ])->with('success', 'Silakan selesaikan pembayaran dalam 24 jam.');

        } catch (\Exception $e) {
            \Log::error('CustomerController::prosesPembayaran - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Halaman konfirmasi pembayaran
     */
    public function showKonfirmasiPembayaran(Request $request)
    {
        $validated = $request->validate([
            'kode_transaksi' => 'required|exists:transaksis,kode_transaksi'
        ]);

        $transaksi = Transaksi::with(['pemesanan.jadwal', 'pemesanan.penumpang'])
            ->where('kode_transaksi', $validated['kode_transaksi'])
            ->first();

        // Ambil detail metode pembayaran
        $metodePembayaran = \App\Models\MetodePembayaran::where('kode', $transaksi->metode_pembayaran)
            ->first();

        return view('customer.konfirmasi_pembayaran', [
            'transaksi' => $transaksi,
            'metodePembayaran' => $metodePembayaran
        ]);
    }

    /**
     * Proses konfirmasi pembayaran (upload bukti)
     */
    public function prosesKonfirmasiPembayaran(Request $request)
    {
        $validated = $request->validate([
            'kode_transaksi' => 'required|exists:transaksis,kode_transaksi',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nama_pengirim' => 'required|string|max:255',
            'tanggal_transfer' => 'required|date',
            'jumlah_transfer' => 'required|numeric'
        ]);

        try {
            $transaksi = Transaksi::find($validated['kode_transaksi']);

            // Upload bukti pembayaran
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = 'bukti_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

                $transaksi->bukti_pembayaran = $path;
            }

            $transaksi->nama_pengirim = $validated['nama_pengirim'];
            $transaksi->tanggal_transfer = $validated['tanggal_transfer'];
            $transaksi->jumlah_transfer = $validated['jumlah_transfer'];
            $transaksi->status = 'menunggu_verifikasi';
            $transaksi->save();

            // Update status pemesanan
            $pemesanan = $transaksi->pemesanan;
            $pemesanan->status_pembayaran = 'menunggu_verifikasi';
            $pemesanan->save();

            return redirect()->route('customer.riwayat')
                ->with('success', 'Bukti pembayaran berhasil diupload. Silakan tunggu verifikasi admin.');

        } catch (\Exception $e) {
            \Log::error('CustomerController::prosesKonfirmasiPembayaran - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Halaman riwayat pemesanan
     */
    public function showRiwayat(Request $request)
    {
        // Cek jika user sudah login
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = session()->get('user');

        // Ambil riwayat pemesanan user dengan relasi yang diperlukan
        $riwayat = Pemesanan::with([
            'jadwal.shuttle',
            'jadwal.rutes',  // Pastikan relasi rutes ada
            'detailPenumpang'
        ])
        ->where('customer_id', $user['id'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('customer.riwayat', [
            'riwayat' => $riwayat,
            'user' => $user
        ]);
    }

    /**
     * Halaman detail pemesanan
     */
    public function showDetailPemesanan(Request $request, $kode_booking)
    {
        // Cek jika user sudah login
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = session()->get('user');

        // Ambil data pemesanan
        $pemesanan = \App\Models\Pemesanan::with([
            'jadwal.shuttle',
            'penumpang',
            'transaksi'
        ])->where('kode_booking', $kode_booking)
        ->where('customer_id', $user['id'])
        ->first();

        if (!$pemesanan) {
            return redirect()->route('customer.riwayat')
                ->with('error', 'Pemesanan tidak ditemukan.');
        }

        return view('customer.detail_pemesanan', [
            'pemesanan' => $pemesanan,
            'user' => $user
        ]);
    }

    /**
     * Batalkan pemesanan
     */
    public function batalkanPemesanan(Request $request, $kode_booking)
    {
        // Cek jika user sudah login
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = session()->get('user');

        try {
            $pemesanan = Pemesanan::where('kode_booking', $kode_booking)
                ->where('customer_id', $user['id'])
                ->first();

            if (!$pemesanan) {
                return redirect()->route('customer.riwayat')
                    ->with('error', 'Pemesanan tidak ditemukan.');
            }

            // Hanya bisa dibatalkan jika status masih menunggu pembayaran
            if (!in_array($pemesanan->status_pemesanan, ['menunggu_konfirmasi', 'menunggu_pembayaran'])) {
                return redirect()->back()
                    ->with('error', 'Pemesanan tidak dapat dibatalkan karena sudah diproses.');
            }

            // Kembalikan kursi tersedia
            $jadwal = $pemesanan->jadwal;
            $jadwal->kursi_tersedia += $pemesanan->jumlah_penumpang;

            // Jika status sebelumnya tidak tersedia, ubah jadi tersedia
            if ($jadwal->status === 'tidak_tersedia') {
                $jadwal->status = 'tersedia';
            }

            $jadwal->save();

            // Update status pemesanan
            $pemesanan->status_pemesanan = 'dibatalkan';
            $pemesanan->status_pembayaran = 'dibatalkan';
            $pemesanan->save();

            // Update transaksi jika ada
            if ($pemesanan->transaksi) {
                $pemesanan->transaksi->status = 'dibatalkan';
                $pemesanan->transaksi->save();
            }

            return redirect()->route('customer.riwayat')
                ->with('success', 'Pemesanan berhasil dibatalkan.');

        } catch (\Exception $e) {
            \Log::error('CustomerController::batalkanPemesanan - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Halaman profil customer
     */
    public function profil()
    {
        // Cek jika user belum login
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = session()->get('user');

        // Ambil data lengkap user dari database
        $userData = \App\Models\User::find($user['id']);

        // Jika user tidak ditemukan, redirect ke login
        if (!$userData) {
            return redirect()->route('customer.login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }

        // Ambil data riwayat pemesanan terbaru (opsional untuk dashboard)
        $riwayatTerbaru = \App\Models\Pemesanan::where('customer_id', $user['id'])
            ->with(['jadwal', 'outletAsal', 'outletTujuan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('customer.dashboardprofile', [
            'user' => $userData,
            'riwayatTerbaru' => $riwayatTerbaru
        ]);
    }

    /**
     * Halaman membership dengan status
     */
    public function membership()
    {
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = session()->get('user');
        $userData = User::find($user['id']);

        if (!$userData) {
            return redirect()->route('customer.login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }

        // Jika user belum menjadi member, tampilkan halaman untuk mendaftar membership
        if ($userData->membership_status === 'non_member') {
            return view('customer.membership_non_member', [
                'user' => $userData
            ]);
        }

        // Jika status pending (menunggu pembayaran)
        if ($userData->membership_status === 'pending') {
            // Cari payment yang pending
            $pendingPayment = MembershipPayment::where('user_id', $userData->id)
                ->where('payment_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->first();

            return view('customer.membership_pending', [
                'user' => $userData,
                'pendingPayment' => $pendingPayment
            ]);
        }

        // Jika membership aktif, hitung semua data untuk ditampilkan
        $membershipLevel = $userData->membership_level ?? 'Bronze';
        $currentPoints = $userData->member_point ?? 0;
        $loyaltyPoints = $userData->loyalty_point ?? 0;

        // Tentukan range point untuk setiap level
        $levelRanges = [
            'Bronze' => ['min' => 0, 'max' => 1000],
            'Silver' => ['min' => 1000, 'max' => 2500],
            'Gold' => ['min' => 2500, 'max' => 4500],
            'Platinum' => ['min' => 4500, 'max' => 6000],
        ];

        // Tentukan level berikutnya
        $levels = ['Bronze', 'Silver', 'Gold', 'Platinum'];
        $currentIndex = array_search($membershipLevel, $levels);
        $nextLevel = $currentIndex < count($levels) - 1 ? $levels[$currentIndex + 1] : 'Platinum';

        // Hitung progress
        $currentMin = $levelRanges[$membershipLevel]['min'];
        $currentMax = $levelRanges[$membershipLevel]['max'];

        if ($currentPoints >= $currentMax) {
            $progressPercentage = 100;
        } elseif ($currentPoints <= $currentMin) {
            $progressPercentage = 0;
        } else {
            $progressPercentage = (($currentPoints - $currentMin) / ($currentMax - $currentMin)) * 100;
        }

        // Hitung points yang dibutuhkan untuk level berikutnya
        $pointsNeeded = 0;
        if ($membershipLevel !== 'Platinum') {
            $nextMin = $levelRanges[$nextLevel]['min'];
            $pointsNeeded = $nextMin - $currentPoints;
            if ($pointsNeeded < 0) $pointsNeeded = 0;
        }

        // Hitung sisa waktu membership
        $daysRemaining = 0;
        if ($userData->membership_end_date) {
            $daysRemaining = Carbon::parse($userData->membership_end_date)->diffInDays(Carbon::now());
        }

        // Create membership object for view
        $membership = (object) [
            'level' => $membershipLevel,
            'points' => $currentPoints,
            'loyalty_points' => $loyaltyPoints,
        ];

        return view('customer.membership', [
            'user' => $userData,
            'membership' => $membership,
            'currentPoints' => $currentPoints,
            'loyaltyPoints' => $loyaltyPoints,
            'membershipLevel' => $membershipLevel,
            'nextLevel' => $nextLevel,
            'currentMin' => $currentMin,
            'currentMax' => $currentMax,
            'progressPercentage' => $progressPercentage,
            'pointsNeeded' => $pointsNeeded,
            'daysRemaining' => $daysRemaining,
            'membershipStartDate' => $userData->membership_start_date,
            'membershipEndDate' => $userData->membership_end_date,
        ]);
    }

    /**
     * Halaman form pendaftaran membership
     */
    public function showMembershipForm()
    {
        $user = Auth::user();

        // Cek apakah user sudah punya membership
        if ($user->membership_status === 'active') {
            return redirect()->route('customer.membership');
        } elseif ($user->membership_status === 'pending') {
            return redirect()->route('customer.membership');
        }

        return view('customer.membership_form');
    }

    /**
     * Proses pendaftaran membership
     */
    public function processMembershipRegistration(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:12',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:L,P',
        ]);

        $user = Auth::user();

        // Cek apakah sudah ada membership aktif atau pending
        if (in_array($user->membership_status, ['active', 'pending'])) {
            return redirect()->route('customer.membership');
        }

        DB::beginTransaction();

        try {
            // Update user data
            $user->update([
                'phone' => $request->phone,
                'tanggal_lahir' => $request->birthdate,
                'jenis_kelamin' => $request->gender,
                'membership_status' => 'pending',
                'membership_level' => 'Bronze',
                'member_point' => 0,
                'loyalty_point' => 0,
                'membership_fee' => 20000,
                'membership_transaction_id' => 'MEM' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6)),
            ]);

            // Buat payment record menggunakan MembershipPayment model
            MembershipPayment::create([
                'user_id' => $user->id,
                'transaction_id' => $user->membership_transaction_id,
                'amount' => 20000,
                'discount' => 0,
                'total_amount' => 20000,
                'payment_status' => 'pending',
                'waktu_kadaluarsa' => Carbon::now()->addHours(24),
            ]);

            DB::commit();

            return redirect()->route('customer.membership.payment');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat mendaftar membership: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan halaman pembayaran membership
     */
    public function showMembershipPayment()
    {
        $user = Auth::user();

        // Check if user has pending membership
        if ($user->membership_status !== 'pending') {
            return redirect()->route('customer.membership');
        }

        // Find pending payment for this user
        $payment = MembershipPayment::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->first();

        if (!$payment) {
            return redirect()->route('customer.membership');
        }

        return view('customer.membership_payment', [
            'user' => $user,
            'payment' => $payment,
        ]);
    }

    /**
     * Proses pembayaran membership
     */
    public function processMembershipPayment(Request $request)
    {
        $user = Auth::user();

        // Cek apakah user memiliki membership pending
        if ($user->membership_status !== 'pending') {
            return redirect()->route('customer.membership');
        }

        // Cari payment yang pending
        $payment = MembershipPayment::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->first();

        if (!$payment) {
            return redirect()->route('customer.membership');
        }

        DB::beginTransaction();

        try {
            // Update status membership menjadi active
            $user->update([
                'membership_status' => 'active',
                'membership_start_date' => now(),
                'membership_end_date' => now()->addMonths(12),
                'membership_payment_status' => 'paid',
            ]);

            // Update status payment
            $payment->update([
                'payment_status' => 'success',
                'paid_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('customer.membership')->with('success', 'Membership berhasil diaktifkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan halaman pending pembayaran
     */
    public function showMembershipPending()
    {
        $user = Auth::user();

        // Check if user has pending membership
        if ($user->membership_status !== 'pending') {
            return redirect()->route('customer.membership');
        }

        // Find pending payment for this user
        $payment = MembershipPayment::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->first();

        if (!$payment) {
            return redirect()->route('customer.membership');
        }

        return view('customer.membership_pending', [
            'user' => $user,
            'payment' => $payment,
        ]);
    }

    /**
     * Simulasi pembayaran (untuk testing)
     */
    public function simulateMembershipPayment(Request $request)
    {
        $user = Auth::user();

        // Cek apakah user memiliki membership pending
        if ($user->membership_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Membership tidak ditemukan'
            ]);
        }

        // Cari payment yang pending
        $payment = MembershipPayment::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment tidak ditemukan'
            ]);
        }

        DB::beginTransaction();

        try {
            // Update status membership menjadi active
            $user->update([
                'membership_status' => 'active',
                'membership_start_date' => now(),
                'membership_end_date' => now()->addMonths(12),
                'membership_payment_status' => 'paid',
            ]);

            // Update status payment
            $payment->update([
                'payment_status' => 'success',
                'paid_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disimulasikan',
                'redirect_url' => route('customer.membership')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Batalkan pembayaran membership
     */
    public function cancelMembershipPayment(Request $request)
    {
        $user = Auth::user();

        // Check if user has pending membership
        if ($user->membership_status !== 'pending') {
            return redirect()->route('customer.membership')->with('info', 'Tidak ada pembayaran pending yang dapat dibatalkan.');
        }

        // Find and delete pending payment
        $payment = MembershipPayment::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->first();

        if ($payment) {
            $payment->delete();
        }

        // Reset user membership status to non_member
        $user->update([
            'membership_status' => 'non_member',
            'membership_transaction_id' => null,
        ]);

        return redirect()->route('customer.membership')->with('info', 'Pembayaran telah dibatalkan.');
    }

    /**
     * Tampilkan halaman membership aktif (redirect ke halaman utama membership)
     */
    public function membershipActive()
    {
        return redirect()->route('customer.membership');
    }
    /**
     * Gunakan loyalty points untuk diskon
     */
    public function useLoyaltyPoints(Request $request)
    {
        if (!session()->has('user')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = session()->get('user');
        $userData = User::find($user['id']);

        if (!$userData) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Cek apakah user adalah member aktif
        if (!$userData->isMemberActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus menjadi member aktif untuk menggunakan loyalty points.'
            ]);
        }

        // Cek apakah memiliki cukup loyalty points
        if ($userData->loyalty_point < 50) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum 50 loyalty points diperlukan untuk mendapatkan diskon.'
            ]);
        }

        $totalAmount = $request->input('total_amount', 0);

        if ($totalAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Total amount tidak valid.'
            ]);
        }

        // Hitung diskon berdasarkan loyalty points
        $discount = $userData->calculateDiscountFromLoyaltyPoints($totalAmount);

        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghitung diskon.'
            ]);
        }

        // Tentukan poin yang digunakan
        $pointsUsed = 0;
        if ($userData->loyalty_point >= 150) {
            $pointsUsed = 150;
        } elseif ($userData->loyalty_point >= 100) {
            $pointsUsed = 100;
        } else {
            $pointsUsed = 50;
        }

        // Simpan diskon ke session
        session()->put('loyalty_discount', [
            'user_id' => $userData->id,
            'discount_amount' => $discount,
            'points_used' => $pointsUsed,
            'total_before_discount' => $totalAmount,
            'total_after_discount' => $totalAmount - $discount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loyalty points berhasil digunakan untuk diskon!',
            'discount' => $discount,
            'total_after_discount' => $totalAmount - $discount,
            'points_used' => $pointsUsed,
            'remaining_points' => $userData->loyalty_point - $pointsUsed
        ]);
    }

    /**
     * Hapus loyalty discount dari session
     */
    public function removeLoyaltyDiscount(Request $request)
    {
        session()->forget('loyalty_discount');

        return response()->json([
            'success' => true,
            'message' => 'Loyalty discount berhasil dihapus.'
        ]);
    }

    public function profilDetail()
    {
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = session()->get('user');
        $userData = \App\Models\User::find($user['id']);

        if (!$userData) {
            return redirect()->route('customer.login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }

        return view('customer.profilcust', [
            'user' => $userData
        ]);
    }

    public function updateProfile(Request $request)
    {
        // Cek jika user belum login
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = session()->get('user');
        $userData = \App\Models\User::find($user['id']);

        if (!$userData) {
            return redirect()->route('customer.login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username,' . $userData->id,
            'email' => 'required|email|unique:users,email,' . $userData->id,
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Update data user
            $userData->name = $validated['name'];
            $userData->username = $validated['username'] ?? $userData->username;
            $userData->email = $validated['email'];
            $userData->phone = $validated['phone'] ?? $userData->phone;
            $userData->nik = $validated['nik'] ?? $userData->nik;
            $userData->tanggal_lahir = $validated['tanggal_lahir'] ?? $userData->tanggal_lahir;
            $userData->jenis_kelamin = $validated['jenis_kelamin'] ?? $userData->jenis_kelamin;

            // Update password jika diisi
            if ($request->filled('password')) {
                $userData->password = bcrypt($validated['password']);
            }

            // Upload avatar jika ada
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $userData->avatar = $avatarPath;
            }

            $userData->save();

            // Update session
            session()->put('user', [
                'id' => $userData->id,
                'name' => $userData->name,
                'email' => $userData->email,
            ]);

            return redirect()->route('customer.profilcust')->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui profil: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function updatePoints(Request $request)
    {
        if (!session()->has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = session()->get('user');
        $userData = \App\Models\User::find($user['id']);

        if (!$userData) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'points' => 'required|integer|min:0',
            'loyalty_points' => 'required|integer|min:0',
            'membership_level' => 'required|in:Bronze,Silver,Gold,Platinum'
        ]);

        try {
            $userData->member_point = $validated['points'];
            $userData->loyalty_point = $validated['loyalty_points'];
            $userData->membership_level = $validated['membership_level'];
            $userData->save();

            return response()->json([
                'success' => true,
                'message' => 'Points updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update points: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman bantuan/FAQ
     */
    public function bantuan()
    {
        $user = session()->get('user', []);

        // Ambil FAQ dari database
        $faqs = Faq::where('status', 'aktif')
            ->orderBy('urutan', 'asc')
            ->get();

        // Ambil kontak support
        $kontakSupport = \App\Models\MProfilePerusahaan::select('telepon', 'email', 'alamat')
            ->where('status', 'active')
            ->first();

        return view('customer.bantuan', compact('user', 'faqs', 'kontakSupport'));
    }

    /**
     * Halaman syarat dan ketentuan
     */
    public function syaratKetentuan()
    {
        $user = session()->get('user', []);

        // Ambil syarat dan ketentuan
        $syaratKetentuan = SyaratKetentuan::getUntukPengguna();

        return view('customer.syarat_ketentuan', compact('user', 'syaratKetentuan'));
    }

    /**
     * Halaman kebijakan privasi
     */
    public function kebijakanPrivasi()
    {
        $user = session()->get('user', []);

        // Ambil kebijakan privasi
        $kebijakanPrivasi = KebijakanPrivasi::getAktif();

        return view('customer.kebijakan_privasi', compact('user', 'kebijakanPrivasi'));
    }

    /**
     * Halaman kontak dengan master data
     */
    public function contact()
    {
        // Cek jika user sudah login dari session
        $user = session()->get('user');

        // Ambil data master kontak
        $masterKontak = MMasterKontak::where('status', 'active')->first();

        // Jika tidak ada data master kontak, gunakan data dari profil perusahaan
        if (!$masterKontak) {
            $profile = MProfilePerusahaan::where('status', 'active')->first();

            if ($profile) {
                $masterKontak = (object) [
                    'nama_perusahaan' => $profile->nama_dagang ?? 'Smart Shuttle',
                    'deskripsi_singkat' => $profile->deskripsi_singkat ?? 'Menghubungkan kota, menyatukan perjalanan',
                    'email_utama' => $profile->email ?? 'mdcitrasolusi@gmail.com',
                    'email_dukungan' => $profile->email ?? 'mdcitrasolusi@gmail.com',
                    'telepon_utama' => $profile->telepon ?? '0858-1122-4321',
                    'telepon_dukungan' => $profile->telepon ?? '0858-1122-4321',
                    'alamat_kantor_pusat' => $profile->alamat_kantor_pusat ?? 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur',
                    'facebook_url' => $profile->facebook_url ?? '#',
                    'instagram_url' => $profile->instagram_url ?? 'https://citrasolusi.id',
                    'twitter_url' => $profile->twitter_url ?? '#',
                    'jam_operasional' => json_encode([
                        ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                        ['hari' => 'Sabtu', 'jam' => '08:00 - 15:00'],
                        ['hari' => 'Minggu', 'jam' => 'Tutup']
                    ]),
                    'link_kebijakan_privasi' => $profile->link_kebijakan_privasi ?? '#',
                    'link_syarat_ketentuan' => $profile->link_syarat_ketentuan ?? '#',
                ];
            } else {
                // Data fallback jika tidak ada sama sekali
                $masterKontak = (object) [
                    'nama_perusahaan' => 'Smart Shuttle',
                    'deskripsi_singkat' => 'Menghubungkan kota, menyatukan perjalanan',
                    'email_utama' => 'mdcitrasolusi@gmail.com',
                    'email_dukungan' => 'mdcitrasolusi@gmail.com',
                    'telepon_utama' => '0858-1122-4321',
                    'telepon_dukungan' => '0858-1122-4321',
                    'alamat_kantor_pusat' => 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur',
                    'facebook_url' => '#',
                    'instagram_url' => 'https://citrasolusi.id',
                    'twitter_url' => '#',
                    'jam_operasional' => json_encode([
                        ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                        ['hari' => 'Sabtu', 'jam' => '08:00 - 15:00'],
                        ['hari' => 'Minggu', 'jam' => 'Tutup']
                    ]),
                    'link_kebijakan_privasi' => '#',
                    'link_syarat_ketentuan' => '#',
                ];
            }
        }

        // Parse jam operasional jika dalam format JSON
        if (isset($masterKontak->jam_operasional) && is_string($masterKontak->jam_operasional)) {
            $masterKontak->jam_operasional = json_decode($masterKontak->jam_operasional, true);
        }

        return view('customer.kontak', compact('user', 'masterKontak'));
    }

    /**
     * Proses pengiriman pesan dari form kontak
     */
    public function submitContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'telepon' => 'nullable|string|max:20',
            'pesan' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Mohon periksa kembali data yang Anda masukkan.');
        }

        try {
            // Simpan pesan ke database
            PesanKontak::create([
                'nama_pengirim' => $request->nama,
                'email_pengirim' => $request->email,
                'nomor_telepon' => $request->telepon,
                'pesan' => $request->pesan,
                'status' => 'terkirim',
            ]);

            // Log untuk debugging
            Log::info('Pesan kontak berhasil dikirim', [
                'nama' => $request->nama,
                'email' => $request->email,
            ]);

            return redirect()->back()
                ->with('success', 'Pesan Anda telah berhasil dikirim! Kami akan menghubungi Anda dalam waktu 1x24 jam.');

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan pesan kontak: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.')
                ->withInput();
        }
    }

    /**
     * Halaman syarat dan ketentuan membership khusus
     */
    public function syaratKetentuanMembership()
    {
        $user = session()->get('user', []);

        // Ambil syarat dan ketentuan khusus membership
        $syaratKetentuan = SyaratKetentuan::where('jenis', 'membership')->first();

        if (!$syaratKetentuan) {
            // Fallback ke syarat ketentuan umum
            $syaratKetentuan = SyaratKetentuan::getUntukPengguna();
        }

        return view('customer.syarat_ketentuan_membership', compact('user', 'syaratKetentuan'));
    }

    /**
     * Perpanjang membership
     */
    public function renewMembership()
    {
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = session()->get('user');
        $userData = User::find($user['id']);

        if (!$userData) {
            return redirect()->route('customer.login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }

        // Hanya bisa renew jika membership aktif atau expired
        if (!in_array($userData->membership_status, ['active', 'expired'])) {
            return redirect()->route('customer.membership')
                ->with('error', 'Anda tidak dapat memperpanjang membership saat ini.');
        }

        // Buat payment baru untuk renewal
        DB::beginTransaction();

        try {
            $amount = 100000; // Biaya renewal Rp 100.000
            $transactionId = MembershipPayment::generateTransactionId();

            $payment = MembershipPayment::create([
                'user_id' => $userData->id,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'discount' => 0,
                'total_amount' => $amount,
                'payment_status' => 'pending',
                'waktu_kadaluarsa' => now()->addHours(24),
            ]);

            // Update user status menjadi pending (untuk pembayaran renewal)
            $userData->update([
                'membership_status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('customer.membership.payment')
                ->with('success', 'Silakan lanjutkan pembayaran untuk memperpanjang membership.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating membership renewal payment: ' . $e->getMessage());

            return redirect()->route('customer.membership')
                ->with('error', 'Terjadi kesalahan saat membuat pembayaran renewal.');
        }
    }
}
