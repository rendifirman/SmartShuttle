<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\Branch;
use Illuminate\Support\Str;

class OutletController extends Controller
{
    public function index()
    {
        // Ambil semua data outlet dengan relasi branch
        $outlets = Outlet::with('branch')
            ->where('status', 'aktif')
            ->orderBy('nama_outlet')
            ->get();

        // Ambil data cabang untuk filter
        $branches = Branch::where('status', 'aktif')
            ->orderBy('kota')
            ->get();

        // Ambil data kota unik dari branches untuk filter
        $kotaList = Branch::select('kota')
            ->distinct()
            ->where('status', 'aktif')
            ->orderBy('kota')
            ->pluck('kota')
            ->toArray();

        return view('customer.outlet', compact('outlets', 'branches', 'kotaList'));
    }

    public function filter(Request $request)
    {
        $kota = $request->input('kota');
        $branchId = $request->input('branch_id');
        $branchName = $request->input('branch_name');

        $query = Outlet::with('branch')
            ->where('status', 'aktif');

        // Filter berdasarkan kota
        if ($kota) {
            $query->whereHas('branch', function($q) use ($kota) {
                $q->where('kota', 'like', '%' . $kota . '%');
            });
        }

        // Filter berdasarkan branch_id
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        // Jika hanya ada branch_name tanpa branch_id
        elseif ($branchName && !$branchId) {
            $query->whereHas('branch', function($q) use ($branchName) {
                $q->where('nama_cabang', 'like', '%' . $branchName . '%')
                  ->orWhere('kota', 'like', '%' . $branchName . '%')
                  ->orWhereRaw("CONCAT(nama_cabang, ' - ', kota) LIKE ?", ['%' . $branchName . '%']);
            });
        }

        $outlets = $query->orderBy('nama_outlet')->get();
        $branches = Branch::where('status', 'aktif')->orderBy('kota')->get();
        $kotaList = Branch::select('kota')
            ->distinct()
            ->where('status', 'aktif')
            ->orderBy('kota')
            ->pluck('kota')
            ->toArray();

        return view('customer.outlet', compact('outlets', 'branches', 'kotaList', 'kota', 'branchId', 'branchName'));
    }

    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0);
        $kota = $request->input('kota', '');
        $branchId = $request->input('branch_id', '');

        $query = Outlet::with('branch')
            ->where('status', 'aktif');

        // Apply filters
        if ($kota) {
            $query->whereHas('branch', function($q) use ($kota) {
                $q->where('kota', 'like', '%' . $kota . '%');
            });
        }

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $totalOutlets = $query->count();
        
        // Get next 6 outlets
        $outlets = $query->orderBy('nama_outlet')
            ->offset($offset)
            ->limit(6)
            ->get();

        $hasMore = ($offset + $outlets->count()) < $totalOutlets;

        // Generate HTML for new outlets
        $html = '';
        foreach ($outlets as $outlet) {
            // Helper function untuk gambar outlet
            function getOutletImage($outlet) {
                if (!empty($outlet->foto_outlet)) {
                    // Jika sudah URL lengkap
                    if (Str::startsWith($outlet->foto_outlet, ['http://', 'https://'])) {
                        return $outlet->foto_outlet;
                    }

                    // Cek apakah file ada di public/images/outlets/
                    $filename = basename($outlet->foto_outlet);
                    $publicPath = 'images/outlets/' . $filename;

                    if (file_exists(public_path($publicPath))) {
                        return asset($publicPath);
                    }

                    // Coba langsung path yang ada
                    if (file_exists(public_path($outlet->foto_outlet))) {
                        return asset($outlet->foto_outlet);
                    }
                }

                return asset('images/placeholder-outlet.jpg');
            }

            // Get fasilitas array
            $fasilitasArray = [];
            if ($outlet->fasilitas) {
                $fasilitasArray = array_map('trim', explode(',', $outlet->fasilitas));
            }
            
            // Tambahkan dari boolean fields
            if ($outlet->tersedia_toilet) $fasilitasArray[] = 'Toilet';
            if ($outlet->tersedia_musholla) $fasilitasArray[] = 'Musholla';
            if ($outlet->tersedia_atm) $fasilitasArray[] = 'ATM';
            if ($outlet->tersedia_wifi) $fasilitasArray[] = 'WiFi';
            $fasilitasArray = array_unique($fasilitasArray);

            $html .= '
            <div class="outlet-card" data-city="' . ($outlet->branch ? e($outlet->branch->kota) : '') . '">
                <div class="outlet-card-inner">
                    <div class="card-header">
                        ' . e($outlet->nama_outlet) . '
                    </div>
                    <div class="card-image">
                        <img src="' . e(getOutletImage($outlet)) . '"
                             alt="' . e($outlet->nama_outlet) . '"
                             class="outlet-img"
                             onerror="this.onerror=null;this.src=\'' . asset('images/placeholder-outlet.jpg') . '\'">
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-store"></i> CABANG
                                </div>
                                <div class="info-value">
                                    ' . e($outlet->branch ? $outlet->branch->nama_cabang : 'Tidak diketahui') . '
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-city"></i> KOTA
                                </div>
                                <div class="info-value">
                                    ' . e($outlet->branch ? $outlet->branch->kota : 'Tidak diketahui') . '
                                </div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">
                                    <i class="fas fa-map-marker-alt"></i> ALAMAT
                                </div>
                                <div class="info-value address">
                                    ' . e($outlet->alamat_lengkap ?? $outlet->alamat) . '
                                </div>
                            </div>
                        </div>
                        <div class="contact-hours">
                            <div class="contact-hours-grid">
                                <div class="contact-item">
                                    <div class="contact-label">
                                        <i class="fas fa-phone"></i> TELEPON
                                    </div>
                                    <div class="contact-value">
                                        ' . e($outlet->telepon ?? '-') . '
                                    </div>
                                </div>
                                <div class="hours-item">
                                    <div class="hours-label">
                                        <i class="fas fa-clock"></i> JAM OPERASIONAL
                                    </div>
                                    <div class="hours-value">
                                        ' . e($outlet->jam_operasional ?? '24 Jam') . '
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn-detail" data-outlet-id="' . $outlet->id . '">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                    </div>
                </div>
            </div>';
        }

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $outlets->count(),
            'total' => $totalOutlets,
            'allLoaded' => !$hasMore
        ]);
    }

    public function getDetail(Request $request)
    {
        $id = $request->input('id');
        
        $outlet = Outlet::with('branch')->find($id);
        
        if (!$outlet) {
            return response()->json(['error' => 'Outlet not found'], 404);
        }

        // Helper function untuk gambar outlet
        function getOutletImageDetail($outlet) {
            if (!empty($outlet->foto_outlet)) {
                if (Str::startsWith($outlet->foto_outlet, ['http://', 'https://'])) {
                    return $outlet->foto_outlet;
                }
                
                $filename = basename($outlet->foto_outlet);
                $publicPath = 'images/outlets/' . $filename;

                if (file_exists(public_path($publicPath))) {
                    return asset($publicPath);
                }

                if (file_exists(public_path($outlet->foto_outlet))) {
                    return asset($outlet->foto_outlet);
                }
            }

            return asset('images/placeholder-outlet.jpg');
        }

        // Get fasilitas array
        $fasilitasArray = [];
        if ($outlet->fasilitas) {
            $fasilitasArray = array_map('trim', explode(',', $outlet->fasilitas));
        }
        
        // Tambahkan dari boolean fields
        if ($outlet->tersedia_toilet) $fasilitasArray[] = 'Toilet';
        if ($outlet->tersedia_musholla) $fasilitasArray[] = 'Musholla';
        if ($outlet->tersedia_atm) $fasilitasArray[] = 'ATM';
        if ($outlet->tersedia_wifi) $fasilitasArray[] = 'WiFi';
        $fasilitasArray = array_unique($fasilitasArray);

        return response()->json([
            'id' => $outlet->id,
            'nama' => $outlet->nama_outlet,
            'cabang' => $outlet->branch ? $outlet->branch->nama_cabang : 'Tidak diketahui',
            'kota' => $outlet->branch ? $outlet->branch->kota : 'Tidak diketahui',
            'alamat' => $outlet->alamat_lengkap ?? $outlet->alamat,
            'telepon' => $outlet->telepon,
            'email' => $outlet->email,
            'fasilitas' => $fasilitasArray,
            'jam_operasional' => $outlet->jam_operasional ?? '24 Jam',
            'tipe_outlet' => $outlet->tipe_outlet, // PAKAI tipe_outlet saja
            'zona_pelayanan' => $outlet->zona_pelayanan,
            'kapasitas_parkir' => $outlet->kapasitas_parkir,
            'gambar' => getOutletImageDetail($outlet),
        ]);
    }

    public function update(Request $request, $id)
    {
        // \Log::info('OutletController update called', ['id' => $id, 'all_data' => $request->all()]);
        
        // PASTIKAN VALIDASI MENGUNAKAN tipe_outlet BUKAN tipe_lokasi
        $validated = $request->validate([
            'nama_outlet' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'alamat_lengkap' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'nullable|email',
            'tipe_outlet' => 'required|in:mall,pusat_perbelanjaan,perkantoran,stasiun,bandara,jalan_utama,kawasan_komersial,perumahan,kampus,rumah_sakit,hotel,wisata,pusat_kota,lainnya',
            'kapasitas_parkir' => 'nullable|integer',
            'zona_pelayanan' => 'nullable|string',
            'jam_operasional' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif,maintenance',
            'foto_outlet' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // \Log::info('Validation passed', ['tipe_outlet' => $request->tipe_outlet]);

        $outlet = Outlet::findOrFail($id);
        
        // \Log::info('Found outlet', [
        //     'current_tipe_outlet' => $outlet->tipe_outlet,
        //     'new_tipe_outlet' => $request->tipe_outlet
        // ]);
        
        // Handle file upload
        if ($request->hasFile('foto_outlet')) {
            // Hapus foto lama jika ada
            if ($outlet->foto_outlet && file_exists(public_path($outlet->foto_outlet))) {
                unlink(public_path($outlet->foto_outlet));
            }

            // Upload foto baru
            $file = $request->file('foto_outlet');
            $filename = time() . '_' . Str::slug($outlet->nama_outlet) . '.' . $file->getClientOriginalExtension();
            
            $path = 'images/outlets/';
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0777, true);
            }
            
            $file->move(public_path($path), $filename);
            $outlet->foto_outlet = $path . $filename;
        }

        // Handle fasilitas
        if ($request->has('fasilitas')) {
            $fasilitas = array_unique($request->fasilitas);
            $outlet->fasilitas = implode(', ', $fasilitas);
            
            $outlet->tersedia_toilet = in_array('Toilet', $fasilitas);
            $outlet->tersedia_musholla = in_array('Musholla', $fasilitas);
            $outlet->tersedia_atm = in_array('ATM', $fasilitas);
            $outlet->tersedia_wifi = in_array('WiFi', $fasilitas);
        } else {
            $outlet->fasilitas = null;
            $outlet->tersedia_toilet = false;
            $outlet->tersedia_musholla = false;
            $outlet->tersedia_atm = false;
            $outlet->tersedia_wifi = false;
        }

        // Update field lainnya - PERBAIKI: Gunakan tipe_outlet
        $outlet->nama_outlet = $request->nama_outlet;
        $outlet->branch_id = $request->branch_id;
        $outlet->alamat_lengkap = $request->alamat_lengkap;
        $outlet->telepon = $request->telepon;
        $outlet->email = $request->email;
        
        // INI YANG DIPERBAIKI: tipe_lokasi -> tipe_outlet
        $outlet->tipe_outlet = $request->tipe_outlet;
        
        $outlet->kapasitas_parkir = $request->kapasitas_parkir;
        $outlet->zona_pelayanan = $request->zona_pelayanan;
        $outlet->jam_operasional = $request->jam_operasional;
        $outlet->status = $request->status;
        
        $outlet->save();

        // \Log::info('Outlet saved successfully', [
        //     'id' => $outlet->id,
        //     'tipe_outlet' => $outlet->tipe_outlet
        // ]);

        return redirect()->route('admin.outletperusahaan')
            ->with('success', 'Outlet berhasil diupdate!');
    }
}