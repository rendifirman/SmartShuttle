<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DriverSchedule; // Tambahkan ini
use App\Models\DriverJadwal;
use App\Models\DriverLocation;
use App\Models\DriverJourneyState;
use App\Models\Pemesanan;
use App\Models\Rute;
use App\Models\Outlet;
use App\Models\Branch;
use App\Models\KursiTerpesan;

class DriverController extends Controller
{
    /**
     * Show driver login form
     */
    public function showLogin()
    {
        return view('driver.login');
    }

    /**
     * Handle driver login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        \Log::info('Driver login attempt', ['email' => $request->email]);

        if (Auth::guard('driver')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Check if user has driver role
            $user = Auth::guard('driver')->user();

            \Log::info('Driver login - user found', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'status' => $user->status,
            ]);

            // Check user status
            if ($user->status !== 'active') {
                Auth::guard('driver')->logout();
                \Log::warning('Driver login failed - inactive account', ['email' => $request->email]);
                return back()->withErrors(['email' => 'Akun Anda tidak aktif.']);
            }

            // Check if user has driver role
            if (!$user->hasRole('driver')) {
                Auth::guard('driver')->logout();
                \Log::warning('Driver login failed - no driver role', [
                    'email' => $request->email,
                    'roles' => $user->getRoleNames()->toArray()
                ]);
                return back()->withErrors(['email' => 'Anda tidak memiliki akses driver.']);
            }

            \Log::info('Driver login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
            ]);

            return redirect()->intended(route('driver.dashboard'));
        }

        \Log::warning('Driver login failed - invalid credentials', ['email' => $request->email]);

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Show driver dashboard
     */
    public function dashboard()
    {
        $driver = Auth::guard('driver')->user();

        // Initialize default values
        $schedules = collect();
        $jumlahJadwalBulanIni = 0;
        $jadwalAktif = 0;
        $jadwalSelesai = 0;
        $totalJadwal = 0;

        if ($driver) {
            // Get today's date
            $today = \Carbon\Carbon::today();

            // Fetch all schedules with relationships
            $allSchedules = DriverJadwal::with(['jadwal', 'masterRute'])
                ->where('id_driver', $driver->id)
                ->get();

            \Log::info('Dashboard - Driver logged in', ['driver_id' => $driver->id, 'total_schedules' => $allSchedules->count()]);
            \Log::info('Today date:', ['today' => $today->format('Y-m-d'), 'today_timestamp' => $today]);

            // Debug: Log setiap jadwal
            foreach ($allSchedules as $idx => $sched) {
                \Log::info('Schedule #' . ($idx + 1), [
                    'id' => $sched->id_jadwal_driver,
                    'tanggal' => $sched->tanggal,
                    'tanggal_type' => gettype($sched->tanggal),
                    'rute' => $sched->rute,
                    'waktu_keberangkatan' => $sched->waktu_keberangkatan
                ]);
            }

            // Filter for today - tanpa parsing, langsung compare
            $schedules = $allSchedules->filter(function($schedule) use ($today) {
                if (is_null($schedule->tanggal)) {
                    \Log::debug('Skipping schedule - tanggal is null');
                    return false;
                }

                // Cast to string untuk comparison
                $scheduleDateStr = (string) $schedule->tanggal;
                $todayDateStr = $today->format('Y-m-d');

                \Log::debug('Comparing dates', [
                    'schedule_date' => $scheduleDateStr,
                    'today_date' => $todayDateStr,
                    'match' => $scheduleDateStr === $todayDateStr
                ]);

                return $scheduleDateStr === $todayDateStr;
            })->values();

            \Log::info('Dashboard - Filter result', ['filtered_count' => $schedules->count()]);

            // Calculate statistics
            $jumlahJadwalBulanIni = $allSchedules->filter(function($schedule) use ($today) {
                if (is_null($schedule->tanggal)) return false;
                try {
                    $scheduleDate = \Carbon\Carbon::parse($schedule->tanggal);
                    return $scheduleDate->between($today->copy()->startOfMonth(), $today->copy()->endOfMonth());
                } catch (\Exception $e) {
                    return false;
                }
            })->count();

            // Count schedules that are either 'aktif' or 'dalam_perjalanan' as active
            $jadwalAktif = $allSchedules->whereIn('status', ['aktif', 'dalam_perjalanan'])->count();
            $jadwalSelesai = $allSchedules->where('status', 'selesai')->count();
            $totalJadwal = $allSchedules->count();
        } else {
            \Log::warning('Dashboard - No driver logged in');
        }

        return view('driver.dashboard', compact('driver', 'schedules', 'jumlahJadwalBulanIni', 'jadwalAktif', 'jadwalSelesai', 'totalJadwal'));
    }

