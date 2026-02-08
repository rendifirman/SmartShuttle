<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Shuttle;
use App\Models\Rute;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with(['shuttle', 'rutes'])
            ->orderBy('tanggal_keberangkatan', 'desc')
            ->orderBy('waktu_keberangkatan', 'asc');

        // Filter logic
        if ($request->filled('rute_id')) {
            $query->whereHas('rutes', function($q) use ($request) {
                $q->where('rute_id', $request->rute_id);
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_keberangkatan', $request->tanggal);
        }

        if ($request->filled('status')) {
            if ($request->status == 'hampir_penuh') {
                $query->where('status', 'tersedia')
                    ->whereHas('shuttle', function($q) {
                        $q->whereRaw('jadwals.kursi_tersedia <= (shuttles.kapasitas_kursi * 0.2)');
                    });
            } elseif ($request->status == 'penuh') {
                $query->where(function($q) {
                    $q->where('status', 'penuh')
                      ->orWhere('kursi_tersedia', '<=', 0);
                });
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('shuttle_id')) {
            $query->where('shuttle_id', $request->shuttle_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('shuttle', function($q) use ($search) {
                    $q->where('nama_shuttle', 'like', "%{$search}%")
                      ->orWhere('plat_nomor', 'like', "%{$search}%");
                })->orWhereHas('rutes', function($q) use ($search) {
                    $q->where('nama_rute', 'like', "%{$search}%")
                      ->orWhere('kota_asal', 'like', "%{$search}%")
                      ->orWhere('kota_tujuan', 'like', "%{$search}%");
                });
            });
        }

        $jadwals = $query->paginate(10);

        // Statistics
        $totalJadwal = Jadwal::count();
        
        $tersedia = Jadwal::where('status', 'tersedia')
            ->where('kursi_tersedia', '>', 0)
            ->count();
        
        $hampirPenuh = Jadwal::where('status', 'tersedia')
            ->where('kursi_tersedia', '>', 0)
            ->with(['shuttle'])
            ->get()
            ->filter(function($jadwal) {
                $totalKursi = $jadwal->shuttle->kapasitas_kursi ?? $jadwal->shuttle->total_kursi ?? 0;
                if ($totalKursi == 0) return false;
                $persentase = ($jadwal->kursi_tersedia / $totalKursi) * 100;
                return $persentase <= 20;
            })->count();
        
        $penuh = Jadwal::where(function($q) {
                $q->where('status', 'penuh')
                  ->orWhere('kursi_tersedia', '<=', 0);
            })->count();

        $shuttles = Shuttle::all();
        $rutes = Rute::all();

        return view('admin.jadwal-index', compact(
            'jadwals',
            'totalJadwal',
            'tersedia',
            'hampirPenuh',
            'penuh',
            'shuttles',
            'rutes'
        ));
    }

    public function create()
    {
        $shuttles = Shuttle::all();
        $rutes = Rute::all();
        
        return view('admin.jadwal-create', compact('shuttles', 'rutes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shuttle_id' => 'required|exists:shuttles,id',
            'rute_id' => 'required|exists:rutes,id',
            'tanggal_keberangkatan' => 'required|date|after_or_equal:today',
            'waktu_keberangkatan' => 'required',
            'waktu_kedatangan' => 'required',
            'harga_total' => 'required|numeric|min:1000', // Minimal 1000
        ]);

        // Tidak ada validasi waktu keberangkatan harus lebih awal
        // Bisa 21:00 -> 03:30 (melewati tengah malam)

        $shuttle = Shuttle::findOrFail($request->shuttle_id);
        $totalKursi = $shuttle->kapasitas_kursi ?? $shuttle->total_kursi ?? 0;

        $jadwal = Jadwal::create([
            'shuttle_id' => $request->shuttle_id,
            'tanggal_keberangkatan' => $request->tanggal_keberangkatan,
            'waktu_keberangkatan' => $request->waktu_keberangkatan,
            'waktu_kedatangan' => $request->waktu_kedatangan,
            'harga_total' => $request->harga_total,
            'kursi_tersedia' => $totalKursi,
            'status' => 'tersedia',
        ]);

        $jadwal->rutes()->attach($request->rute_id, [
            'urutan' => 1,
            'durasi_segment' => $this->calculateDuration($request->waktu_keberangkatan, $request->waktu_kedatangan),
            'harga_segment' => $request->harga_total,
        ]);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dibuat!');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::with(['shuttle', 'rutes'])->findOrFail($id);
        $shuttles = Shuttle::all();
        $rutes = Rute::all();
        
        $totalKursi = $jadwal->shuttle->kapasitas_kursi ?? $jadwal->shuttle->total_kursi ?? 0;
        
        return view('admin.jadwal-edit', compact('jadwal', 'shuttles', 'rutes', 'totalKursi'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'shuttle_id' => 'required|exists:shuttles,id',
            'rute_id' => 'required|exists:rutes,id',
            'tanggal_keberangkatan' => 'required|date',
            'waktu_keberangkatan' => 'required',
            'waktu_kedatangan' => 'required',
            'harga_total' => 'required|numeric|min:1000',
            'kursi_tersedia' => 'required|integer|min:0',
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $shuttle = Shuttle::findOrFail($request->shuttle_id);
        $totalKursi = $shuttle->kapasitas_kursi ?? $shuttle->total_kursi ?? 0;

        if ($request->kursi_tersedia > $totalKursi) {
            return back()->withErrors(['kursi_tersedia' => 'Kursi tersedia tidak boleh melebihi kapasitas armada'])->withInput();
        }

        $status = $this->calculateStatus($request->kursi_tersedia, $shuttle);

        $jadwal->update([
            'shuttle_id' => $request->shuttle_id,
            'tanggal_keberangkatan' => $request->tanggal_keberangkatan,
            'waktu_keberangkatan' => $request->waktu_keberangkatan,
            'waktu_kedatangan' => $request->waktu_kedatangan,
            'harga_total' => $request->harga_total,
            'kursi_tersedia' => $request->kursi_tersedia,
            'status' => $status,
        ]);

        $jadwal->rutes()->sync([$request->rute_id => [
            'urutan' => 1,
            'durasi_segment' => $this->calculateDuration($request->waktu_keberangkatan, $request->waktu_kedatangan),
            'harga_segment' => $request->harga_total,
        ]]);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        if ($jadwal->driverSchedules()->exists()) {
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Jadwal tidak dapat dihapus karena sudah diambil driver.');
        }
        
        $jadwal->delete();
        
        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }

    private function calculateDuration($waktuBerangkat, $waktuTiba)
    {
        $berangkat = Carbon::parse($waktuBerangkat);
        $tiba = Carbon::parse($waktuTiba);
        
        // Jika waktu tiba lebih kecil dari waktu berangkat, berarti melewati tengah malam
        if ($tiba < $berangkat) {
            $tiba->addDay(); // Tambah 1 hari
        }
        
        return $berangkat->diffInMinutes($tiba);
    }

    private function calculateStatus($kursiTersedia, $shuttle)
    {
        if ($kursiTersedia <= 0) {
            return 'penuh';
        }
        
        $totalKursi = $shuttle->kapasitas_kursi ?? $shuttle->total_kursi ?? 0;
        if ($totalKursi == 0) {
            return 'tersedia';
        }
        
        $persentase = ($kursiTersedia / $totalKursi) * 100;
        if ($persentase <= 20) {
            return 'hampir_penuh';
        }
        
        return 'tersedia';
    }
}