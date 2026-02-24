<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Branch;
use App\Models\User;
use App\Models\Rute;
use App\Models\Shuttle;
use App\Models\MLayanan;
use App\Models\Promo;
use App\Models\Outlet;
use App\Models\Artikel;
use App\Models\MasterTarif;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\DetailPenumpang;
use App\Models\Transaksi;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Show admin login form
     */
    public function showLogin()
    {
        return $this->showLoginForm();
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

        \Log::info('Admin login attempt', ['email' => $request->email]);

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Check if user has admin role
            $user = Auth::guard('admin')->user();

            \Log::info('Admin login - user found', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'status' => $user->status,
                'branch_id' => $user->branch_id,
            ]);

            // Check user status
            if ($user->status !== 'active') {
                Auth::guard('admin')->logout();
                \Log::warning('Admin login failed - inactive account', ['email' => $request->email]);
                return back()->withErrors(['email' => 'Akun Anda tidak aktif.']);
            }

            // Check if user has admin role
            if (!$user->hasAnyRole(['admin_pusat', 'admin_cabang', 'operator'])) {
                Auth::guard('admin')->logout();
                \Log::warning('Admin login failed - no admin role', [
                    'email' => $request->email,
                    'roles' => $user->getRoleNames()->toArray()
                ]);
                return back()->withErrors(['email' => 'Anda tidak memiliki akses admin.']);
            }

            // Check if branch admin has branch assignment
            if ($user->hasRole('admin_cabang') && !$user->branch_id) {
                Auth::guard('admin')->logout();
                \Log::warning('Admin login failed - branch admin without branch', ['email' => $request->email]);
                return back()->withErrors(['email' => 'Akun admin cabang belum ditugaskan ke cabang manapun.']);
            }

            \Log::info('Admin login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        \Log::warning('Admin login failed - invalid credentials', ['email' => $request->email]);

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

    /**
     * Show contact form (using MMasterKontak model)
     */
    public function kontakPerusahaan()
    {
        $kontak = MMasterKontak::getDataKontak();
        return view('admin.kontakperusahaan', compact('kontak'));
    }

    /**
     * Admin live view for driver locations (polling-based)
     */
    public function driverLocationsLive()
    {
        return view('admin.driver_locations_live');
    }

    /**
     * Update contact (using MMasterKontak model)
     */
    public function updateKontakPerusahaan(Request $request, $id)
    {
        $kontak = MMasterKontak::findOrFail($id);

        $request->validate([
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
            'jam_operasional' => 'nullable|array',
            'link_kebijakan_privasi' => 'nullable|url|max:255',
            'link_syarat_ketentuan' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        // Handle jam_operasional array
        if ($request->has('jam_operasional') && is_array($request->jam_operasional)) {
            $data['jam_operasional'] = json_encode($request->jam_operasional);
        }

        $kontak->update($data);

        return redirect()->route('admin.kontakperusahaan')->with('success', 'Kontak perusahaan berhasil diperbarui.');
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
            'tipe_outlet' => 'required|in:mall,pusat_perbelanjaan,perkantoran,stasiun,bandara,jalan_utama,kawasan_komersial,perumahan,kampus,rumah_sakit,hotel,wisata,pusat_kota,lainnya',
            'kapasitas_parkir' => 'nullable|integer|min:0',
            'zona_pelayanan' => 'nullable|string|max:100',
            'jam_operasional' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif,maintenance',
            'foto_outlet' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate kode outlet otomatis
        $lastOutlet = Outlet::orderBy('id', 'desc')->first();
        $nextNumber = $lastOutlet ? intval(substr($lastOutlet->kode_outlet, 2)) + 1 : 1;
        $kodeOutlet = 'OT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Get branch data for kota
        $branch = Branch::find($request->branch_id);

        // Handle fasilitas array
        $fasilitas = $request->has('fasilitas') ? implode(', ', $request->fasilitas) : null;

        // Handle file upload
        $fotoPath = null;
        if ($request->hasFile('foto_outlet')) {
            $file = $request->file('foto_outlet');
            $filename = time() . '_' . Str::slug($request->nama_outlet) . '.' . $file->getClientOriginalExtension();

            $path = 'images/outlets/';
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0777, true);
            }

            $file->move(public_path($path), $filename);
            $fotoPath = $path . $filename;
        }

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
            'foto_outlet' => $fotoPath,
            'fasilitas' => $fasilitas,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.outletperusahaan')->with('success', 'Outlet berhasil ditambahkan.');
    }

    public function editOutlet($id)
    {
        $outlet = Outlet::with('branch')->findOrFail($id);
        $branches = Branch::where('status', 'aktif')->get();

        // Parse fasilitas string ke array dengan benar
        $selectedFacilities = [];

        if ($outlet->fasilitas) {
            // Bersihkan string
            $cleanString = trim($outlet->fasilitas, ' "[]\'');
            if (!empty($cleanString)) {
                $facilitiesArray = explode(',', $cleanString);
                foreach ($facilitiesArray as $facility) {
                    $trimmed = trim($facility, ' "[]\'');
                    if (!empty($trimmed) && strtolower($trimmed) !== 'null') {
                        $selectedFacilities[] = $trimmed;
                    }
                }
            }
        }

        // Tambahkan dari boolean fields jika ada
        if ($outlet->tersedia_toilet && !in_array('Toilet', $selectedFacilities)) {
            $selectedFacilities[] = 'Toilet';
        }
        if ($outlet->tersedia_musholla && !in_array('Musholla', $selectedFacilities)) {
            $selectedFacilities[] = 'Musholla';
        }
        if ($outlet->tersedia_atm && !in_array('ATM', $selectedFacilities)) {
            $selectedFacilities[] = 'ATM';
        }
        if ($outlet->tersedia_wifi && !in_array('WiFi', $selectedFacilities)) {
            $selectedFacilities[] = 'WiFi';
        }

        return view('admin.outletperusahaan-edit', compact('outlet', 'branches', 'selectedFacilities'));
    }

    public function updateOutlet(Request $request, $id)
    {
        $outlet = Outlet::findOrFail($id);

        $request->validate([
            'nama_outlet' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'alamat_lengkap' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'tipe_outlet' => 'required|in:mall,pusat_perbelanjaan,perkantoran,stasiun,bandara,jalan_utama,kawasan_komersial,perumahan,kampus,rumah_sakit,hotel,wisata,pusat_kota,lainnya',
            'kapasitas_parkir' => 'nullable|integer|min:0',
            'zona_pelayanan' => 'nullable|string|max:100',
            'jam_operasional' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif,maintenance',
            'foto_outlet' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 1. Handle upload foto
        if ($request->hasFile('foto_outlet')) {
            if ($outlet->foto_outlet && file_exists(public_path($outlet->foto_outlet))) {
                unlink(public_path($outlet->foto_outlet));
            }

            $file = $request->file('foto_outlet');
            $filename = time() . '_' . Str::slug($outlet->nama_outlet) . '.' . $file->getClientOriginalExtension();

            $path = 'images/outlets/';
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0777, true);
            }

            $file->move(public_path($path), $filename);
            $outlet->foto_outlet = $path . $filename;
        }

        // 2. Handle fasilitas
        if ($request->has('fasilitas')) {
            $fasilitasArray = $request->fasilitas;
            $outlet->fasilitas = implode(', ', $fasilitasArray);

            $outlet->tersedia_toilet = in_array('Toilet', $fasilitasArray);
            $outlet->tersedia_musholla = in_array('Musholla', $fasilitasArray);
            $outlet->tersedia_atm = in_array('ATM', $fasilitasArray);
            $outlet->tersedia_wifi = in_array('WiFi', $fasilitasArray);
        } else {
            $outlet->fasilitas = null;
            $outlet->tersedia_toilet = false;
            $outlet->tersedia_musholla = false;
            $outlet->tersedia_atm = false;
            $outlet->tersedia_wifi = false;
        }

        // 3. Update field lainnya
        $outlet->update([
            'branch_id' => $request->branch_id,
            'nama_outlet' => $request->nama_outlet,
            'alamat_lengkap' => $request->alamat_lengkap,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'tipe_outlet' => $request->tipe_outlet,
            'kapasitas_parkir' => $request->kapasitas_parkir,
            'zona_pelayanan' => $request->zona_pelayanan,
            'jam_operasional' => $request->jam_operasional,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.outletperusahaan')->with('success', 'Outlet berhasil diperbarui.');
    }

    public function destroyOutlet($id)
    {
        $outlet = Outlet::findOrFail($id);

        // Check if outlet has related data before deleting
        // Hapus foto jika ada
        if ($outlet->foto_outlet && file_exists(public_path($outlet->foto_outlet))) {
            unlink(public_path($outlet->foto_outlet));
        }

        $outlet->delete();

        return redirect()->route('admin.outletperusahaan')->with('success', 'Outlet berhasil dihapus.');
    }

    public function showOutlet($id)
    {
        $outlet = Outlet::with('branch')->findOrFail($id);

        // Parse fasilitas string ke array
        $selectedFacilities = [];
        if ($outlet->fasilitas) {
            $cleanString = trim($outlet->fasilitas, ' "[]\'');
            if (!empty($cleanString)) {
                $facilitiesArray = explode(',', $cleanString);
                foreach ($facilitiesArray as $facility) {
                    $trimmed = trim($facility, ' "[]\'');
                    if (!empty($trimmed)) {
                        $selectedFacilities[] = $trimmed;
                    }
                }
            }
        }

        return view('admin.outletperusahaan-show', compact('outlet', 'selectedFacilities'));
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

    // ★★★ TAMBAHKAN METHOD jadwal() INI ★★★
    public function jadwal()
    {
        // Redirect ke JadwalController index method
        return redirect()->route('admin.jadwal.index');
        // Atau jika ingin langsung menampilkan view:
        // return view('admin.jadwal.index');
    }

    public function driver()
    {
        return view('admin.driver');
    }

    public function pegawai(Request $request)
    {
        // Get all users with specific roles
        $query = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->with('roles', 'branch');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%");
            });
        }

        // Apply role filter
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pegawais = $query->paginate(15);

        // Get statistics
        $totalPegawai = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->count();

        $adminPusat = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin_pusat');
        })->count();

        $adminCabang = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin_cabang');
        })->count();

        $driver = User::whereHas('roles', function ($q) {
            $q->where('name', 'driver');
        })->count();

        $activePegawai = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->where('status', 'active')->count();

        return view('admin.pegawai', compact(
            'pegawais',
            'totalPegawai',
            'adminPusat',
            'adminCabang',
            'driver',
            'activePegawai'
        ));
    }

    /**
     * Show form for creating new pegawai
     */
    public function createPegawai()
    {
        $branches = Branch::where('status', 'aktif')->get();
        return view('admin.pegawai-create', compact('branches'));
    }

    /**
     * Store new pegawai
     */
    public function storePegawai(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:users,nik',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'agama' => 'nullable|string',
            'status_pernikahan' => 'nullable|string',
            'kontak_darurat' => 'nullable|string',
            'tanggal_bergabung' => 'required|date',
            'status_pegawai' => 'required|in:Tetap,Kontrak,Magang',
            'masa_kerja' => 'nullable|string',
            'posisi' => 'required|in:Admin Pusat,Admin Cabang,Driver,Manager',
            'branch_id' => 'required|exists:branches,id',
            'status' => 'required|in:active,inactive',
            // Pendidikan & Keahlian
            'pendidikan_terakhir' => 'nullable|string',
            'institusi' => 'nullable|string',
            'tahun_lulus' => 'nullable|string',
            'keahlian' => 'nullable|string',
            'pengalaman_kerja' => 'nullable|string',
            // Dokumen
            'dokumen_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_npwp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_skck' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['foto', 'dokumen_ktp', 'dokumen_ijazah', 'dokumen_npwp', 'dokumen_skck']);

        // Handle file upload - Foto Profil
        if ($request->hasFile('foto')) {
            $imagePath = $request->file('foto')->store('pegawai', 'public');
            $data['foto'] = $imagePath;
        }

        // Handle file upload - Dokumen KTP
        if ($request->hasFile('dokumen_ktp')) {
            $dokumenPath = $request->file('dokumen_ktp')->store('pegawai/dokumen', 'public');
            $data['dokumen_ktp'] = $dokumenPath;
        }

        // Handle file upload - Dokumen Ijazah
        if ($request->hasFile('dokumen_ijazah')) {
            $dokumenPath = $request->file('dokumen_ijazah')->store('pegawai/dokumen', 'public');
            $data['dokumen_ijazah'] = $dokumenPath;
        }

        // Handle file upload - Dokumen NPWP
        if ($request->hasFile('dokumen_npwp')) {
            $dokumenPath = $request->file('dokumen_npwp')->store('pegawai/dokumen', 'public');
            $data['dokumen_npwp'] = $dokumenPath;
        }

        // Handle file upload - Dokumen SKCK
        if ($request->hasFile('dokumen_skck')) {
            $dokumenPath = $request->file('dokumen_skck')->store('pegawai/dokumen', 'public');
            $data['dokumen_skck'] = $dokumenPath;
        }

        $data['password'] = bcrypt('password123');
        $data['status'] = 'active';

        $user = User::create($data);

        // Assign role based on posisi
        $roleMapping = [
            'Admin Pusat' => 'admin_pusat',
            'Admin Cabang' => 'admin_cabang',
            'Driver' => 'driver',
            'Manager' => 'admin_pusat',
        ];

        $role = $roleMapping[$request->posisi] ?? 'driver';
        $user->assignRole($role);

        return redirect()->route('admin.pegawai')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Show pegawai details
     */
    public function showPegawai($id)
    {
        $pegawai = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->findOrFail($id);

        return view('admin.pegawai-show', compact('pegawai'));
    }

    /**
     * Show edit pegawai form
     */
    public function editPegawai($id)
    {
        $pegawai = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->findOrFail($id);

        $branches = Branch::where('status', 'aktif')->get();
        return view('admin.pegawai-edit', compact('pegawai', 'branches'));
    }

    /**
     * Update pegawai
     */
    public function updatePegawai(Request $request, $id)
    {
        $pegawai = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:users,nik,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'agama' => 'nullable|string',
            'status_pernikahan' => 'nullable|string',
            'kontak_darurat' => 'nullable|string',
            'tanggal_bergabung' => 'nullable|date',
            'status_pegawai' => 'nullable|in:Tetap,Kontrak,Magang',
            'masa_kerja' => 'nullable|string',
            'posisi' => 'nullable|in:Admin Pusat,Admin Cabang,Driver,Manager',
            'branch_id' => 'nullable|exists:branches,id',
            'status' => 'nullable|in:active,inactive',
            // Pendidikan & Keahlian
            'pendidikan_terakhir' => 'nullable|string',
            'institusi' => 'nullable|string',
            'tahun_lulus' => 'nullable|string',
            'keahlian' => 'nullable|string',
            'pengalaman_kerja' => 'nullable|string',
            // Dokumen
            'dokumen_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_npwp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_skck' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['foto', 'dokumen_ktp', 'dokumen_ijazah', 'dokumen_npwp', 'dokumen_skck']);

        // Handle file upload - Foto Profil
        if ($request->hasFile('foto')) {
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $imagePath = $request->file('foto')->store('pegawai', 'public');
            $data['foto'] = $imagePath;
        }

        // Handle file upload - Dokumen KTP
        if ($request->hasFile('dokumen_ktp')) {
            if ($pegawai->dokumen_ktp && Storage::disk('public')->exists($pegawai->dokumen_ktp)) {
                Storage::disk('public')->delete($pegawai->dokumen_ktp);
            }
            $dokumenPath = $request->file('dokumen_ktp')->store('pegawai/dokumen', 'public');
            $data['dokumen_ktp'] = $dokumenPath;
        }

        // Handle file upload - Dokumen Ijazah
        if ($request->hasFile('dokumen_ijazah')) {
            if ($pegawai->dokumen_ijazah && Storage::disk('public')->exists($pegawai->dokumen_ijazah)) {
                Storage::disk('public')->delete($pegawai->dokumen_ijazah);
            }
            $dokumenPath = $request->file('dokumen_ijazah')->store('pegawai/dokumen', 'public');
            $data['dokumen_ijazah'] = $dokumenPath;
        }

        // Handle file upload - Dokumen NPWP
        if ($request->hasFile('dokumen_npwp')) {
            if ($pegawai->dokumen_npwp && Storage::disk('public')->exists($pegawai->dokumen_npwp)) {
                Storage::disk('public')->delete($pegawai->dokumen_npwp);
            }
            $dokumenPath = $request->file('dokumen_npwp')->store('pegawai/dokumen', 'public');
            $data['dokumen_npwp'] = $dokumenPath;
        }

        // Handle file upload - Dokumen SKCK
        if ($request->hasFile('dokumen_skck')) {
            if ($pegawai->dokumen_skck && Storage::disk('public')->exists($pegawai->dokumen_skck)) {
                Storage::disk('public')->delete($pegawai->dokumen_skck);
            }
            $dokumenPath = $request->file('dokumen_skck')->store('pegawai/dokumen', 'public');
            $data['dokumen_skck'] = $dokumenPath;
        }

        $pegawai->update($data);

        // Update roles if posisi changed
        if ($request->filled('posisi')) {
            $roleMapping = [
                'Admin Pusat' => 'admin_pusat',
                'Admin Cabang' => 'admin_cabang',
                'Driver' => 'driver',
                'Manager' => 'admin_pusat',
            ];

            $role = $roleMapping[$request->posisi] ?? 'driver';
            $pegawai->syncRoles($role);
        }

        return redirect()->route('admin.pegawai')->with('success', 'Pegawai berhasil diperbarui.');
    }

    /**
     * Delete pegawai
     */
    public function destroyPegawai($id)
    {
        $pegawai = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->findOrFail($id);

        // Delete photo if exists
        if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $pegawai->delete();

        return redirect()->route('admin.pegawai')->with('success', 'Pegawai berhasil dihapus.');
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
        $tarifs = MasterTarif::aktif()->get();
        return view('admin.rute-create', compact('layanans', 'tarifs'));
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
            'master_tarif_ids' => 'nullable|array',
            'master_tarif_ids.*' => 'exists:master_tarif,id',
        ]);

        $data = $request->all();

        // Format durasi if needed
        if (!str_contains($data['durasi'], ':')) {
            $data['durasi'] = $data['durasi'] . ':00';
        }

        // Hapus master_tarif_ids dari data sebelum create
        $tarifIds = $request->input('master_tarif_ids', []);
        unset($data['master_tarif_ids']);

        $rute = Rute::create($data);

        // Attach tarifs jika ada
        if (!empty($tarifIds)) {
            $rute->masterTarifs()->attach($tarifIds);
        }

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil ditambahkan.');
    }

    public function editRute($id)
    {
        $rute = Rute::findOrFail($id);
        $layanans = MLayanan::all();
        $tarifs = MasterTarif::aktif()->get();
        return view('admin.rute-edit', compact('rute', 'layanans', 'tarifs'));
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
            'master_tarif_ids' => 'nullable|array',
            'master_tarif_ids.*' => 'exists:master_tarif,id',
        ]);

        $data = $request->all();

        // Format durasi if needed
        if (!str_contains($data['durasi'], ':')) {
            $data['durasi'] = $data['durasi'] . ':00';
        }

        // Hapus master_tarif_ids dari data sebelum update
        $tarifIds = $request->input('master_tarif_ids', []);
        unset($data['master_tarif_ids']);

        $rute->update($data);

        // Sync tarifs
        $rute->masterTarifs()->sync($tarifIds);

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil diperbarui.');
    }

    public function destroyRute($id)
    {
        $rute = Rute::findOrFail($id);

        // Check if rute has active schedules
        if ($rute->jadwals()->count() > 0) {
            return redirect()->route('admin.rute')->with('error', 'Rute tidak dapat dihapus karena masih memiliki jadwal aktif.');
        }

        $rute->delete();

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil dihapus.');
    }

    public function showRute($id)
    {
        $rute = Rute::with(['layanan', 'masterTarifs'])->findOrFail($id);

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
        $pemesanan = Pemesanan::with([
            'jadwal.shuttle',
            'jadwal.rutes',
            'user',
            'detailPenumpang'
        ])
        ->paginate(15);

        $rutes = Rute::all();
        $customers = User::whereHas('roles', function($q) {
            $q->where('name', 'customer');
        })->get();

        return view('admin.transaksi.perjalanan', [
            'pemesanan' => $pemesanan,
            'rutes' => $rutes,
            'customers' => $customers
        ]);
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

    // Tiket Perjalanan Method
    public function tiketPerjalanan()
    {
        return view('admin.tiket-perjalanan');
    }

    // Laporan Method
    public function laporan()
    {
        return view('admin.laporan');
    }

    // Pengaturan Methods - User Management
    public function user(Request $request)
    {
        // Get users with filtering
        $query = User::with('roles', 'branch');

        // Apply filters
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get summary data
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();

        // Get pegawai without user account (pegawai yang belum punya akun)
        $pegawaiWithoutAccount = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->doesntHave('roles.permissions')->orWhereHas('roles', function ($q) {
            $q->whereNotIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->get();

        // Simpler approach: get all pegawai with roles but check if they need to be assigned as user accounts
        $allPegawai = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->get();

        // Get data for forms
        $branches = Branch::where('status', 'aktif')->get();
        $roles = ['admin_pusat', 'admin_cabang', 'operator', 'driver', 'customer'];

        return view('admin.user', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'branches',
            'roles',
            'allPegawai'
        ));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin_pusat,admin_cabang,operator,driver,customer',
            'password' => 'required|string|min:8|confirmed',
            'branch_id' => 'required_if:role,admin_cabang,driver|exists:branches,id',
            'status' => 'required|in:active,inactive',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        // Get pegawai data
        $user = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
        })->findOrFail($request->user_id);

        // Update password and status
        $user->password = Hash::make($request->password);
        $user->status = $request->status;

        // Set branch_id for admin_cabang and driver
        if (in_array($request->role, ['admin_cabang', 'driver'])) {
            $user->branch_id = $request->branch_id;
        }

        $user->save();

        // Assign roles
        $user->syncRoles([$request->role]);

        // Assign permissions if provided
        if ($request->has('permissions') && is_array($request->permissions)) {
            $user->syncPermissions($request->permissions);
        } else {
            // If no permissions provided, assign default permissions based on role
            $defaultPermissions = $this->getDefaultPermissionsForRole($request->role);
            $user->syncPermissions($defaultPermissions);
        }

        return redirect()->route('admin.user')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Get pegawai data for form (AJAX endpoint)
     */
    public function getPegawaiData($id)
    {
        try {
            $pegawai = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin_pusat', 'admin_cabang', 'driver']);
            })->findOrFail($id);

            // Ambil role pertama dari pegawai
            $userRole = $pegawai->roles->first()?->name;

            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $pegawai->name,
                    'email' => $pegawai->email,
                    'phone' => $pegawai->phone,
                    'nik' => $pegawai->nik,
                    'posisi' => $pegawai->posisi,
                    'branch_id' => $pegawai->branch_id,
                    'branch_name' => $pegawai->branch ? $pegawai->branch->nama_cabang : '-',
                    'status' => $pegawai->status,
                    'role' => $userRole,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Get default permissions for a role
     */
    private function getDefaultPermissionsForRole($role)
    {
        $rolePermissions = [
            'admin_pusat' => [
                // Dashboard
                'view_dashboard',

                // Master Data - Full access
                'view_master_data',
                'view_profile_perusahaan',
                'manage_profile_perusahaan',
                'view_cabang',
                'manage_cabang',
                'view_outlet',
                'manage_outlet',
                'view_promo',
                'manage_promo',
                'view_kontak',
                'manage_kontak',
                'view_artikel',
                'manage_artikel',
                'view_armada',
                'manage_armada',
                'view_driver',
                'manage_driver',
                'view_pegawai',
                'manage_pegawai',
                'view_rute',
                'manage_rute',
                'view_jadwal',
                'manage_jadwal',

                // Transaksi - Full access
                'view_transaksi',
                'view_smartsend_transaksi',
                'manage_smartsend_transaksi',
                'view_perjalanan_transaksi',
                'manage_perjalanan_transaksi',
                'view_armada_transaksi',
                'manage_armada_transaksi',

                // SmartSend - Full access
                'view_smartsend',
                'view_smartsend_tiket',
                'manage_smartsend_tiket',
                'view_smartsend_perjalanan',
                'manage_smartsend_perjalanan',
                'view_smartsend_armada',
                'manage_smartsend_armada',

                // SmartRent - Full access
                'view_smartrent',
                'manage_smartrent',

                // Laporan - Full access
                'view_laporan',
                'manage_laporan',

                // Pengaturan - Full access
                'view_pengaturan',
                'view_user',
                'manage_user',
                'view_menu',
                'manage_menu',
            ],
            'admin_cabang' => [
                // Dashboard
                'view_dashboard',

                // Master Data - Limited access
                'view_master_data',
                'view_profile_perusahaan',
                'view_cabang',
                'view_outlet',
                'manage_outlet',
                'view_promo',
                'manage_promo',
                'view_kontak',
                'manage_kontak',
                'view_artikel',
                'manage_artikel',
                'view_armada',
                'manage_armada',
                'view_driver',
                'manage_driver',
                'view_pegawai',
                'manage_pegawai',
                'view_rute',
                'manage_rute',
                'view_jadwal',
                'manage_jadwal',

                // Transaksi - Full access
                'view_transaksi',
                'view_smartsend_transaksi',
                'manage_smartsend_transaksi',
                'view_perjalanan_transaksi',
                'manage_perjalanan_transaksi',
                'view_armada_transaksi',
                'manage_armada_transaksi',

                // SmartSend - Full access
                'view_smartsend',
                'view_smartsend_tiket',
                'manage_smartsend_tiket',
                'view_smartsend_perjalanan',
                'manage_smartsend_perjalanan',
                'view_smartsend_armada',
                'manage_smartsend_armada',

                // SmartRent - Full access
                'view_smartrent',
                'manage_smartrent',

                // Laporan - View only
                'view_laporan',
            ],
            'operator' => [
                // Dashboard
                'view_dashboard',

                // Master Data - Limited access
                'view_master_data',
                'view_profile_perusahaan',
                'view_cabang',
                'view_outlet',
                'view_promo',
                'view_kontak',
                'view_artikel',
                'view_armada',
                'view_driver',
                'view_pegawai',
                'view_rute',
                'view_jadwal',

                // Transaksi - Full access
                'view_transaksi',
                'view_smartsend_transaksi',
                'manage_smartsend_transaksi',
                'view_perjalanan_transaksi',
                'manage_perjalanan_transaksi',
                'view_armada_transaksi',
                'manage_armada_transaksi',

                // SmartSend - Full access
                'view_smartsend',
                'view_smartsend_tiket',
                'manage_smartsend_tiket',
                'view_smartsend_perjalanan',
                'manage_smartsend_perjalanan',
                'view_smartsend_armada',
                'manage_smartsend_armada',

                // SmartRent - Full access
                'view_smartrent',
                'manage_smartrent',

                // Laporan - View only
                'view_laporan',
            ],
            'driver' => [
                // Dashboard
                'view_dashboard',

                // Limited access for driver operations
                'view_jadwal',
            ],
            'customer' => [
                // Customer has no admin permissions
            ],
        ];

        return $rolePermissions[$role] ?? [];
    }

    public function pengadaanBahan()
    {
        return view('admin.pengadaan-bahan', [
            'title' => 'Pengaturan - Pengadaan Bahan',
            'pageTitle' => 'Pengaturan - Pengadaan Bahan'
        ]);
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

            return redirect()->route('admin.login');

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
