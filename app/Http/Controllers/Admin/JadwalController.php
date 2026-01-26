<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Shuttle;
use App\Models\MLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    // Method untuk halaman list (index)
    public function index(Request $request)
    {
        // Ambil data filter
        $search = $request->query('search');
        $tanggal = $request->query('tanggal');
        $status = $request->query('status');
        $shuttleId = $request->query('shuttle_id');
        $layananId = $request->query('layanan_id');
        
        // Query jadwal dengan relasi
        $query = Jadwal::with(['shuttle', 'shuttle.layanan'])
            ->orderBy('tanggal_keberangkatan', 'desc')
            ->orderBy('waktu_keberangkatan', 'asc');
        
        // Apply filters
        if ($search) {
            $query->whereHas('shuttle', function($q) use ($search) {
                $q->where('nama_shuttle', 'like', "%{$search}%")
                  ->orWhere('nomor_polisi', 'like', "%{$search}%")
                  ->orWhereHas('layanan', function($q2) use ($search) {
                      $q2->where('nama_layanan', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($tanggal) {
            $query->whereDate('tanggal_keberangkatan', $tanggal);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($shuttleId) {
            $query->where('shuttle_id', $shuttleId);
        }
        
        if ($layananId) {
            $query->whereHas('shuttle', function($q) use ($layananId) {
                $q->where('layanan_id', $layananId);
            });
        }
        
        // Pagination
        $jadwals = $query->paginate(10);
        
        // Data untuk filter dropdown
        $layanans = MLayanan::where('status_aktif', true)->get();
        $shuttles = Shuttle::with('layanan')->get();
        
        // Summary data
        $totalJadwals = Jadwal::count();
        $tersediaJadwals = Jadwal::where('status', 'tersedia')->count();
        $penuhJadwals = Jadwal::where('status', 'penuh')->count();
        
        // Hitung hampir penuh (tersedia <= 20%)
        $hampirPenuhJadwals = Jadwal::where('status', 'tersedia')
            ->get()
            ->filter(function($jadwal) {
                $kapasitas = $jadwal->shuttle ? $jadwal->shuttle->total_kursi : 0;
                $persentase = $kapasitas > 0 ? ($jadwal->kursi_tersedia / $kapasitas) * 100 : 0;
                return $persentase <= 20;
            })->count();
        
        return view('admin.jadwal', compact(
            'jadwals',
            'layanans',
            'shuttles',
            'totalJadwals',
            'tersediaJadwals',
            'hampirPenuhJadwals',
            'penuhJadwals'
        ));
    }
    
    // Method untuk halaman tambah (create)
    public function create()
    {
        // Ambil data layanan dari tabel m_layanan
        $layanans = MLayanan::where('status_aktif', true)
            ->orderBy('urutan_tampilan', 'asc')
            ->orderBy('nama_layanan', 'asc')
            ->get();
        
        // Inisialisasi shuttles sebagai collection kosong
        $shuttles = collect();
        
        // Jika ada old layanan_id, ambil shuttle untuk layanan tersebut
        if (old('layanan_id')) {
            $shuttles = Shuttle::where('layanan_id', old('layanan_id'))
                ->where('status', 'aktif')
                ->get();
        }
        
        return view('admin.jadwal-create', compact('layanans', 'shuttles'));
    }
    
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'layanan_id' => 'required|exists:m_layanan,id_layanan',
            'shuttle_id' => 'required|exists:shuttles,id',
            'tanggal_keberangkatan' => 'required|date|after_or_equal:today',
            'waktu_keberangkatan' => 'required',
            'waktu_kedatangan' => 'required|after:waktu_keberangkatan',
            'harga_total' => 'required|numeric|min:1000',
            'kursi_tersedia' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:500',
            'status' => 'required|in:tersedia,penuh,dibatalkan'
        ]);
        
        // Cek konsistensi layanan_id dan shuttle_id
        $shuttle = Shuttle::find($request->shuttle_id);
        if (!$shuttle) {
            return back()->withErrors(['shuttle_id' => 'Armada tidak ditemukan'])
                ->withInput();
        }
        
        // Pastikan shuttle memiliki layanan_id yang sama dengan yang dipilih
        if ($shuttle->layanan_id != $request->layanan_id) {
            return back()->withErrors([
                'shuttle_id' => 'Armada ini tidak termasuk dalam layanan yang dipilih'
            ])->withInput();
        }
        
        // Cek kapasitas shuttle
        if ($request->kursi_tersedia > $shuttle->total_kursi) {
            return back()->withErrors([
                'kursi_tersedia' => 'Kursi tersedia tidak boleh melebihi kapasitas shuttle (' . $shuttle->total_kursi . ' kursi)'
            ])->withInput();
        }
        
        // Cek apakah shuttle aktif
        if ($shuttle->status !== 'aktif') {
            return back()->withErrors([
                'shuttle_id' => 'Armada tidak aktif. Silakan pilih armada lain.'
            ])->withInput();
        }
        
        // Simpan data
        try {
            DB::beginTransaction();
            
            $jadwal = new Jadwal();
            $jadwal->shuttle_id = $request->shuttle_id;
            $jadwal->tanggal_keberangkatan = $request->tanggal_keberangkatan;
            $jadwal->waktu_keberangkatan = $request->waktu_keberangkatan;
            $jadwal->waktu_kedatangan = $request->waktu_kedatangan;
            $jadwal->harga_total = $request->harga_total;
            $jadwal->kursi_tersedia = $request->kursi_tersedia;
            $jadwal->keterangan = $request->keterangan;
            $jadwal->status = $request->status;
            $jadwal->save();
            
            DB::commit();
            
            return redirect()->route('admin.jadwal')
                ->with('success', 'Jadwal berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating jadwal: ' . $e->getMessage(), [
                'request' => $request->all()
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }
    
    // Method untuk AJAX: get shuttles by layanan
    public function getShuttlesByLayanan(Request $request)
    {
        try {
            $layananId = $request->query('layanan_id');
            
            Log::info('Fetching shuttles for layanan_id:', ['layanan_id' => $layananId]);
            
            if (!$layananId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Layanan ID tidak ditemukan'
                ], 400);
            }
            
            // Validasi apakah layanan ID valid
            $layananExists = MLayanan::where('id_layanan', $layananId)->exists();
            
            if (!$layananExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Layanan tidak ditemukan'
                ], 404);
            }
            
            // PERBAIKAN UTAMA: Query shuttles dengan kondisi yang benar
            $shuttles = Shuttle::where('layanan_id', $layananId)
                ->where('status', 'aktif') // Hanya shuttle aktif
                ->whereNotNull('total_kursi') // Pastikan ada kapasitas
                ->where('total_kursi', '>', 0) // Kapasitas > 0
                ->orderBy('nama_shuttle')
                ->get(['id', 'nama_shuttle', 'nomor_polisi', 'total_kursi']);
            
            Log::info('Shuttles found:', [
                'count' => $shuttles->count(),
                'layanan_id' => $layananId
            ]);
            
            // Format response
            $formattedShuttles = $shuttles->map(function($shuttle) {
                return [
                    'id' => $shuttle->id,
                    'nama_shuttle' => $shuttle->nama_shuttle,
                    'nomor_polisi' => $shuttle->nomor_polisi,
                    'total_kursi' => $shuttle->total_kursi,
                    'display_text' => "{$shuttle->nama_shuttle} ({$shuttle->nomor_polisi}) - {$shuttle->total_kursi} kursi"
                ];
            });
            
            return response()->json([
                'success' => true,
                'shuttles' => $formattedShuttles,
                'count' => $formattedShuttles->count(),
                'message' => $formattedShuttles->count() > 0 ? 
                    'Data armada berhasil diambil' : 
                    'Tidak ada armada tersedia untuk layanan ini'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getShuttlesByLayanan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Method untuk halaman detail (show)
    public function show(Jadwal $jadwal)
    {
        $jadwal->load(['shuttle', 'shuttle.layanan']);
        return view('admin.jadwal.show', compact('jadwal'));
    }
    
    // Method untuk halaman edit (edit)
    public function edit(Jadwal $jadwal)
    {
        $layanans = MLayanan::where('status_aktif', true)->get();
        
        // Ambil shuttles untuk layanan yang dipilih
        $shuttles = collect();
        if ($jadwal->shuttle && $jadwal->shuttle->layanan_id) {
            $shuttles = Shuttle::where('layanan_id', $jadwal->shuttle->layanan_id)
                ->where('status', 'aktif')
                ->get();
        }
        
        $jadwal->load(['shuttle', 'shuttle.layanan']);
        
        return view('admin.jadwal.edit', compact('jadwal', 'layanans', 'shuttles'));
    }
    
    // Method untuk update data (update)
    public function update(Request $request, Jadwal $jadwal)
    {
        // Validasi
        $validated = $request->validate([
            'layanan_id' => 'required|exists:m_layanan,id_layanan',
            'shuttle_id' => 'required|exists:shuttles,id',
            'tanggal_keberangkatan' => 'required|date',
            'waktu_keberangkatan' => 'required',
            'waktu_kedatangan' => 'required|after:waktu_keberangkatan',
            'harga_total' => 'required|numeric|min:1000',
            'kursi_tersedia' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:500',
        ]);
        
        // Cek kapasitas shuttle
        $shuttle = Shuttle::find($request->shuttle_id);
        if ($shuttle && $request->kursi_tersedia > $shuttle->total_kursi) {
            return back()->withErrors([
                'kursi_tersedia' => 'Kursi tersedia tidak boleh melebihi kapasitas shuttle (' . $shuttle->total_kursi . ' kursi)'
            ])->withInput();
        }
        
        // Update data
        try {
            DB::beginTransaction();
            
            $jadwal->shuttle_id = $request->shuttle_id;
            $jadwal->tanggal_keberangkatan = $request->tanggal_keberangkatan;
            $jadwal->waktu_keberangkatan = $request->waktu_keberangkatan;
            $jadwal->waktu_kedatangan = $request->waktu_kedatangan;
            $jadwal->harga_total = $request->harga_total;
            $jadwal->kursi_tersedia = $request->kursi_tersedia;
            $jadwal->keterangan = $request->keterangan;
            
            // Update status jika perlu
            if ($jadwal->kursi_tersedia == 0) {
                $jadwal->status = 'penuh';
            } elseif ($jadwal->status == 'penuh' && $jadwal->kursi_tersedia > 0) {
                $jadwal->status = 'tersedia';
            }
            
            $jadwal->save();
            
            DB::commit();
            
            return redirect()->route('admin.jadwal')
                ->with('success', 'Jadwal berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating jadwal: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }
    
    // Method untuk hapus data (destroy)
    public function destroy(Jadwal $jadwal)
    {
        try {
            DB::beginTransaction();
            
            $jadwal->delete();
            
            DB::commit();
            
            return redirect()->route('admin.jadwal')
                ->with('success', 'Jadwal berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting jadwal: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}