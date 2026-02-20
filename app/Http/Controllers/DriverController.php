<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DriverSchedule; // Tambahkan ini
use App\Models\DriverJadwal;
use App\Models\DriverLocation;
use App\Models\Pemesanan;
use App\Models\Rute;
use App\Models\Outlet;
use App\Models\Branch;

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

            $jadwalAktif = $allSchedules->where('status', 'aktif')->count();
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
    public function laporan()
    {
        return view('driver.laporan');
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

        // Ambil data jadwal driver untuk hari ini dan masa depan
        $today = \Carbon\Carbon::today();
        $trips = DriverJadwal::with(['jadwal', 'masterRute', 'jadwal.rutes'])
            ->where('id_driver', $driver->id)
            ->where('tanggal', '>=', $today)
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu_keberangkatan', 'asc')
            ->get();

        // Ambil history perjalanan (selesai)
        $completedTrips = DriverJadwal::with(['jadwal', 'masterRute'])
            ->where('id_driver', $driver->id)
            ->where('status', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

            // Ambil data penumpang untuk setiap trip
            // PERBAIKAN: Kolom yang benar adalah 'STATUS' (bukan status_pembayaran)
            // Nilai yang valid: menunggu_pembayaran, menunggu_konfirmasi, diproses, dibayar, selesai, dibatalkan
            // Kita gunakan 'dibayar' karena itu ekivalen dengan 'lunas'
            $tripsData = [];
            foreach ($trips as $trip) {
            $bookings = Pemesanan::with(['detailPenumpang', 'kursiTerpesan'])
                    ->where('id_jadwal_driver', $trip->id_jadwal_driver)
                    ->whereIn('status', ['dibayar'])
                    ->get();

            $passengers = [];
            foreach ($bookings as $booking) {
                // PERBAIKAN: Pastikan detailPenumpang adalah Collection, bukan array
                $detailPenumpangs = $booking->detailPenumpang;
                if (!($detailPenumpangs instanceof \Illuminate\Database\Eloquent\Collection)) {
                    $detailPenumpangs = collect($detailPenumpangs);
                }

                foreach ($detailPenumpangs as $passenger) {
                    $seat = $booking->kursiTerpesan()
                        ->where('detail_penumpang_id', $passenger->id)
                        ->first();

                    $passengers[] = [
                        'id' => $passenger->id,
                        'name' => $passenger->nama_penumpang,
                        'phone' => $passenger->nomor_telepon ?? $booking->telepon_pemesan,
                        'seat' => $seat ? $seat->nomor_kursi : 'N/A',
                        'status' => $passenger->status_verifikasi ?? 'terverifikasi',
                    ];
                }
            }

            // Ambil rute asal-tujuan
            $route = $trip->masterRute ?? $trip->rute;
            $from = $trip->jadwal?->asal ?? 'N/A';
            $to = $trip->jadwal?->tujuan ?? 'N/A';

            // ★★★ AMBIL PEMBERHENTIAN DARI JADWAL/RUTE ★★★
            $stopPoints = $this->getStopPointsFromSchedule($trip);

            $tripsData[] = [
                'id_jadwal_driver' => $trip->id_jadwal_driver,
                'trip_number' => count($tripsData) + 1,
                'from' => $from,
                'to' => $to,
                'date' => $trip->tanggal ? $trip->tanggal->format('Y-m-d') : 'N/A',
                'time' => $trip->waktu_keberangkatan ?? 'N/A',
                'eta' => $trip->waktu_kedatangan ?? 'N/A',
                'total_seats' => $trip->total_kursi ?? 0,
                'occupied_seats' => $trip->kursi_terisi ?? 0,
                'status' => $trip->status ?? 'belum_dimulai',
                'passengers' => $passengers,
                'estimated_duration' => $this->calculateDuration($from, $to),
                'stop_points' => $stopPoints, // ★★★ TAMBAHKAN TITIK PEMBERHENTIAN ★★★
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
}