    /**
     * Handle driver logout
     */
    public function logout(Request $request)
    {
        Auth::guard('driver')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }

    /**
     * Show driver schedule - PERBAIKAN: Tambahkan data schedules
     */
    public function jadwal()
    {
        $driver = Auth::guard('driver')->user();

        // Ambil semua jadwal driver yang login
        $schedules = DriverSchedule::with('rute.bus')
            ->where('driver_id', $driver->id)
            ->orderBy('tanggal_berangkat', 'desc')
            ->orderBy('jam_berangkat', 'desc')
            ->get();

        return view('driver.jadwal', compact('schedules', 'driver'));
    }

    /**
     * Show driver reports
     */
    public function laporan(Request $request)
    {
        $driver = Auth::guard('driver')->user();

        if (!$driver) {
            return redirect()->route('driver.login');
        }

        // Get filter parameters
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Query all schedules for this driver with filters
        $query = DriverJadwal::with(['jadwal', 'masterRute', 'jadwal.rutes'])
            ->where('id_driver', $driver->id);

        // Filter by month and year if provided
        if ($bulan && $tahun) {
            $query->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
        }

        // Get all schedules sorted by date descending
        $allSchedules = $query->orderBy('tanggal', 'desc')
            ->orderBy('waktu_keberangkatan', 'desc')
            ->get();

        // Process data for each schedule
        $laporanData = [];
        foreach ($allSchedules as $schedule) {
            // Get route info (from -> to)
            $from = null;
            $to = null;

            // Try from masterRute first
            if ($schedule->masterRute) {
                $from = $schedule->masterRute->kota_asal;
                $to = $schedule->masterRute->kota_tujuan;
            }

            // Fallback from jadwal->rutes
            if (empty($from) && $schedule->jadwal && $schedule->jadwal->rutes->isNotEmpty()) {
                $firstRute = $schedule->jadwal->rutes->first();
                $from = $firstRute->kota_asal;
                $to = $firstRute->kota_tujuan;
            }

            // Fallback from string route
            if (empty($from)) {
                $ruteString = $schedule->rute ?? '';
                if (preg_match('/\(([^→]+)→([^)]+)\)/', $ruteString, $matches)) {
                    $from = trim($matches[1]);
                    $to = trim($matches[2]);
                } else {
                    $from = $ruteString ?: 'N/A';
                    $to = 'N/A';
                }
            }

            // Get passenger count from Pemesanan
            $penumpangCount = Pemesanan::where('id_jadwal_driver', $schedule->id_jadwal_driver)
                ->whereIn('status', ['dibayar', 'diproses', 'selesai'])
                ->with('detailPenumpang')
                ->get()
                ->sum(function($booking) {
                    return $booking->detailPenumpang->count();
                });

            // Get paket count (packages - this could be from additional services or separate table)
            // For now, we'll calculate from Pemesanan - assuming paket is separate from passengers
            // If there's no specific column for paket, we'll set it to 0 for now
            $paketCount = 0;

            // Determine category type
            $kategori = 'perjalanan'; // Default is perjalanan (passenger)
            if ($penumpangCount == 0 && $schedule->status == 'selesai') {
                $kategori = 'armada'; // Empty passenger might be armada movement
            }

            // Format date
            $tanggal = $schedule->tanggal ? $schedule->tanggal->format('d-m-Y') : 'N/A';

            // Status badge
            $statusBadge = '';
            switch ($schedule->status) {
                case 'selesai':
                    $statusBadge = 'Selesai';
                    break;
                case 'aktif':
                case 'dalam_perjalanan':
                    $statusBadge = 'Dalam Proses';
                    break;
                case 'dibatalkan':
                    $statusBadge = 'Dibatalkan';
                    break;
                default:
                    $statusBadge = ucfirst($schedule->status ?? 'N/A');
            }

            $laporanData[] = [
                'id_jadwal_driver' => $schedule->id_jadwal_driver,
                'tanggal' => $tanggal,
                'rute' => ($from && $to) ? "$from - $to" : ($schedule->rute ?? 'N/A'),
                'from' => $from,
                'to' => $to,
                'penumpang' => $penumpangCount,
                'paket' => $paketCount,
                'armada' => $schedule->armada ?? 'Bus A',
                'status' => $statusBadge,
                'status_raw' => $schedule->status,
                'kategori' => $kategori,
                'waktu_keberangkatan' => $schedule->waktu_keberangkatan ?? 'N/A',
            ];
        }

        // Get available months for filter (last 12 months)
        $availableMonths = [];
        for ($i = 0; $i < 12; $i++) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $availableMonths[] = [
                'bulan' => $date->format('m'),
                'tahun' => $date->format('Y'),
                'label' => $date->translatedFormat('F Y')
            ];
        }

