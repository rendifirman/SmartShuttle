<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Jadwal;
use App\Models\DriverJadwal;
use Carbon\Carbon;

class DriverJadwalController extends Controller
{
    /**
     * Menampilkan jadwal tersedia dari admin
     */
    public function daftarJadwalTersedia()
    {
        $driver = Auth::guard('driver')->user();

        // ★★★ FITUR BARU: Filter berdasarkan schedule_accept_mode driver ★★★
        if ($driver->schedule_accept_mode === 'MANUAL_CONFIRM') {
            // Driver MANUAL_CONFIRM hanya bisa melihat jadwal global
            $jadwalTersedia = Jadwal::jadwalGlobal()
                ->with(['shuttle', 'rutes'])
                ->orderBy('tanggal_keberangkatan', 'asc')
                ->orderBy('waktu_keberangkatan', 'asc')
                ->paginate(10);
        } else {
            // Driver AUTO_ACCEPT hanya bisa melihat jadwal yang di-assign ke mereka
            $jadwalTersedia = Jadwal::jadwalAssigned()
                ->where('driver_id', $driver->id)
                ->where('status_admin', '!=', 'diambil')
                ->with(['shuttle', 'rutes'])
                ->orderBy('tanggal_keberangkatan', 'asc')
                ->orderBy('waktu_keberangkatan', 'asc')
                ->paginate(10);
        }

        return view('driver.jadwal-tersedia', compact('jadwalTersedia'));
    }

