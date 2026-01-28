<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Branch;
use App\Models\User;
use App\Models\Rute;
use App\Models\Shuttle;
use App\Models\MLayanan;
use App\Models\Promo;
use App\Models\Outlet;
use App\Models\Artikel;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Process admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Check if user has admin role
            $user = Auth::guard('admin')->user();
            if (!$user->hasAnyRole(['admin_pusat', 'admin_cabang'])) {
                Auth::guard('admin')->logout();
                return back()->withErrors(['email' => 'Anda tidak memiliki akses admin.']);
            }

            // Check if branch admin has branch assignment
            if ($user->hasRole('admin_cabang') && !$user->branch_id) {
                Auth::guard('admin')->logout();
                return back()->withErrors(['email' => 'Akun admin cabang belum ditugaskan ke cabang manapun.']);
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->onlyInput('email');
    }

    // Master Data Methods
    public function profilePerusahaan()
    {
        return view('admin.profileperusahaan');
    }

    public function kontak()
    {
        return view('admin.kontak');
    }

    public function kontakPerusahaan()
    {
        $kontak = \App\Models\MMasterKontak::getFirstOrCreate();
        return view('admin.kontakperusahaan', compact('kontak'));
    }

    public function updateKontakPerusahaan(Request $request, $id)
    {
        try {
            $kontak = \App\Models\MMasterKontak::findOrFail($id);
            
            $validated = $request->validate([
                'nama_perusahaan' => 'required|string|max:255',
                'deskripsi_singkat' => 'required|string|max:500',
                'email_utama' => 'required|email|max:255',
                'email_dukungan' => 'nullable|email|max:255',
                'telepon_utama' => 'required|string|max:20',
                'telepon_dukungan' => 'nullable|string|max:20',
                'alamat_kantor_pusat' => 'required|string|max:500',
                'facebook_url' => 'nullable|url|max:255',
                'instagram_url' => 'nullable|url|max:255',
                'twitter_url' => 'nullable|url|max:255',
                'jam_operasional' => 'required|string',
                'link_kebijakan_privasi' => 'nullable|url|max:255',
                'link_syarat_ketentuan' => 'nullable|url|max:255',
                'status' => 'required|in:active,inactive',
            ]);

            // Parse jam_operasional jika dalam format JSON string
            if (isset($validated['jam_operasional'])) {
                // Jika sudah string JSON, langsung gunakan
                if (!is_array($validated['jam_operasional'])) {
                    // Coba decode untuk validasi
                    $decoded = json_decode($validated['jam_operasional'], true);
                    if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                        // Jika bukan JSON valid, buat format default
                        $validated['jam_operasional'] = json_encode([
                            ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                            ['hari' => 'Sabtu', 'jam' => '08:00 - 15:00'],
                            ['hari' => 'Minggu', 'jam' => 'Tutup']
                        ]);
                    }
                }
            }

            $kontak->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kontak perusahaan berhasil diperbarui.',
                    'data' => $kontak
                ]);
            }

            return redirect()->route('admin.kontakperusahaan')
                ->with('success', 'Kontak perusahaan berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error('Error updating kontak perusahaan: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function pusat()
    {
        return view('admin.pusat');
    }

    public function cabangPerusahaan(Request $request)
    {
        // Get branches with filtering
        $query = Branch::query();

        // Apply filters
        if ($request->filled('kota')) {
            $query->where('kota', $request->kota);
        }

        if ($request->filled('nama_cabang')) {
            $query->where('nama_cabang', 'like', '%' . $request->nama_cabang . '%');
        }

        if ($request->filled('kode_cabang')) {
            $query->where('kode_cabang', 'like', '%' . $request->kode_cabang . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status == 'aktif' ? 'aktif' : 'nonaktif');
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_cabang', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_cabang', 'like', '%' . $request->search . '%')
                  ->orWhere('kota', 'like', '%' . $request->search . '%');
            });
        }

        $branches = $query->paginate(10);

        // Get summary data
        $totalBranches = Branch::count();
        $activeBranches = Branch::where('status', 'aktif')->count();
        $inactiveBranches = Branch::where('status', 'nonaktif')->count();

        // Get unique cities for filter dropdown
        $cities = Branch::distinct()->pluck('kota')->filter()->sort()->values();

        return view('admin.cabangperusahaan', compact(
            'branches',
            'totalBranches',
            'activeBranches',
            'inactiveBranches',
            'cities'
        ));
    }

    // Branch CRUD Methods
    public function createBranch()
    {
        return view('admin.cabang-create');
    }

    public function storeBranch(Request $request)
    {
        $request->validate([
            'kode_cabang' => 'required|string|max:10|unique:branches,kode_cabang',
            'nama_cabang' => 'required|string|max:255',
            'kota' => 'required|string|max:100',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:branches,email',
            'koordinat_gps' => 'nullable|string|max:50',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();

        Branch::create($data);

        return redirect()->route('admin.cabangperusahaan')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function editBranch($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.cabang-edit', compact('branch'));
    }

    public function updateBranch(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'kode_cabang' => 'required|string|max:10|unique:branches,kode_cabang,' . $id,
            'nama_cabang' => 'required|string|max:255',
            'kota' => 'required|string|max:100',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:branches,email,' . $id,
            'koordinat_gps' => 'nullable|string|max:50',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $branch->update($request->all());

        return redirect()->route('admin.cabangperusahaan')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroyBranch($id)
    {
        $branch = Branch::findOrFail($id);

        // Check if branch has outlets
        if ($branch->outlets()->count() > 0) {
            return redirect()->route('admin.cabangperusahaan')->with('error', 'Cabang tidak dapat dihapus karena masih memiliki outlet.');
        }

        $branch->deleted_by = auth()->id();
        $branch->save();

        $branch->delete();

        return redirect()->route('admin.cabangperusahaan')->with('success', 'Cabang berhasil dihapus.');
    }

    public function getBranch($id)
    {
        $branch = Branch::findOrFail($id);
        return response()->json($branch);
    }

    // ========================= OUTLET CRUD METHODS =========================

    public function outletPerusahaan(Request $request)
    {
        // Get outlets with filtering
        $query = Outlet::with('branch');

        // Apply filters
        if ($request->filled('kota')) {
            $query->whereHas('branch', function($q) use ($request) {
                $q->where('kota', $request->kota);
            });
        }

        if ($request->filled('nama')) {
            $query->where('nama_outlet', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('kode')) {
            $query->where('kode_outlet', 'like', '%' . $request->kode . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_outlet', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_outlet', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        $outlets = $query->paginate(10);

        // Get summary data
        $totalOutlets = Outlet::count();
        $activeOutlets = Outlet::where('status', 'aktif')->count();
        $inactiveOutlets = Outlet::where('status', 'nonaktif')->count();
        $maintenanceOutlets = Outlet::where('status', 'maintenance')->count();

        // Get unique cities for filter dropdown
        $cities = Branch::distinct()->pluck('kota')->filter()->sort()->values();

        return view('admin.outletperusahaan', compact(
            'outlets',
            'totalOutlets',
            'activeOutlets',
            'inactiveOutlets',
            'maintenanceOutlets',
            'cities'
        ));
    }

    public function createOutlet()
    {
        $branches = Branch::where('status', 'aktif')->get();
        return view('admin.outletperusahaan-create', compact('branches'));
    }

    public function storeOutlet(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'nama_outlet' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'tipe_outlet' => 'required|in:regular,premium,express',
            'kapasitas_parkir' => 'nullable|integer|min:0',
            'zona_pelayanan' => 'nullable|string|max:100',
            'jam_operasional' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif,maintenance',
        ]);

        // Generate kode outlet otomatis
        $lastOutlet = Outlet::orderBy('id', 'desc')->first();
        $nextNumber = $lastOutlet ? intval(substr($lastOutlet->kode_outlet, 2)) + 1 : 1;
        $kodeOutlet = 'OT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Get branch data for kota
        $branch = Branch::find($request->branch_id);

        // Handle fasilitas array
        $fasilitas = $request->has('fasilitas') ? implode(',', $request->fasilitas) : null;

        Outlet::create([
            'branch_id' => $request->branch_id,
            'kode_outlet' => $kodeOutlet,
            'nama_outlet' => $request->nama_outlet,
            'alamat_lengkap' => $request->alamat_lengkap,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'kota' => $branch->kota,
            'tipe_outlet' => $request->tipe_outlet,
            'kapasitas_parkir' => $request->kapasitas_parkir,
            'zona_pelayanan' => $request->zona_pelayanan,
            'jam_operasional' => $request->jam_operasional,
            'fasilitas' => $fasilitas,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.outletperusahaan')->with('success', 'Outlet berhasil ditambahkan.');
    }

    public function editOutlet($id)
    {
        $outlet = Outlet::findOrFail($id);
        $branches = Branch::where('status', 'aktif')->get();

        // Parse fasilitas string ke array
        $fasilitasArray = $outlet->fasilitas ? explode(',', $outlet->fasilitas) : [];

        return view('admin.outletperusahaan-edit', compact('outlet', 'branches', 'fasilitasArray'));
    }

    public function updateOutlet(Request $request, $id)
    {
        $outlet = Outlet::findOrFail($id);

        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'nama_outlet' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'tipe_outlet' => 'required|in:regular,premium,express',
            'kapasitas_parkir' => 'nullable|integer|min:0',
            'zona_pelayanan' => 'nullable|string|max:100',
            'jam_operasional' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif,maintenance',
        ]);

        // Get branch data for kota
        $branch = Branch::find($request->branch_id);

        // Handle fasilitas array
        $fasilitas = $request->has('fasilitas') ? implode(',', $request->fasilitas) : null;

        $outlet->update([
            'branch_id' => $request->branch_id,
            'nama_outlet' => $request->nama_outlet,
            'alamat_lengkap' => $request->alamat_lengkap,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'kota' => $branch->kota,
            'tipe_outlet' => $request->tipe_outlet,
            'kapasitas_parkir' => $request->kapasitas_parkir,
            'zona_pelayanan' => $request->zona_pelayanan,
            'jam_operasional' => $request->jam_operasional,
            'fasilitas' => $fasilitas,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.outletperusahaan')->with('success', 'Outlet berhasil diperbarui.');
    }

    public function destroyOutlet($id)
    {
        $outlet = Outlet::findOrFail($id);

        // Check if outlet has related data before deleting
        // Contoh: if ($outlet->transactions()->count() > 0) {
        //     return redirect()->route('admin.outletperusahaan')->with('error', 'Outlet tidak dapat dihapus karena masih memiliki transaksi.');
        // }

        $outlet->delete();

        return redirect()->route('admin.outletperusahaan')->with('success', 'Outlet berhasil dihapus.');
    }

    public function showOutlet($id)
    {
        $outlet = Outlet::with('branch')->findOrFail($id);

        // Parse fasilitas string ke array
        $fasilitasArray = $outlet->fasilitas ? explode(',', $outlet->fasilitas) : [];

        return view('admin.outletperusahaan-show', compact('outlet', 'fasilitasArray'));
    }

    // ========================= END OUTLET CRUD =========================

    public function promo(Request $request)
    {
        // Get promos with filtering
        $query = Promo::query();

        // Apply filters
        if ($request->filled('nama_promo')) {
            $query->where('nama_promo', 'like', '%' . $request->nama_promo . '%');
        }

        if ($request->filled('kode_promo')) {
            $query->where('kode_promo', 'like', '%' . $request->kode_promo . '%');
        }

        if ($request->filled('jenis_diskon')) {
            $query->where('jenis_diskon', $request->jenis_diskon);
        }

        if ($request->filled('kategori_promo')) {
            $query->where('kategori_promo', $request->kategori_promo);
        }

        if ($request->filled('tipe_promo')) {
            $query->where('tipe_promo', $request->tipe_promo);
        }

        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->where('status', true)
                      ->whereDate('tanggal_mulai', '<=', now())
                      ->whereDate('tanggal_berakhir', '>=', now());
            } elseif ($request->status == 'nonaktif') {
                $query->where('status', false);
            } elseif ($request->status == 'expired') {
                $query->where(function($q) {
                    $q->where('status', true)
                      ->whereDate('tanggal_berakhir', '<', now());
                });
            }
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_promo', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_promo', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        $promos = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get summary data
        $totalPromo = Promo::count();
        $activePromo = Promo::where('status', true)
                           ->whereDate('tanggal_mulai', '<=', now())
                           ->whereDate('tanggal_berakhir', '>=', now())
                           ->count();
        $inactivePromo = Promo::where('status', false)->count();
        $expiredPromo = Promo::where('status', true)
                            ->whereDate('tanggal_berakhir', '<', now())
                            ->count();

        // Calculate ongoing promos (aktif dan belum expired)
        $ongoingPromo = $activePromo;

        // Get unique values for filter dropdowns
        $discountTypes = Promo::distinct()->pluck('jenis_diskon')->filter()->sort()->values();
        $categories = Promo::distinct()->pluck('kategori_promo')->filter()->sort()->values();
        $promoTypes = Promo::distinct()->pluck('tipe_promo')->filter()->sort()->values();

        return view('admin.promo', compact(
            'promos',
            'totalPromo',
            'activePromo',
            'inactivePromo',
            'expiredPromo',
            'ongoingPromo',
            'discountTypes',
            'categories',
            'promoTypes'
        ));
    }

    public function createPromo()
    {
        return view('admin.promo-create');
    }

    /**
     * Store new promo
     */
    public function storePromo(Request $request)
    {
        $request->validate([
            'kode_promo' => 'required|string|max:50|unique:promo,kode_promo',
            'nama_promo' => 'required|string|max:255',
            'jenis_diskon' => 'required|in:persentase,nominal',
            'nilai_diskon' => 'required|numeric|min:0',
            'maksimal_diskon' => 'nullable|numeric|min:0',
            'minimal_pembelian' => 'nullable|numeric|min:0',
            'min_tiket' => 'nullable|integer|min:1',
            'kategori_promo' => 'required|in:umum,keluarga,membership',
            'tipe_promo' => 'required|in:all,shuttle,paket,sewa',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'nullable|integer|min:1',
            'deskripsi' => 'required|string',
            'pesan_error' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'khusus_member' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->except('gambar', 'khusus_member', 'status');
        $data['khusus_member'] = $request->has('khusus_member') ? 1 : 0;
        $data['status'] = $request->has('status') ? 1 : 0;
        $data['terpakai'] = 0;

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('promo_images', 'public');
            $data['gambar'] = $imagePath;
        }

        Promo::create($data);

        return redirect()->route('admin.promo')->with('success', 'Promo berhasil ditambahkan.');
    }

    /**
     * Show edit promo form
     */
    public function editPromo($id)
    {
        $promo = Promo::findOrFail($id);
        return view('admin.promo-edit', compact('promo'));
    }

    /**
     * Update promo
     */
    public function updatePromo(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        $request->validate([
            'kode_promo' => 'required|string|max:50|unique:promo,kode_promo,' . $id,
            'nama_promo' => 'required|string|max:255',
            'jenis_diskon' => 'required|in:persentase,nominal',
            'nilai_diskon' => 'required|numeric|min:0',
            'maksimal_diskon' => 'nullable|numeric|min:0',
            'minimal_pembelian' => 'nullable|numeric|min:0',
            'min_tiket' => 'nullable|integer|min:1',
            'kategori_promo' => 'required|in:umum,keluarga,membership',
            'tipe_promo' => 'required|in:all,shuttle,paket,sewa',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'nullable|integer|min:1',
            'deskripsi' => 'required|string',
            'pesan_error' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'khusus_member' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->except('gambar', 'khusus_member', 'status');
        $data['khusus_member'] = $request->has('khusus_member') ? 1 : 0;
        $data['status'] = $request->has('status') ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($promo->gambar && Storage::disk('public')->exists($promo->gambar)) {
                Storage::disk('public')->delete($promo->gambar);
            }

            $imagePath = $request->file('gambar')->store('promo_images', 'public');
            $data['gambar'] = $imagePath;
        }

        $promo->update($data);

        return redirect()->route('admin.promo')->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Show promo detail
     */
    public function showPromo($id)
    {
        $promo = Promo::findOrFail($id);

        // Calculate status
        $now = now();
        $startDate = \Carbon\Carbon::parse($promo->tanggal_mulai);
        $endDate = \Carbon\Carbon::parse($promo->tanggal_berakhir);

        $statusClass = 'status-nonaktif';
        $statusText = 'Nonaktif';

        if($promo->status) {
            if($now->between($startDate, $endDate)) {
                $statusClass = 'status-aktif';
                $statusText = 'Aktif';
            } else if($now->gt($endDate)) {
                $statusClass = 'status-expired';
                $statusText = 'Expired';
            }
        }

        return view('admin.promo-show', compact('promo', 'statusClass', 'statusText'));
    }

    /**
     * Delete promo
     */
    public function destroyPromo($id)
    {
        $promo = Promo::findOrFail($id);

        // Check if promo has been used
        if ($promo->terpakai > 0) {
            return redirect()->route('admin.promo')->with('error', 'Promo tidak dapat dihapus karena sudah digunakan.');
        }

        // Delete image if exists
        if ($promo->gambar && Storage::disk('public')->exists($promo->gambar)) {
            Storage::disk('public')->delete($promo->gambar);
        }

        $promo->delete();

        return redirect()->route('admin.promo')->with('success', 'Promo berhasil dihapus.');
    }

    public function armada(Request $request)
    {
        // Get shuttles with filtering
        $query = Shuttle::with('layanan');

        // Apply filters
        if ($request->filled('merk')) {
            $query->where('merk', 'like', '%' . $request->merk . '%');
        }

        if ($request->filled('tipe')) {
            $query->where('tipe_shuttle', $request->tipe);
        }

        if ($request->filled('warna')) {
            $query->where('warna', $request->warna);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('kode', 'like', '%' . $request->search . '%')
                  ->orWhere('merk', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_polisi', 'like', '%' . $request->search . '%');
            });
        }

        $shuttles = $query->paginate(10);

        // Get summary data
        $totalShuttles = Shuttle::count();
        $activeShuttles = Shuttle::where('status', 'aktif')->count();
        $inactiveShuttles = Shuttle::where('status', 'tidak-aktif')->count();
        $serviceShuttles = Shuttle::where('status', 'perbaikan')->count();

        // Get unique brands/types/colors for filter dropdown
        $brands = Shuttle::distinct()->pluck('merk')->filter()->sort()->values();
        $types = Shuttle::distinct()->pluck('tipe_shuttle')->filter()->sort()->values();
        $colors = Shuttle::distinct()->pluck('warna')->filter()->sort()->values();

        return view('admin.armada', compact(
            'shuttles',
            'totalShuttles',
            'activeShuttles',
            'inactiveShuttles',
            'serviceShuttles',
            'brands',
            'types',
            'colors'
        ));
    }

    // Shuttle CRUD Methods
    public function createShuttle()
    {
        $layanans = \App\Models\MLayanan::all();
        return view('admin.armada-create', compact('layanans'));
    }

    public function storeShuttle(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:m_layanan,id_layanan',
            'kode' => 'required|string|max:20|unique:shuttles,kode',
            'nama_shuttle' => 'required|string|max:255',
            'merk' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'tipe_shuttle' => 'required|string|max:50',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'warna' => 'required|string|max:50',
            'kapasitas_kursi' => 'required|integer|min:1|max:50',
            'nomor_polisi' => 'required|string|max:20|unique:shuttles,nomor_polisi',
            'status' => 'required|in:aktif,tidak-aktif,perbaikan',
            'jenis_kepemilikan' => 'required|in:milik-perusahaan,sewa,vendor',
            'tanggal_masuk' => 'required|date',
        ]);

        $data = $request->all();

        // Set defaults
        $data['total_kursi'] = $request->kapasitas_kursi;
        $data['fasilitas'] = $request->fasilitas ?? 'AC Double,WiFi High Speed,Charger USB-C';
        $data['layout_kursi'] = \App\Models\KursiTerpesan::generateLayoutKursi($request->kapasitas_kursi);
        $data['created_by'] = auth()->id();

        // Handle kelengkapan array
        if ($request->has('kelengkapan') && is_array($request->kelengkapan)) {
            $data['kelengkapan'] = $request->kelengkapan;
        }

        Shuttle::create($data);

        return redirect()->route('admin.armada')->with('success', 'Armada berhasil ditambahkan.');
    }

    public function editShuttle($id)
    {
        $shuttle = Shuttle::findOrFail($id);
        $layanans = \App\Models\MLayanan::all();
        return view('admin.armada-edit', compact('shuttle', 'layanans'));
    }

    public function updateShuttle(Request $request, $id)
    {
        $shuttle = Shuttle::findOrFail($id);

        $request->validate([
            'layanan_id' => 'required|exists:m_layanan,id_layanan',
            'kode' => 'required|string|max:20|unique:shuttles,kode,' . $id,
            'nama_shuttle' => 'required|string|max:255',
            'merk' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'tipe_shuttle' => 'required|string|max:50',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'warna' => 'required|string|max:50',
            'kapasitas_kursi' => 'required|integer|min:1|max:50',
            'nomor_polisi' => 'required|string|max:20|unique:shuttles,nomor_polisi,' . $id,
            'status' => 'required|in:aktif,tidak-aktif,perbaikan',
            'jenis_kepemilikan' => 'required|in:milik-perusahaan,sewa,vendor',
            'tanggal_masuk' => 'required|date',
            'nilai_asset' => 'nullable|numeric',
            'fasilitas' => 'nullable|string',
            'no_stnk' => 'nullable|string|max:50',
            'masa_stnk' => 'nullable|date',
            'no_kir' => 'nullable|string|max:50',
            'masa_kir' => 'nullable|date',
            'kelengkapan' => 'nullable|array',
        ]);

        $data = $request->all();

        // Update total_kursi jika kapasitas berubah
        if ($request->kapasitas_kursi != $shuttle->kapasitas_kursi) {
            $data['total_kursi'] = $request->kapasitas_kursi;
            $data['layout_kursi'] = \App\Models\KursiTerpesan::generateLayoutKursi($request->kapasitas_kursi);
        }

        // Handle kelengkapan array
        if ($request->has('kelengkapan')) {
            $kelengkapanArray = [];
            foreach ($request->kelengkapan as $item) {
                if (isset($item['name']) && !empty($item['name'])) {
                    $kelengkapanArray[] = [
                        'name' => $item['name'],
                        'checked' => isset($item['checked']) ? true : false
                    ];
                }
            }
            $data['kelengkapan'] = $kelengkapanArray;
        }

        $shuttle->update($data);

        return redirect()->route('admin.armada')->with('success', 'Armada berhasil diperbarui.');
    }

    public function destroyShuttle($id)
    {
        $shuttle = Shuttle::findOrFail($id);

        // Check if shuttle has active bookings
        if ($shuttle->jadwals()->whereHas('kursiTerpesan.pemesanan', function($query) {
            $query->whereNotIn('status', ['dibatalkan', 'expired']);
        })->exists()) {
            return redirect()->route('admin.armada')->with('error', 'Armada tidak dapat dihapus karena masih memiliki pemesanan aktif.');
        }

        $shuttle->deleted_by = auth()->id();
        $shuttle->save();

        $shuttle->delete();

        return redirect()->route('admin.armada')->with('success', 'Armada berhasil dihapus.');
    }

    public function showShuttle($id)
    {
        $shuttle = Shuttle::with('layanan')->findOrFail($id);
        return view('admin.armada-detail', compact('shuttle'));
    }

    public function driver()
    {
        return view('admin.driver');
    }

    public function pegawai()
    {
        return view('admin.pegawai');
    }

    public function rute(Request $request)
    {
        // Get rutes with filtering
        $query = Rute::with('layanan');

        // Apply filters
        if ($request->filled('kota_asal')) {
            $query->where('kota_asal', 'like', '%' . $request->kota_asal . '%');
        }

        if ($request->filled('kota_tujuan')) {
            $query->where('kota_tujuan', 'like', '%' . $request->kota_tujuan . '%');
        }

        if ($request->filled('tipe_rute')) {
            // This would need to be adjusted based on your database structure
            // For now, we'll search in nama_rute
            $query->where('nama_rute', 'like', '%' . $request->tipe_rute . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('kode_rute', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_rute', 'like', '%' . $request->search . '%')
                  ->orWhere('kota_asal', 'like', '%' . $request->search . '%')
                  ->orWhere('kota_tujuan', 'like', '%' . $request->search . '%');
            });
        }

        $rutes = $query->paginate(10);

        // Get summary data
        $totalRute = Rute::count();
        $activeRute = Rute::where('status', 'aktif')->count();
        $inactiveRute = Rute::where('status', 'nonaktif')->count();

        // Get unique values for filter dropdowns
        $kotaAsalList = Rute::distinct()->pluck('kota_asal')->filter()->sort()->values();
        $kotaTujuanList = Rute::distinct()->pluck('kota_tujuan')->filter()->sort()->values();
        $layananList = MLayanan::all();

        return view('admin.rute', compact(
            'rutes',
            'totalRute',
            'activeRute',
            'inactiveRute',
            'kotaAsalList',
            'kotaTujuanList',
            'layananList'
        ));
    }

    // Rute CRUD Methods
    public function createRute()
    {
        $layanans = MLayanan::all();
        return view('admin.rute-create', compact('layanans'));
    }

    public function storeRute(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:m_layanan,id_layanan',
            'kode_rute' => 'required|string|max:20|unique:rutes,kode_rute',
            'nama_rute' => 'required|string|max:255',
            'kota_asal' => 'required|string|max:100',
            'kota_tujuan' => 'required|string|max:100',
            'durasi' => 'required|string|max:10',
            'jarak' => 'required|numeric|min:0',
            'harga_dasar' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'rute_pemberhentian' => 'nullable|string',
        ]);

        $data = $request->all();

        // Format durasi if needed
        if (!str_contains($data['durasi'], ':')) {
            $data['durasi'] = $data['durasi'] . ':00';
        }

        Rute::create($data);

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil ditambahkan.');
    }

    public function editRute($id)
    {
        $rute = Rute::findOrFail($id);
        $layanans = MLayanan::all();
        return view('admin.rute-edit', compact('rute', 'layanans'));
    }

    public function updateRute(Request $request, $id)
    {
        $rute = Rute::findOrFail($id);

        $request->validate([
            'layanan_id' => 'required|exists:m_layanan,id_layanan',
            'kode_rute' => 'required|string|max:20|unique:rutes,kode_rute,' . $id,
            'nama_rute' => 'required|string|max:255',
            'kota_asal' => 'required|string|max:100',
            'kota_tujuan' => 'required|string|max:100',
            'durasi' => 'required|string|max:10',
            'jarak' => 'required|numeric|min:0',
            'harga_dasar' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'rute_pemberhentian' => 'nullable|string',
        ]);

        $data = $request->all();

        // Format durasi if needed
        if (!str_contains($data['durasi'], ':')) {
            $data['durasi'] = $data['durasi'] . ':00';
        }

        $rute->update($data);

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil diperbarui.');
    }

    public function destroyRute($id)
    {
        try {
            $rute = Rute::findOrFail($id);

            // Check if rute has active schedules
            if ($rute->jadwals()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rute tidak dapat dihapus karena masih memiliki jadwal aktif.'
                ], 400);
            }

            $rute->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rute berhasil dihapus.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showRute($id)
    {
        $rute = Rute::with('layanan')->findOrFail($id);

        // Parse pemberhentian
        $pemberhentian = [];
        if ($rute->rute_pemberhentian) {
            $pemberhentian = json_decode($rute->rute_pemberhentian, true);
        }

        return view('admin.rute-detail', compact('rute', 'pemberhentian'));
    }

    public function getRute($id)
    {
        $rute = Rute::with('layanan')->findOrFail($id);
        return response()->json($rute);
    }

    // Transaksi Methods
    public function smartsendTransaksi()
    {
        return view('admin.transaksi.smartsend');
    }

    public function perjalanan()
    {
        return view('admin.transaksi.perjalanan');
    }

    public function armadaTransaksi()
    {
        return view('admin.transaksi.armada');
    }


    // SmartSend Methods
    public function smartsendTiket()
    {
        return view('admin.smartsend-tiket');
    }

    public function smartsendPerjalanan()
    {
        return view('admin.smartsend-perjalanan');
    }

    public function smartsendArmada()
    {
        return view('admin.smartsend-armada');
    }

    // SmartRent Method
    public function smartrent()
    {
        return view('admin.smartrent');
    }

    // Laporan Method
    public function laporan()
    {
        return view('admin.laporan');
    }

    // Pengaturan Methods
    public function user()
    {
        return view('admin.user');
    }

    public function menu()
    {
        return view('admin.menu');
    }

    /**
     * Proses logout admin
     */
    public function logout(Request $request)
    {
        try {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('customer.login');

        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard');
        }
    }

    // Artikel Management Methods
    public function artikel(Request $request)
    {
        // Get artikels with filtering
        $query = Artikel::query();

        // Apply filters
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('penulis')) {
            $query->where('penulis', 'like', '%' . $request->penulis . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'publik') {
                $query->where('status', true);
            } elseif ($request->status === 'draft') {
                $query->where('status', false);
            }
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_publikasi', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_publikasi', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
            });
        }

        $artikels = $query->orderBy('tanggal_publikasi', 'desc')->paginate(10);

        // Get summary data
        $totalArtikel = Artikel::count();
        $artikelAktif = Artikel::where('status', true)->count();
        $artikelDraft = Artikel::where('status', false)->count();

        // Get unique values for filter dropdowns
        $kategoriList = Artikel::distinct()->pluck('kategori')->filter()->sort()->values();
        $penulisList = Artikel::distinct()->pluck('penulis')->filter()->sort()->values();

        return view('admin.artikel', compact(
            'artikels',
            'totalArtikel',
            'artikelAktif',
            'artikelDraft',
            'kategoriList',
            'penulisList'
        ));
    }

    /**
     * Show create artikel form
     */
    public function createArtikel()
    {
        return view('admin.artikel-create');
    }

    /**
     * Store new artikel
     */
    public function storeArtikel(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|string|max:100',
            'penulis' => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->except('gambar', 'status');
        $data['status'] = $request->has('status') ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('artikel_images', 'public');
            $data['gambar'] = $imagePath;
        }

        Artikel::create($data);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Show edit artikel form
     */
    public function editArtikel($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.artikel-edit', compact('artikel'));
    }

    /**
     * Update artikel
     */
    public function updateArtikel(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|string|max:100',
            'penulis' => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->except('gambar', 'status');
        $data['status'] = $request->has('status') ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
                Storage::disk('public')->delete($artikel->gambar);
            }

            $imagePath = $request->file('gambar')->store('artikel_images', 'public');
            $data['gambar'] = $imagePath;
        }

        $artikel->update($data);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Show artikel detail
     */
    public function showArtikel($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.artikel-show', compact('artikel'));
    }

    /**
     * Delete artikel
     */
    public function destroyArtikel($id)
    {
        $artikel = Artikel::findOrFail($id);

        // Delete image if exists
        if ($artikel->gambar && Storage::disk('public')->exists($artikel->gambar)) {
            Storage::disk('public')->delete($artikel->gambar);
        }

        $artikel->delete();

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