        // Statistics
        $totalPerjalanan = collect($laporanData)->where('kategori', 'perjalanan')->count();
        $totalPenumpang = collect($laporanData)->sum('penumpang');
        $totalPaket = collect($laporanData)->sum('paket');
        $totalSelesai = collect($laporanData)->where('status_raw', 'selesai')->count();

        return view('driver.laporan', compact(
            'laporanData',
            'availableMonths',
            'bulan',
            'tahun',
            'totalPerjalanan',
            'totalPenumpang',
            'totalPaket',
            'totalSelesai'
        ));
    }

    /**
     * Show driver trips
     */
    public function perjalanan()
    {
        $driver = Auth::guard('driver')->user();

        if (!$driver) {
            return redirect()->route('driver.login');
        }

        // Ambil data jadwal driver - TANPA FILTER KETAT agar semua jadwal bisa dilihat
        // Query DriverJadwal dengan relationship yang diperlukan
        $query = DriverJadwal::with(['jadwal', 'masterRute', 'jadwal.rutes'])
            ->where('id_driver', $driver->id)
            // ★★★ PERBAIKAN: Tampilkan semua jadwal tanpa filter tanggal/status
            // Ini memastikan driver bisa lihat semua jadwal yang di-assign
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu_keberangkatan', 'asc');

        // ★★★ CATATAN: Filter dilakukan SETELAH data di-load untuk fleksibilitas
        // Jangan filter di sini agar semua jadwal bisa dilihat driver

        $trips = $query->get();

        // Ambil history perjalanan (selesai) - SEMUA riwayat tanpa batas
        $completedTrips = DriverJadwal::with(['jadwal', 'masterRute', 'jadwal.rutes'])
            ->where('id_driver', $driver->id)
            ->where('status', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_keberangkatan', 'desc')
            ->get(); // ★★★ Tanpa limit() agar semua riwayat bisa dilihat

        // Map completed trips into a simplified array so view JS can reliably access
        // route name, from and to fields (previously these were sometimes missing)
        $completedTrips = $completedTrips->map(function($trip) {
            $from = null;
            $to = null;
            $routeName = null;

            if ($trip->masterRute) {
                $from = $trip->masterRute->kota_asal;
                $to = $trip->masterRute->kota_tujuan;
                $routeName = $trip->masterRute->nama_rute ?? null;
            }

            if (empty($from) && $trip->jadwal && $trip->jadwal->rutes->isNotEmpty()) {
                $firstRute = $trip->jadwal->rutes->first();
                $from = $firstRute->kota_asal;
                $to = $firstRute->kota_tujuan;
                $routeName = $routeName ?? ($firstRute->nama_rute ?? null);
            }

            if (empty($from)) {
                $ruteString = $trip->rute ?? '';
                if (preg_match('/^(.+?)\s*\(([^→]+)→([^)]+)\)/', $ruteString, $matches)) {
                    $routeName = trim($matches[1]);
                    $from = trim($matches[2]);
                    $to = trim($matches[3]);
                } elseif (preg_match('/\(([^→]+)→([^)]+)\)/', $ruteString, $matches)) {
                    $from = trim($matches[1]);
                    $to = trim($matches[2]);
                } else {
                    $from = $ruteString ?: 'N/A';
                    $to = 'N/A';
                }
            }

            if (empty($from)) {
                $from = $trip->kota_asal ?? $trip->asal ?? 'N/A';
                $to = $trip->kota_tujuan ?? $trip->tujuan ?? 'N/A';
            }

            return [
                'id_jadwal_driver' => $trip->id_jadwal_driver,
                'from' => $from,
                'to' => $to,
                'route_name' => $routeName,
                'tanggal' => $trip->tanggal ? $trip->tanggal->format('Y-m-d') : null,
                'waktu_keberangkatan' => $trip->waktu_keberangkatan ?? null,
                'status' => $trip->status ?? null,
                'occupied_seats' => $trip->kursi_terisi ?? 0,
                // include minimal nested relations for backward compatibility
                'masterRute' => $trip->masterRute ? [
                    'kota_asal' => $trip->masterRute->kota_asal ?? null,
                    'kota_tujuan' => $trip->masterRute->kota_tujuan ?? null,
                    'nama_rute' => $trip->masterRute->nama_rute ?? null,
                ] : null,
                'jadwal' => $trip->jadwal ? [
                    'rutes' => $trip->jadwal->rutes->map(function($r){
                        return [
                            'kota_asal' => $r->kota_asal ?? null,
                            'kota_tujuan' => $r->kota_tujuan ?? null,
                            'nama_rute' => $r->nama_rute ?? null,
                        ];
                    })->toArray(),
                ] : null,
            ];
        })->toArray();

        // Ambil data penumpang untuk setiap trip
        // PERBAIKAN: Kolom yang benar adalah 'STATUS' (bukan status_pembayaran)
        // Nilai yang valid: menunggu_pembayaran, menunggu_konfirmasi, diproses, dibayar, selesai, dibatalkan
        // Kita gunakan 'dibayar' karena itu ekivalen dengan 'lunas'
        $tripsData = [];
        foreach ($trips as $trip) {
            // ★★★ PERBAIKAN: SKIP jadwal dengan status 'selesai' dari daftar perjalanan hari ini
            // Jadwal 'selesai' akan ditampilkan di bagian Riwayat Perjalanan saja
            if ($trip->status === 'selesai') {
                continue;
            }
            // ★★★ AMBIL DATA DARI JADWAL DAN RUTE ★★★
            // Prioritas: masterRute (rute_id) -> jadwal.rutes -> string rute

            // Inisialisasi variabel
            $from = null;
            $to = null;
            $routeName = null;

            // Coba ambil dari masterRute (tabel Rute via rute_id)
            if ($trip->masterRute) {
                $from = $trip->masterRute->kota_asal;
                $to = $trip->masterRute->kota_tujuan;
                $routeName = $trip->masterRute->nama_rute;
            }

            // Fallback: coba dari jadwal (ambil rute pertama dari relasi rutes)
            if (empty($from) && $trip->jadwal && $trip->jadwal->rutes->isNotEmpty()) {
                $firstRute = $trip->jadwal->rutes->first();
                $from = $firstRute->kota_asal;
                $to = $firstRute->kota_tujuan;
                $routeName = $routeName ?? $firstRute->nama_rute;
            }

            // Fallback terakhir: dari kolom rute string
            if (empty($from)) {
                $ruteString = $trip->rute ?? '';
                // Parse format: "Rute Name (Kota Asal → Kota Tujuan)"
                if (preg_match('/^(.+?)\s*\(([^→]+)→([^)]+)\)/', $ruteString, $matches)) {
                    // Format: "Nama Rute (Kota Asal → Kota Tujuan)"
                    $routeName = trim($matches[1]);
                    $from = trim($matches[2]);
                    $to = trim($matches[3]);
                } elseif (preg_match('/\(([^→]+)→([^)]+)\)/', $ruteString, $matches)) {
                    // Format: "(Kota Asal → Kota Tujuan)"
                    $from = trim($matches[1]);
                    $to = trim($matches[2]);
                } else {
                    // Tidak ada format yang cocok, gunakan string langsung
                    $from = $ruteString ?: 'N/A';
                    $to = 'N/A';
                }
            }

            // Jika masih kosong, gunakan data dari driver_jadwal itu sendiri
            if (empty($from)) {
                $from = $trip->kota_asal ?? $trip->asal ?? 'N/A';
                $to = $trip->kota_tujuan ?? $trip->tujuan ?? 'N/A';
            }

            // ★★★ PERBAIKAN: Gunakan kursi_terisi dari database sebagai sumber utama ★★★
            // Ini menyamarakan dengan Riwayat Perjalanan yang menggunakan $trip->kursi_terisi
            $occupiedSeats = $trip->kursi_terisi ?? 0;

            // Ambil data passengers untuk ditampilkan di detail
            $bookings = Pemesanan::with(['detailPenumpang', 'kursiTerpesan'])
                    ->where('id_jadwal_driver', $trip->id_jadwal_driver)
                    ->whereIn('status', ['dibayar', 'diproses', 'menunggu_pembayaran', 'menunggu_konfirmasi'])
                    ->get();

            $passengers = [];

            foreach ($bookings as $booking) {
                // PERBAIKAN: Pastikan detailPenumpang adalah Collection, bukan array
                $detailPenumpangs = $booking->detailPenumpang;
                if (!($detailPenumpangs instanceof \Illuminate\Database\Eloquent\Collection)) {
                    $detailPenumpangs = collect($detailPenumpangs);
                }

                foreach ($detailPenumpangs as $passenger) {
                    // ★★★ PERBAIKAN: Query langsung ke tabel KursiTerposez ★★★
                    // Menggunakan id_jadwal_driver untuk pencarian yang lebih akurat
                    $seat = KursiTerpesan::where('pemesanan_id', $booking->id)
                        ->where('detail_penumpang_id', $passenger->id)
                        ->first();

                    // Fallback: coba cari berdasarkan jadwal_id jika tidak ketemu
                    if (!$seat && $trip->id_jadwal) {
                        $seat = KursiTerpesan::where('jadwal_id', $trip->id_jadwal)
                            ->where('detail_penumpang_id', $passenger->id)
                            ->first();
                    }

                    $passengers[] = [
                        'id' => $passenger->id,
                        'name' => $passenger->nama_lengkap,
                        'phone' => $passenger->telepon ?? $booking->telepon_pemesan,
                        'seat' => $seat ? $seat->nomor_kursi : ($passenger->nomor_kursi ?? 'N/A'),
                        'status' => 'terverifikasi',
                    ];
                }
            }

            // ★★★ AMBIL PEMBERHENTIAN DARI JADWAL/RUTE ★★★
            $stopPoints = $this->getStopPointsFromSchedule($trip);

            // ★★★ AMBIL STARTING OUTLET DARI STOP POINT PERTAMA ★★★
            $startingOutlet = null;
            if (!empty($stopPoints) && is_array($stopPoints) && isset($stopPoints[0])) {
                $firstStop = $stopPoints[0];
                if (isset($firstStop['outlets']) && !empty($firstStop['outlets'])) {
                    $startingOutlet = [
                        'kota' => $firstStop['kota'] ?? null,
                        'branch_id' => $firstStop['branch_id'] ?? null,
                        'branch_name' => $firstStop['branch_name'] ?? null,
                        'outlets' => $firstStop['outlets']
                    ];
                }
            }

            // ★★★ AMBIL DURASI DAN JARAK DARI MASTER RUTE ★★★
            $estimatedDuration = '-';
            $distance = null;

            // Ambil dari masterRute jika ada
            if ($trip->masterRute) {
                // Ambil durasi - gunakan formatted_durasi jika ada, sonst直接从durasi
                $estimatedDuration = $trip->masterRute->formatted_durasi ?? $trip->masterRute->durasi ?? '-';
                // Ambil jarak
                $distance = $trip->masterRute->jarak;
            }

            // Fallback: coba ambil dari jadwal->rutes jika masterRute tidak punya data
            if (($estimatedDuration === '-' || $estimatedDuration === null) && $trip->jadwal) {
                $jadwalRutes = $trip->jadwal->rutes;
                if ($jadwalRutes && $jadwalRutes->isNotEmpty()) {
                    $firstRute = $jadwalRutes->first();
                    $estimatedDuration = $firstRute->formatted_durasi ?? $firstRute->durasi ?? '-';
                    $distance = $distance ?? $firstRute->jarak;
                }
            }

            // Jika masih null, gunakan default
            if ($distance === null) {
                $distance = $trip->jadwal?->jarak ?? null;
            }

            // Periksa apakah ada journey state di DB untuk perjalanan ini (mis. setelah driver mulai)
            $journeyState = DriverJourneyState::where('id_jadwal_driver', $trip->id_jadwal_driver)
                ->where('id_driver', $driver->id)
                ->first();

            // Tentukan status final yang akan dikirim ke view. Jika ada journey state in_progress,
            // override status jadwal agar tampilan menampilkan "Dalam Perjalanan" setelah reload.
            $finalStatus = $trip->status ?? 'belum_dimulai';
            if ($journeyState) {
                if ($journeyState->status === 'in_progress') {
                    $finalStatus = 'dalam_perjalanan';
                } elseif ($journeyState->status === 'completed') {
                    $finalStatus = 'selesai';
                }
            }

            $tripsData[] = [
                'id_jadwal_driver' => $trip->id_jadwal_driver,
                'trip_number' => count($tripsData) + 1,
                'from' => $from,
                'to' => $to,
                'date' => $trip->tanggal ? $trip->tanggal->format('Y-m-d') : 'N/A',
                'time' => $trip->waktu_keberangkatan ?? 'N/A',
                'eta' => $trip->waktu_kedatangan ?? 'N/A',
                'total_seats' => $trip->total_kursi ?? 0,
                'occupied_seats' => $occupiedSeats,
                'status' => $finalStatus,
                'acceptance_status' => $trip->acceptance_status ?? 'accepted',
                'passengers' => $passengers,
                'estimated_duration' => $estimatedDuration,
                'stop_points' => $stopPoints, // ★★★ TAMBAHKAN TITIK PEMBERHENTIAN ★★★
                'starting_outlet' => $startingOutlet, // ★★★ TAMBAHKAN STARTING OUTLET ★★★
                // Ambil jarak dari masterRute
                'distance' => $distance,
                'route_name' => $routeName,
            ];
        }

        return view('driver.perjalanan', compact('driver', 'tripsData', 'completedTrips'));
    }

    /**
     * ★★★ AMBIL TITIK PEMBERHENTIAN DARI JADWAL DAN RUTE ★★★
     * Mengambil outlets pemberhentian sesuai dengan rute yang ada di jadwal
     */
    private function getStopPointsFromSchedule($trip)
    {
        $stopPoints = [];

        try {
            // Ambil jadwal dengan rutes
            $jadwal = $trip->jadwal ?? null;

            if (!$jadwal) {
                return $stopPoints;
            }

            // Ambil rutes yang terkait dengan jadwal
            $rutes = $jadwal->rutes ?? collect();

            if ($rutes->isEmpty()) {
                return $stopPoints;
            }

            // Proses setiap rute untuk mengambil pemberhentiannya
            foreach ($rutes as $rute) {
                $pemberhentian = $rute->rute_pemberhentian ?? [];

                // Pastikan pemberhentian adalah array
                if (!is_array($pemberhentian)) {
                    $pemberhentian = json_decode($pemberhentian, true) ?? [];
                }

                // Proses setiap stop
                foreach ($pemberhentian as $stopIndex => $stop) {
                    if (!is_array($stop)) {
                        continue;
                    }

                    $kota = $stop['kota'] ?? '';
                    $outlets = $stop['outlets'] ?? [];
                    $durasiSinggah = $stop['durasi_singgah'] ?? 10;

                    // Ambil branch berdasarkan kota
                    $branch = Branch::where('kota', $kota)->first();

                    if (!$branch) {
                        continue;
                    }

                    // Ambil outlets aktif dari branch yang ada di pemberhentian ini
                    $branchOutlets = Outlet::where('branch_id', $branch->id)
                        ->where('status', 'aktif')
                        ->get();

                    $outletDetails = [];
                    foreach ($branchOutlets as $outlet) {
                        // Cek jika outlet ada dalam daftar outlets untuk stop ini
                        if (in_array($outlet->nama_outlet, $outlets)) {
                            $outletDetails[] = [
                                'id' => $outlet->id,
                                'nama_outlet' => $outlet->nama_outlet,
                                'alamat' => $outlet->alamat_lengkap ?? '',
                                'kota' => $branch->kota,
                            ];
                        }
                    }

                    // Tambahkan stop point jika ada outlets
                    if (!empty($outletDetails)) {
                        $stopPoints[] = [
                            'urutan' => $stopIndex + 1,
                            'kota' => $kota,
                            'branch_id' => $branch->id,
                            'branch_name' => $branch->nama_cabang,
                            'durasi_singgah' => $durasiSinggah,
                            'outlets' => $outletDetails,
                        ];
                    }
                }
            }

        } catch (\Exception $e) {
            \Log::error('Error getting stop points from schedule: ' . $e->getMessage());
        }

        return $stopPoints;
    }

    /**
     * Calculate estimated duration based on route
     */
    private function calculateDuration($from, $to)
    {
        // Stub untuk durasi - bisa diintegrasikan dengan real data dari tabel rute
        $durationMap = [
            'Jakarta|Bandung' => '3 jam 15 menit',
            'Bandung|Jakarta' => '3 jam 15 menit',
            'Jakarta|Surabaya' => '8 jam',
            'Surabaya|Jakarta' => '8 jam',
        ];

        $key = $from . '|' . $to;
        return $durationMap[$key] ?? '- menit';
    }

    /**
     * Show driver profile
     */
    public function profile()
    {
        $driver = Auth::guard('driver')->user();
        return view('driver.profile', compact('driver'));
    }

    /**
     * Show driver profile edit form
     */
    public function profileEdit()
    {
        // Ambil data driver yang sedang login
        $driver = Auth::guard('driver')->user();

        // Kirim data driver ke view
        return view('driver.profile-edit', compact('driver'));
    }

    /**
     * Update driver profile
     */
    public function updateProfile(Request $request)
    {
        $driver = Auth::guard('driver')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $driver->id,
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:16',
            'nomor_sim' => 'nullable|string|max:20',
            'masa_berlaku_sim' => 'nullable|date',
            'ktp_file' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'sim_file' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'photo_file' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $driver->phone,
            'nik' => $validated['nik'] ?? $driver->nik,
            'nomor_sim' => $validated['nomor_sim'] ?? $driver->nomor_sim,
            'masa_berlaku_sim' => $validated['masa_berlaku_sim'] ?? $driver->masa_berlaku_sim,
        ];

        // Handle file uploads
        if ($request->hasFile('ktp_file')) {
            $file = $request->file('ktp_file');
            $fileName = 'ktp_' . $driver->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('drivers/ktp', $fileName, 'public');
            $updateData['ktp_file'] = 'drivers/ktp/' . $fileName;
        }

        if ($request->hasFile('sim_file')) {
            $file = $request->file('sim_file');
            $fileName = 'sim_' . $driver->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('drivers/sim', $fileName, 'public');
            $updateData['sim_file'] = 'drivers/sim/' . $fileName;
        }

        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $fileName = 'photo_' . $driver->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('drivers/photos', $fileName, 'public');
            $updateData['photo_file'] = 'drivers/photos/' . $fileName;
        }

        // Buat ID pengemudi jika belum ada
        if (!$driver->id_pengemudi) {
            $updateData['id_pengemudi'] = 'DRV-' . date('Y') . '-' . str_pad($driver->id, 5, '0', STR_PAD_LEFT);
        }

        $driver->update($updateData);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Show driver settings page
     */
    public function pengaturan()
    {
        $driver = Auth::guard('driver')->user();
        return view('driver.pengaturan', compact('driver'));
    }

    /**
     * ★★★ Update driver schedule acceptance mode ★★★
     */
    public function updateScheduleAcceptMode(Request $request)
    {
        $validated = $request->validate([
            'schedule_accept_mode' => 'required|in:AUTO_ACCEPT,MANUAL_CONFIRM'
        ]);

        $driver = Auth::guard('driver')->user();
        $driver->update($validated);

        $modeLabel = $validated['schedule_accept_mode'] === 'AUTO_ACCEPT' ? 'Penerimaan Otomatis' : 'Konfirmasi Manual';

        return back()->with('success', 'Mode penerimaan jadwal berhasil diubah menjadi: ' . $modeLabel);
    }

    /**
     * Show driver help/FAQ page
     */
    public function bantuan()
    {
        return view('driver.bantuan');
    }

    /**
     * ★★★ API ENDPOINT: Ambil data penumpang real-time untuk trip tertentu ★★★
     * Data source: Pemesanan + DetailPenumpang (dari admin jadwal penumpang)
     * Digunakan untuk update penumpang tanpa perlu reload halaman
     */
    public function getPassengersRealtime($tripId)
    {
        try {
            $driver = Auth::guard('driver')->user();

            if (!$driver) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Ambil trip data
            $trip = DriverJadwal::with(['jadwal', 'masterRute', 'jadwal.rutes'])
                ->where('id_jadwal_driver', $tripId)
                ->where('id_driver', $driver->id)
                ->first();

            if (!$trip) {
                return response()->json(['error' => 'Trip tidak ditemukan'], 404);
            }

            // ★★★ Ambil data penumpang dari admin Jadwal penumpang source ★★★
            // Gunakan Pemesanan + DetailPenumpang (SAMA dengan getTripDetail() sekarang)
            $jadwalId = $trip->id_jadwal;
            $pemesanan = \App\Models\Jadwal::find($jadwalId)
                ? \App\Models\Jadwal::findOrFail($jadwalId)->pemesanan()
                    ->with(['user', 'detailPenumpang', 'pembayaran', 'kursiTerpesan'])
                    ->get()
                : collect([]);

            // Transform Pemesanan + DetailPenumpang ke format penumpang
            $passengers = [];
            $occupiedCount = 0;

            foreach ($pemesanan as $booking) {
                foreach ($booking->detailPenumpang as $detail) {
                    $occupiedCount++;

                    // Cari seat dari kursiTerpesan
                    $seat = $booking->kursiTerpesan()
                        ->where('detail_penumpang_id', $detail->id)
                        ->first();

                    $passengers[] = [
                        'id' => $detail->id,
                        'name' => $detail->nama_lengkap,
                        'phone' => $detail->telepon ?? $booking->telepon_pemesan,
                        'seat' => $seat ? $seat->nomor_kursi : 'N/A',
                        'nik' => $detail->nik,
                        'status' => $seat ? $seat->status : 'pending',
                        'jenis_kelamin' => $detail->jenis_kelamin,
                    ];
                }
            }

            // Hitung total kursi
            $totalSeats = $trip->total_kursi ?? 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'trip_id' => $tripId,
                    'total_passengers' => count($passengers),
                    'occupied_seats' => $occupiedCount,
                    'total_seats' => $totalSeats,
                    'available_seats' => $totalSeats - $occupiedCount,
                    'passengers' => $passengers,
                    'timestamp' => now()->toIso8601String()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting passengers realtime: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