    /**
     * Driver mengambil jadwal dari admin
     */
    public function ambilJadwal(Request $request, $idJadwal)
    {
        $driver = Auth::guard('driver')->user();

        // Validasi input
        $request->validate([
            'konfirmasi' => 'required|accepted'
        ]);

        try {
            return DB::transaction(function () use ($idJadwal, $driver, $request) {
                // Lock row jadwal untuk menghindari race condition
                $jadwal = Jadwal::where('id', $idJadwal)
                    ->lockForUpdate()
                    ->firstOrFail();

                // ★★★ FITUR BARU: Validate based on schedule_accept_mode ★★★
                if ($driver->schedule_accept_mode === 'AUTO_ACCEPT') {
                    // Driver AUTO_ACCEPT hanya bisa menerima jadwal yang di-assign ke mereka
                    if ($jadwal->driver_id !== $driver->id) {
                        return back()->with('error', 'Jadwal ini tidak di-assign untuk Anda.');
                    }

                    if ($jadwal->status_admin === 'diambil') {
                        return back()->with('error', 'Jadwal ini sudah dikonfirmasi.');
                    }
                } else {
                    // Driver MANUAL_CONFIRM bisa mengklaim jadwal global
                    if (!$jadwal->is_global_schedule) {
                        return back()->with('error', 'Jadwal ini bukan jadwal global.');
                    }

                    // Cek apakah jadwal sudah diambil driver lain
                    if ($jadwal->status_admin === 'diambil') {
                        return back()->with('error', 'Jadwal ini sudah diambil driver lain.');
                    }
                }

                // Cek apakah driver sudah mengambil jadwal ini
                $sudahAmbil = \App\Models\DriverJadwal::where('id_driver', $driver->id)
                    ->where('id_jadwal', $idJadwal)
                    ->exists();

                if ($sudahAmbil) {
                    return back()->with('error', 'Anda sudah mengambil jadwal ini.');
                }

                // VALIDASI BATAS 20 JADWAL PER BULAN
                $tanggalJadwal = Carbon::parse($jadwal->tanggal_keberangkatan);
                $jumlahJadwalBulanIni = \App\Models\DriverJadwal::where('id_driver', $driver->id)
                    ->whereYear('tanggal', $tanggalJadwal->year)
                    ->whereMonth('tanggal', $tanggalJadwal->month)
                    ->count();

                if ($jumlahJadwalBulanIni >= 20) {
                    return back()->with('error', 'Anda sudah mencapai batas 20 jadwal dalam bulan ini. Tidak dapat mengambil jadwal baru.');
                }

                // Simpan data ke tabel driver_jadwals
                $rute = $jadwal->rutes->first();
                $shuttle = $jadwal->shuttle;

                // Hitung total kursi dari shuttle
                $totalKursi = $shuttle ? ($shuttle->kapasitas_kursi ?? $shuttle->total_kursi ?? 0) : 0;

                // Hitung kursi yang sudah terisi
                $kursiTerisi = $totalKursi - $jadwal->kursi_tersedia;

                // Buat record di driver_jadwals
                $driverJadwal = \App\Models\DriverJadwal::create([
                    'id_jadwal' => $jadwal->id,
                    'id_driver' => $driver->id,
                    'rute' => $rute ? ($rute->nama_rute . ' (' . $rute->kota_asal . ' → ' . $rute->kota_tujuan . ')') : 'Rute Tidak Diketahui',
                    'tanggal' => $jadwal->tanggal_keberangkatan,
                    'armada' => $shuttle ? $shuttle->nama_shuttle . ' (' . ($shuttle->plat_nomor ?? '-') . ')' : 'Armada Tidak Diketahui',
                    'waktu_keberangkatan' => $jadwal->waktu_keberangkatan,
                    'waktu_kedatangan' => $jadwal->waktu_kedatangan,
                    'harga' => $jadwal->harga_total,
                    'total_kursi' => $totalKursi,
                    'kursi_terisi' => $kursiTerisi,
                    'status' => 'aktif',
                    'waktu_diambil' => Carbon::now(),
                ]);

                // Update status jadwal admin menjadi "diambil"
                $jadwal->status_admin = 'diambil';
                $jadwal->save();

                // ★★★ Untuk jadwal global MANUAL_CONFIRM, assign ke driver saat di-klaim ★★★
                if ($jadwal->is_global_schedule && $driver->schedule_accept_mode === 'MANUAL_CONFIRM') {
                    $jadwal->driver_id = $driver->id;
                    $jadwal->is_global_schedule = false;
                    $jadwal->save();
                }

                return redirect()->route('driver.jadwal.saya')
                    ->with('success', 'Jadwal berhasil diambil!');
            });

        } catch (\Exception $e) {
            \Log::error('Error mengambil jadwal: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan jadwal milik driver
     */
    public function jadwalSaya()
    {
        $driver = Auth::guard('driver')->user();

        // Ambil data langsung dari tabel driver_jadwals
        $jadwalSaya = DriverJadwal::where('id_driver', $driver->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_keberangkatan', 'asc')
            ->get();

        // Hitung statistik bulan ini
        $tahunBulanSekarang = Carbon::now();
        $jumlahJadwalBulanIni = DriverJadwal::where('id_driver', $driver->id)
            ->whereYear('tanggal', $tahunBulanSekarang->year)
            ->whereMonth('tanggal', $tahunBulanSekarang->month)
            ->count();

        $limitBulanIni = 20;
        $sisaKuota = $limitBulanIni - $jumlahJadwalBulanIni;

        return view('driver.jadwal-saya', compact(
            'jadwalSaya',
            'driver',
            'jumlahJadwalBulanIni',
            'sisaKuota'
        ));
    }

    /**
     * Update status jadwal driver
     */
    public function updateStatus(Request $request, $idJadwalDriver)
    {
        $request->validate([
            'status' => 'required|in:aktif,selesai,dibatalkan'
        ]);

        $driverJadwal = DriverJadwal::where('id_jadwal_driver', $idJadwalDriver)
            ->where('id_driver', Auth::guard('driver')->id())
            ->firstOrFail();

        $driverJadwal->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status jadwal berhasil diperbarui.');
    }

    /**
     * Detail jadwal driver
     */
    public function detailJadwal($idJadwalDriver)
    {
        $driverJadwal = DriverJadwal::where('id_jadwal_driver', $idJadwalDriver)
            ->where('id_driver', Auth::guard('driver')->id())
            ->firstOrFail();

        return view('driver.detail-jadwal', compact('driverJadwal'));
    }

    /**
     * Dashboard driver
     */
    public function dashboard()
    {
        $driver = Auth::guard('driver')->user();

        $totalJadwal = DriverJadwal::where('id_driver', $driver->id)->count();

        $tahunBulanSekarang = Carbon::now();
        $jumlahJadwalBulanIni = DriverJadwal::where('id_driver', $driver->id)
            ->whereYear('tanggal', $tahunBulanSekarang->year)
            ->whereMonth('tanggal', $tahunBulanSekarang->month)
            ->count();

        $jadwalAktif = DriverJadwal::where('id_driver', $driver->id)
            ->where('status', 'aktif')
            ->count();

        $jadwalSelesai = DriverJadwal::where('id_driver', $driver->id)
            ->where('status', 'selesai')
            ->count();

        $pendapatan = DriverJadwal::where('id_driver', $driver->id)
            ->where('status', 'selesai')
            ->sum('harga');

        $jadwalTerbaru = DriverJadwal::where('id_driver', $driver->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ★★★ TAMBAHAN: Jadwal hari ini ★★★
        $today = Carbon::today();
        $schedules = DriverJadwal::with(['jadwal', 'masterRute'])
            ->where('id_driver', $driver->id)
            ->whereDate('tanggal', $today)
            ->orderBy('waktu_keberangkatan', 'asc')
            ->get();

        \Log::info('DriverJadwalController dashboard', [
            'driver_id' => $driver->id,
            'today' => $today->format('Y-m-d'),
            'schedules_count' => $schedules->count(),
            'all_dates' => DriverJadwal::where('id_driver', $driver->id)->pluck('tanggal')->unique()->values()
        ]);

        return view('driver.dashboard', compact(
            'driver',
            'totalJadwal',
            'jumlahJadwalBulanIni',
            'jadwalAktif',
            'jadwalSelesai',
            'pendapatan',
            'jadwalTerbaru',
            'schedules'
        ));
    }

    /**
     * Batalkan jadwal
     */
    public function batalkanJadwal($idJadwalDriver)
    {
        try {
            DB::transaction(function () use ($idJadwalDriver) {
                $driverJadwal = DriverJadwal::where('id_jadwal_driver', $idJadwalDriver)
                    ->where('id_driver', Auth::guard('driver')->id())
                    ->firstOrFail();

                // Dapatkan id_jadwal sebelum menghapus
                $idJadwal = $driverJadwal->id_jadwal;

                // Hapus jadwal driver
                $driverJadwal->delete();

                // Update status jadwal admin kembali menjadi tersedia
                $jadwal = Jadwal::find($idJadwal);
                if ($jadwal) {
                    $jadwal->status_admin = null;
                    $jadwal->save();
                }
            });

            return back()->with('success', 'Jadwal berhasil dibatalkan.');

        } catch (\Exception $e) {
            \Log::error('Error membatalkan jadwal: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membatalkan jadwal.');
        }
    }
}
