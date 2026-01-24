<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
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
use App\Models\Faq;
use App\Models\MembershipPayment;
use App\Models\HargaPaket;
use App\Models\PengirimanPaket;
use App\Models\Artikel;
use App\Models\KursiTerpesan;
use Carbon\Carbon;
use App\Models\Review;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\Shipment;
use App\Models\RuteSegment;
use App\Models\MasterHarga;
use App\Models\ShipmentTracking;
use App\Services\PaylabsService;

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

        if (strlen($initials) == 1) {
            $initials = strtoupper(substr($name, 0, 2));
        } else {
            $initials = substr($initials, 0, 2);
        }

        return $initials;
    }
}

class CustomerController extends Controller
{
    protected $paylabsService;

    public function __construct(PaylabsService $paylabsService)
    {
        $this->paylabsService = $paylabsService;
    }

    /**
     * Helper: Dapatkan data user
     */
    private function getUserData(): array
    {
        $user = session()->get('user', []);
        $userModel = Auth::user();

        return [
            'id' => $user['id'] ?? null,
            'name' => $user['name'] ?? null,
            'email' => $user['email'] ?? null,
            'membership_status' => $user['membership_status'] ?? 'non_member',
            'membership_level' => $user['membership_level'] ?? null,
            'is_member' => ($user['membership_status'] ?? 'non_member') === 'active'
        ];
    }

    /**
     * Helper: Get eligible promos (temporary solution)
     */
    private function getEligiblePromosWithStatus(array $userData, array $bookingData, $serviceType = 'shuttle'): array
    {
        $isMember = isset($userData['membership_status']) && $userData['membership_status'] === 'active';
        $jumlahTiket = $bookingData['jumlah_tiket'] ?? 1;
        $totalPembelian = $bookingData['total_pembelian'] ?? 0;

        $promos = Promo::active()
            ->where(function($query) use ($serviceType) {
                $query->where('tipe_promo', $serviceType)
                    ->orWhere('tipe_promo', 'all');
            })
            ->get()
            ->map(function ($promo) use ($userData, $jumlahTiket, $totalPembelian, $isMember) {
                // Validasi sederhana
                $eligible = true;
                $reason = null;

                // Cek status dasar
                if (!$promo->isValid()) {
                    $eligible = false;
                    $reason = 'Promo tidak aktif';
                }

                // Cek minimal pembelian
                elseif ($totalPembelian < $promo->minimal_pembelian) {
                    $eligible = false;
                    $reason = 'Min. pembelian Rp ' . number_format($promo->minimal_pembelian, 0, ',', '.');
                }

                // Cek kategori keluarga
                elseif ($promo->kategori_promo === 'keluarga' && $promo->min_tiket && $jumlahTiket < $promo->min_tiket) {
                    $eligible = false;
                    $reason = "Minimal {$promo->min_tiket} tiket";
                }

                // Cek kategori membership
                elseif ($promo->kategori_promo === 'membership' && !$isMember) {
                    $eligible = false;
                    $reason = 'Khusus member';
                }

                // Cek khusus member
                elseif ($promo->khusus_member && !$isMember) {
                    $eligible = false;
                    $reason = 'Hanya untuk member';
                }

                return [
                    'promo' => $promo,
                    'eligible' => $eligible,
                    'reason' => $reason,
                ];
            });

        return $promos->toArray();
    }

    /**
     * Helper: Validasi promo dengan kondisi user
     */
    private function validatePromoWithUser(Promo $promo, array $userData, array $bookingData): array
    {
        $isMember = isset($userData['membership_status']) && $userData['membership_status'] === 'active';
        $jumlahTiket = $bookingData['jumlah_tiket'] ?? 1;
        $totalPembelian = $bookingData['total_pembelian'] ?? 0;

        // Cek status promo
        if (!$promo->isValid()) {
            return [
                'valid' => false,
                'message' => 'Promo tidak aktif atau sudah kadaluarsa'
            ];
        }

        // Cek minimal pembelian
        if ($totalPembelian < $promo->minimal_pembelian) {
            return [
                'valid' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($promo->minimal_pembelian, 0, ',', '.')
            ];
        }

        // Cek kategori keluarga
        if ($promo->kategori_promo === 'keluarga' && $promo->min_tiket && $jumlahTiket < $promo->min_tiket) {
            return [
                'valid' => false,
                'message' => "Minimal {$promo->min_tiket} tiket untuk promo keluarga"
            ];
        }

        // Cek kategori membership
        if ($promo->kategori_promo === 'membership' && !$isMember) {
            return [
                'valid' => false,
                'message' => 'Promo ini hanya untuk member'
            ];
        }

        // Cek khusus member
        if ($promo->khusus_member && !$isMember) {
            return [
                'valid' => false,
                'message' => 'Promo ini hanya untuk member'
            ];
        }

        // Hitung diskon
        $diskon = $promo->calculateDiscount($totalPembelian);
        $totalSetelahDiskon = $totalPembelian - $diskon;

        return [
            'valid' => true,
            'message' => 'Promo berhasil diterapkan',
            'diskon' => $diskon,
            'total_setelah_diskon' => $totalSetelahDiskon
        ];
    }

    /**
     * Halaman beranda
     */
    public function beranda()
    {
        $user = session()->get('user');

        $outletsGrouped = Outlet::with('branch')
            ->where('status', 'aktif')
            ->orderBy('nama_outlet')
            ->get()
            ->groupBy(function ($outlet) {
                return $outlet->branch ? $outlet->branch->kota : 'Lainnya';
            });

        $layanan = MLayanan::where('status_aktif', true)
            ->orderBy('urutan_tampilan', 'asc')
            ->take(3)
            ->get();

        $profile = MProfilePerusahaan::where('status', 'active')->first();

       // Ambil data review dari database yang sudah approved
$reviews = Review::with('user')
    ->where('status', 'approved')
    ->orderBy('created_at', 'desc')
    ->limit(3)
    ->get()
    ->map(function($review) {
        return [
            'name' => $review->user->name ?? 'User',
            'avatar' => $review->user?->avatar_url ?? null,
            'stars' => $review->rating,
            'text' => $review->review,
            'date' => $review->created_at->format('d M Y')
        ];
    });

        // Jika tidak ada review dari database, gunakan default
        if ($reviews->isEmpty()) {
            $reviews = collect([
                [
                    'name' => 'Luna Ayna',
                    'avatar' => 'https://randomuser.me/api/portraits/women/32.jpg',
                    'stars' => 5,
                    'text' => 'Servisnya bagus, drivernya sopan dan nyetirnya halus jadi bisa tidur selama perjalanan. Tracking lokasinya juga akurat. Bakal jadi langganan.'
                ],
                [
                    'name' => 'Rizky Pratama',
                    'avatar' => 'https://randomuser.me/api/portraits/men/54.jpg',
                    'stars' => 4,
                    'text' => 'Pertama kali coba SmartShuttle dan langsung puas. Mobilnya bersih, AC dingin, kursinya empuk. Berangkat juga sesuai jadwal. Recommended banget buat yang sering PP Jakarta–Bandung!'
                ],
                [
                    'name' => 'Sari Dewi',
                    'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg',
                    'stars' => 5,
                    'text' => 'Harganya menurut saya cukup murah dibanding shuttle lain, tapi kualitas layanannya tetap bagus. Pemesanan lewat aplikasi juga gampang.'
                ]
            ]);
        }

        // Ambil data promo yang aktif dan dalam periode
        $promos = Promo::where('status', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_berakhir', '>=', now())
            ->where(function ($q) {
                $q->whereNull('kuota')
                  ->orWhereColumn('terpakai', '<', 'kuota');
            })
            ->orderBy('tanggal_mulai', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'kode' => $p->kode_promo ?? null,
                    'nama' => $p->nama_promo ?? $p->nama ?? '',
                    'deskripsi' => $p->deskripsi ?? '',
                    'gambar' => asset($p->gambar ?? 'images/default-promo.jpg'),
                    'periode' => ($p->tanggal_mulai ? $p->tanggal_mulai->format('d M Y') : '') . ' - ' . ($p->tanggal_berakhir ? $p->tanggal_berakhir->format('d M Y') : ''),
                ];
            });

        // Ambil artikel terbaru yang aktif (diambil dari ArtikelSeeder)
        $articles = Artikel::aktif()
            ->terbaru()
            ->limit(4)
            ->get()
            ->map(function ($a) {
                return [
                    'id' => $a->id,
                    'image' => $a->gambar_url ?? ($a->gambar ? asset('storage/' . $a->gambar) : asset('images/default-article.jpg')),
                    'title' => $a->judul,
                    'category' => $a->kategori ?? '',
                    'excerpt' => $a->excerpt ?? strip_tags(Str::limit($a->konten ?? '', 150)),
                    'date' => $a->tanggal_publikasi ? $a->tanggal_publikasi->format('d M Y') : '',
                ];
            })
            ->toArray();

        // Jika tidak ada promo aktif, gunakan contoh data (opsional)
        if ($promos->isEmpty()) {
            $promos = collect([
                [
                    'id' => 1,
                    'nama' => 'Promo Awal Tahun',
                    'deskripsi' => 'Diskon 30% untuk semua layanan shuttle selama bulan Januari',
                    'gambar' => asset('images/promo/bali.jpg'),
                    'periode' => '1 Jan 2024 - 31 Mar 2024'
                ],
                [
                    'id' => 2,
                    'nama' => 'Paket Keluarga',
                    'deskripsi' => 'Diskon 25% untuk pemesanan tiket shuttle minimal 4 orang',
                    'gambar' => asset('images/promo/promo2.jpg'),
                    'periode' => '1 Feb 2024 - 31 Mar 2024'
                ],
                [
                    'id' => 3,
                    'nama' => 'Member Baru',
                    'deskripsi' => 'Dapatkan 2 tiket gratis untuk pendaftaran member baru',
                    'gambar' => asset('images/promo/promo3.jpg'),
                    'periode' => 'Sepanjang Tahun 2024'
                ],
            ]);
        }
        $articles = Artikel::aktif()
        ->terbaru()
        ->get()
        ->map(function ($artikel) {
            return [
                'id' => $artikel->id,
                'title' => $artikel->judul,
                'excerpt' => $artikel->excerpt,
                'full_content' => $artikel->konten,
                'category' => $artikel->kategori,
                'image' => $artikel->gambar_url,
                'date' => $artikel->tanggal_format,
                'read_time' => $artikel->waktu_baca,
                'tags' => explode(',', $artikel->meta_keywords ?? ''),
                'slug' => $artikel->slug
            ];
        });

        return view('customer.beranda', compact('user', 'outletsGrouped', 'layanan', 'profile', 'reviews', 'promos', 'articles'));
    }

    /**
     * Halaman outlet
     */
    public function outlet(Request $request)
    {
        $user = session()->get('user');

        $query = Outlet::with('branch')
            ->where('status', 'aktif');

        // Filter berdasarkan kota
        if ($request->filled('kota')) {
            $query->whereHas('branch', function ($q) use ($request) {
                $q->where('kota', $request->kota);
            });
        }

        // Filter berdasarkan branch_id
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Pagination untuk initial load (6 items)
        $outlets = $query->orderBy('nama_outlet')->limit(6)->get();
        $totalOutlets = $query->count();
        $hasMore = $totalOutlets > 6;

        $branches = Branch::where('status', 'aktif')
            ->orderBy('kota')
            ->get();

        $kotaList = Branch::select('kota')
            ->distinct()
            ->where('status', 'aktif')
            ->orderBy('kota')
            ->pluck('kota')
            ->toArray();

        return view('customer.outlet', compact('user', 'outlets', 'branches', 'kotaList', 'totalOutlets', 'hasMore'));
    }

    /**
     * AJAX endpoint untuk load more outlets
     */
    public function loadMoreOutlets(Request $request)
    {
        try {
            \Log::info('LoadMoreOutlets Request Data:', $request->all());

            // Validasi data
            $validator = Validator::make($request->all(), [
                'offset' => 'required|integer|min:0',
                'kota' => 'nullable|string',
                'branch_id' => 'nullable|integer|exists:branches,id'
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            $validated = $validator->validated();

            $query = Outlet::with('branch')
                ->where('status', 'aktif');

            // Filter berdasarkan kota
            if (!empty($validated['kota'])) {
                $query->whereHas('branch', function ($q) use ($validated) {
                    $q->where('kota', $validated['kota']);
                });
            }

            // Filter berdasarkan branch_id
            if (!empty($validated['branch_id'])) {
                $query->where('branch_id', $validated['branch_id']);
            }

            // Hitung total
            $totalOutlets = $query->count();

            // Load 6 more outlets starting from offset
            $outlets = $query->orderBy('nama_outlet')
                ->skip($validated['offset'])
                ->take(6)
                ->get();

            $allLoaded = ($validated['offset'] + $outlets->count()) >= $totalOutlets;

            \Log::info('LoadMoreOutlets Results:', [
                'offset' => $validated['offset'],
                'outlets_count' => $outlets->count(),
                'totalOutlets' => $totalOutlets,
                'allLoaded' => $allLoaded
            ]);

            // Jika tidak ada outlet lagi
            if ($outlets->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'html' => '',
                    'count' => 0,
                    'allLoaded' => true,
                    'total' => $totalOutlets,
                    'message' => 'Tidak ada outlet lagi'
                ]);
            }

            // Generate HTML untuk outlets baru
            $html = '';
            foreach ($outlets as $outlet) {
                $gambar = $this->getOutletImage($outlet);

                // Buat HTML untuk setiap outlet card
                $html .= '<div class="outlet-card" data-city="' . ($outlet->branch ? $outlet->branch->kota : '') . '">';
                $html .= '<div class="outlet-card-inner">';
                $html .= '<div class="card-header">' . e($outlet->nama_outlet) . '</div>';
                $html .= '<div class="card-image">';
                $html .= '<img src="' . e($gambar) . '" alt="' . e($outlet->nama_outlet) . '" class="outlet-img" onerror="this.onerror=null;this.src=\'' . asset('images/placeholder-outlet.jpg') . '\'">';
                $html .= '</div>';
                $html .= '<div class="card-body">';

                // Info grid
                $html .= '<div class="info-grid">';
                $html .= '<div class="info-item">';
                $html .= '<div class="info-label"><i class="fas fa-store"></i> CABANG</div>';
                $html .= '<div class="info-value">' . e($outlet->branch ? $outlet->branch->nama_cabang : 'Tidak diketahui') . '</div>';
                $html .= '</div>';
                $html .= '<div class="info-item">';
                $html .= '<div class="info-label"><i class="fas fa-city"></i> KOTA</div>';
                $html .= '<div class="info-value">' . e($outlet->branch ? $outlet->branch->kota : 'Tidak diketahui') . '</div>';
                $html .= '</div>';
                $html .= '<div class="info-item full-width">';
                $html .= '<div class="info-label"><i class="fas fa-map-marker-alt"></i> ALAMAT</div>';
                $html .= '<div class="info-value address">' . e($outlet->alamat_lengkap ?? $outlet->alamat) . '</div>';
                $html .= '</div>';
                $html .= '</div>';

                // Contact & hours
                $html .= '<div class="contact-hours">';
                $html .= '<div class="contact-hours-grid">';
                $html .= '<div class="contact-item">';
                $html .= '<div class="contact-label"><i class="fas fa-phone"></i> TELEPON</div>';
                $html .= '<div class="contact-value">' . e($outlet->telepon ?? '-') . '</div>';
                $html .= '</div>';
                $html .= '<div class="hours-item">';
                $html .= '<div class="hours-label"><i class="fas fa-clock"></i> JAM OPERASIONAL</div>';
                $html .= '<div class="hours-value">' . e($outlet->jam_operasional ?? '24 Jam') . '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';

                // Button detail
                $html .= '<button class="btn-detail" onclick="showOutletPopup(' . $outlet->id . ')">';
                $html .= '<i class="fas fa-eye"></i> Lihat Detail';
                $html .= '</button>';

                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }

            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $outlets->count(),
                'allLoaded' => $allLoaded,
                'total' => $totalOutlets,
                'current_offset' => $validated['offset'] + $outlets->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Load more outlets error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat outlet. Error: ' . $e->getMessage(),
                'debug' => env('APP_DEBUG') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    private function getOutletImage($outlet)
    {
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

    /**
     * Form login
     */
    public function showLogin()
    {
        if (session()->has('user')) {
            return redirect()->route('customer.beranda')->with('info', 'Anda sudah login!');
        }

        // Pastikan session dan CSRF token sudah diinisialisasi sebelum render view
        if (!session()->isStarted()) {
            session()->start();
        }

        if (!session()->token()) {
            session()->regenerateToken();
        }

        // Log untuk debugging first load issues
        \Log::info('CustomerController::showLogin - Session initialized', [
            'session_id' => session()->getId(),
            'has_csrf_token' => !empty(session()->token()),
            'csrf_token' => substr(session()->token() ?? '', 0, 10) . '...',
            'session_has_user' => session()->has('user'),
            'auth_check' => Auth::check(),
        ]);

        return view('customer.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // Log CSRF token validation at start
            \Log::info('Login request start', [
                'email' => $validated['email'],
                'has_csrf_token' => !empty($request->session()->token()),
                'session_id' => session()->getId(),
            ]);

            $remember = $request->filled('remember');
            $credentials = [
                'email' => $validated['email'],
                'password' => $validated['password'],
            ];

            // Debug logging
            \Log::info('Login attempt', ['email' => $validated['email']]);
            $userCheck = User::where('email', $validated['email'])->first();
            if ($userCheck) {
                \Log::info('User found', [
                    'user_id' => $userCheck->id,
                    'status' => $userCheck->status,
                    'email_verified_at' => $userCheck->email_verified_at,
                    'password_hash_exists' => !empty($userCheck->password)
                ]);
            } else {
                \Log::info('User not found for email: ' . $validated['email']);
            }

            if (!Auth::attempt($credentials, $remember)) {
                \Log::info('Auth attempt failed for email: ' . $validated['email']);
                return back()->withErrors(['message' => 'Email atau password salah'])->withInput();
            }

            \Log::info('Auth attempt successful', ['user_id' => Auth::id()]);

            $request->session()->regenerate();
            $user = Auth::user();

            // Cek status user
            if ($user->status === 'inactive') {
                Auth::logout();
                return back()->withErrors(['message' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.'])->withInput();
            }

            // ===== PERBAIKAN DI SINI =====
            // Check user role and redirect accordingly
            if ($user->hasRole('admin_pusat') || $user->hasRole('admin_cabang')) {
                // Log out from web guard and log in with admin guard
                Auth::logout();
                Auth::guard('admin')->login($user);
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('driver')) {
                // Log out from web guard and log in with driver guard
                Auth::logout();
                Auth::guard('driver')->login($user);
                return redirect()->route('driver.dashboard');
            } elseif ($user->hasRole('operator')) {
                // For operator, redirect to appropriate dashboard
                Auth::logout();
                Auth::guard('admin')->login($user);
                return redirect()->route('admin.dashboard');
            }

            // For customer and other roles, continue as usual
            session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar_url,
                'membership_status' => $user->membership_status,
                'membership_level' => $user->membership_level,
            ]);

            // Pastikan session di-save sebelum redirect
            try {
                session()->save();
            } catch (\Exception $e) {
                \Log::warning('Failed to save session after login', ['error' => $e->getMessage()]);
            }

            \Log::info('Login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'session_saved' => true,
            ]);

            return redirect()->route('customer.beranda');

        } catch (\Exception $e) {
            \Log::error('Login exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Form register
     */
    public function showRegister()
    {
        if (session()->has('user')) {
            return redirect()->route('customer.beranda')->with('info', 'Anda sudah login!');
        }

        // Pastikan session dan CSRF token sudah diinisialisasi sebelum render view
        if (!session()->isStarted()) {
            session()->start();
        }

        if (!session()->token()) {
            session()->regenerateToken();
        }

        // Log untuk debugging
        \Log::info('CustomerController::showRegister - Session initialized', [
            'session_id' => session()->getId(),
            'has_csrf_token' => !empty(session()->token()),
            'csrf_token' => substr(session()->token() ?? '', 0, 10) . '...',
        ]);

        $syaratKetentuan = SyaratKetentuan::getUntukPengguna();
        $kebijakanPrivasi = KebijakanPrivasi::getAktif();

        return view('customer.register', [
            'syaratKetentuan' => $syaratKetentuan,
            'kebijakanPrivasi' => $kebijakanPrivasi
        ]);
    }

    /**
     * Proses register - TIDAK login otomatis
     */
    public function register(Request $request)
    {
        \Log::info('CustomerController::register - Starting', $request->all());

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // Buat user baru TANPA login otomatis
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'status' => 'active',
                'membership_status' => 'non_member',
                'membership_level' => 'Bronze',
                'member_point' => 0,
                'loyalty_point' => 0,
            ]);

            // Berikan role customer
            $user->assignRole('customer');

            // Tidak login otomatis, langsung redirect ke halaman login
            return redirect()->route('customer.login')
                ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('CustomerController::register - Exception', ['error' => $e->getMessage()]);
            return back()->withErrors(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $recaller = Auth::getRecallerName();
            Cookie::queue(Cookie::forget($recaller));

            session()->forget(['user', 'token']);
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('customer.beranda');

        } catch (\Exception $e) {
            session()->forget(['user', 'token']);
            return redirect()->route('customer.beranda');
        }
    }

    /**
     * Halaman pencarian shuttle
     */
    public function showSearch(Request $request)
    {
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

        $outletsGrouped = [];
        foreach ($outlets as $outlet) {
            $kota = $outlet['kota'];
            if (!isset($outletsGrouped[$kota])) {
                $outletsGrouped[$kota] = [];
            }
            $outletsGrouped[$kota][] = $outlet;
        }

        $data = ['outletsGrouped' => $outletsGrouped];

        if ($request->has('departure_outlet') && $request->has('destination_outlet')) {
            $searchData = $this->processSearch($request);
            $data = array_merge($data, $searchData);
        }

        return view('customer.search', $data);
    }

    /**
     * API endpoint untuk pencarian (AJAX)
     */
    public function search(Request $request)
    {
        return $this->showSearch($request);
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

        $departureOutlet = Outlet::with('branch')->find($validated['departure_outlet']);
        $destinationOutlet = Outlet::with('branch')->find($validated['destination_outlet']);

        $validated['departure_outlet_data'] = $departureOutlet;
        $validated['destination_outlet_data'] = $destinationOutlet;
        $validated['departure_city'] = $departureOutlet->branch->kota ?? 'Unknown';
        $validated['destination_city'] = $destinationOutlet->branch->kota ?? 'Unknown';

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
     * Mencari jadwal yang tersedia
     */
    private function findAvailableSchedules($departureCity, $destinationCity, $departureOutlet, $destinationOutlet, $departureDate, $passengerCount)
    {
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
                $totalHarga = $this->calculatePriceForRoute($jadwal, $departureCity, $destinationCity);
                $jadwal->harga_total = $totalHarga ?? $jadwal->harga_total;
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
        if ($rute->kota_asal == $departureCity && $rute->kota_tujuan == $destinationCity) {
            return $this->checkOutletsInRoute($rute, $departureOutlet, $destinationOutlet);
        }

        if ($rute->kota_asal == $departureCity) {
            $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
            foreach ($pemberhentian as $stop) {
                if (($stop['kota'] ?? '') == $destinationCity) {
                    return $this->checkOutletsInRoute($rute, $departureOutlet, $destinationOutlet, $destinationCity);
                }
            }
        }

        if ($rute->kota_tujuan == $destinationCity) {
            $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
            foreach ($pemberhentian as $stop) {
                if (($stop['kota'] ?? '') == $departureCity) {
                    return $this->checkOutletsInRoute($rute, $departureOutlet, $destinationOutlet, null, $departureCity);
                }
            }
        }

        $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
        $foundDeparture = false;
        $foundDestination = false;

        foreach ($pemberhentian as $stop) {
            if (($stop['kota'] ?? '') == $departureCity) $foundDeparture = true;
            if (($stop['kota'] ?? '') == $destinationCity) $foundDestination = true;
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

        // Cek departure outlet
        if ($rute->kota_asal == $depCityInStop || $depCityInStop === null) {
            foreach ($pemberhentian as $stop) {
                if (($stop['kota'] ?? '') == ($depCityInStop ?? $rute->kota_asal)) {
                    if (in_array($departureOutlet, $stop['outlets'] ?? [])) {
                        $departureValid = true;
                        break;
                    }
                }
            }
            if (!$departureValid && $rute->kota_asal == ($depCityInStop ?? $rute->kota_asal)) {
                $departureValid = true;
            }
        }

        // Cek destination outlet
        if ($rute->kota_tujuan == $destCityInStop || $destCityInStop === null) {
            foreach ($pemberhentian as $stop) {
                if (($stop['kota'] ?? '') == ($destCityInStop ?? $rute->kota_tujuan)) {
                    if (in_array($destinationOutlet, $stop['outlets'] ?? [])) {
                        $destinationValid = true;
                        break;
                    }
                }
            }
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
        $ruteJadwal = RuteJadwal::where('jadwal_id', $jadwal->id)->first();
        if (!$ruteJadwal) return $jadwal->harga_total;

        $rute = Rute::find($ruteJadwal->rute_id);
        if (!$rute) return $jadwal->harga_total;

        if ($rute->kota_asal == $departureCity && $rute->kota_tujuan == $destinationCity) {
            return $rute->harga_dasar;
        }

        if ($rute->jarak) {
            $hargaPerKm = $rute->harga_dasar / $rute->jarak;
            return $rute->harga_dasar;
        }

        return $jadwal->harga_total;
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

        $jadwal = Jadwal::with(['shuttle', 'rutes'])->find($validated['jadwal_id']);
        $outletAsal = Outlet::with('branch')->find($validated['outlet_asal']);
        $outletTujuan = Outlet::with('branch')->find($validated['outlet_tujuan']);

        if ($jadwal->kursi_tersedia < $validated['penumpang']) {
            return redirect()->back()
                ->with('error', 'Kursi tidak tersedia. Hanya tersisa ' . $jadwal->kursi_tersedia . ' kursi.');
        }

        $hargaPerKursi = $jadwal->harga_total;
        $totalHarga = $hargaPerKursi * $validated['penumpang'];

        // Cek promo
        $appliedPromo = session()->get('applied_promo');
        $diskon = 0;
        $totalAfterDiscount = $totalHarga;

        if ($appliedPromo) {
            $promo = Promo::find($appliedPromo['id']);
            if ($promo && $promo->is_aktif && $totalHarga >= $promo->minimal_pembelian) {
                $diskon = $promo->hitungDiskon($totalHarga);
                $totalAfterDiscount = $totalHarga - $diskon;
            } else {
                session()->forget('applied_promo');
            }
        }

        // Cek loyalty discount
        $loyaltyDiscount = session()->get('loyalty_discount');
        $diskonLoyalty = 0;

        if ($loyaltyDiscount) {
            $user = session()->get('user', []);
            if ($loyaltyDiscount['user_id'] == ($user['id'] ?? null)) {
                $diskonLoyalty = $loyaltyDiscount['discount_amount'];
                $totalAfterDiscount -= $diskonLoyalty;
                if ($totalAfterDiscount < 0) $totalAfterDiscount = 0;
            }
        }

        $user = session()->get('user', []);
        $kotaAsal = $outletAsal->branch ? $outletAsal->branch->kota : 'Kota Asal';
        $kotaTujuan = $outletTujuan->branch ? $outletTujuan->branch->kota : 'Kota Tujuan';

        $rutePertama = null;
        $ruteTerakhir = null;
        $ruteString = '';

        if ($jadwal->rutes && $jadwal->rutes->count() > 0) {
            $rutePertama = $jadwal->rutes->first();
            $ruteTerakhir = $jadwal->rutes->last();
            $ruteString = $rutePertama->kota_asal . ' → ' . $ruteTerakhir->kota_tujuan;
        }

        // Dapatkan promo yang eligible
        $userData = $this->getUserData();
        $eligiblePromos = $this->getEligiblePromosWithStatus(
            $userData,
            [
                'jumlah_tiket' => $validated['penumpang'],
                'total_pembelian' => $totalHarga
            ],
            'shuttle'
        );

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
            'availablePromos' => Promo::active()->get(),
            'promos' => Promo::orderByDesc('status')->get(),
            'loyaltyDiscount' => $loyaltyDiscount,
            'user' => $user,
            'eligiblePromos' => $eligiblePromos,
            'userData' => $userData,
        ]);
    }

    /**
     * Validasi promo dengan kondisi user (UPDATE METHOD)
     */
    public function validatePromo(Request $request)
    {
        try {
            $request->validate([
                'promo_code' => 'required|string',
                'total_amount' => 'required|numeric',
                'ticket_count' => 'required|integer|min:1'
            ]);

            $promoCode = strtoupper($request->promo_code);
            $promo = Promo::where('kode_promo', $promoCode)->first();

            if (!$promo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode promo tidak ditemukan'
                ]);
            }

            // Dapatkan data user
            $userData = $this->getUserData();

            // Validasi promo dengan kondisi
            $validation = $this->validatePromoWithUser(
                $promo,
                $userData,
                [
                    'jumlah_tiket' => $request->ticket_count,
                    'total_pembelian' => $request->total_amount
                ]
            );

            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ]);
            }

            // Simpan promo ke session
            session()->put('applied_promo', [
                'id' => $promo->id,
                'kode' => $promo->kode_promo,
                'nama' => $promo->nama_promo,
                'deskripsi' => $promo->deskripsi,
                'kategori' => $promo->kategori_promo,
                'min_tiket' => $promo->min_tiket,
                'khusus_member' => $promo->khusus_member,
                'diskon' => $validation['diskon'],
                'total_setelah_diskon' => $validation['total_setelah_diskon']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kode promo berhasil diterapkan!',
                'promo' => [
                    'id' => $promo->id,
                    'nama' => $promo->nama_promo,
                    'kode' => $promo->kode_promo,
                    'kategori' => $promo->kategori_promo,
                    'jenis_diskon' => $promo->jenis_diskon,
                    'nilai_diskon' => $promo->nilai_diskon,
                    'maksimal_diskon' => $promo->maksimal_diskon,
                    'deskripsi' => $promo->deskripsi,
                    'minimal_pembelian' => $promo->minimal_pembelian,
                    'min_tiket' => $promo->min_tiket,
                    'khusus_member' => $promo->khusus_member
                ],
                'diskon' => $validation['diskon'],
                'total_after_discount' => $validation['total_setelah_diskon']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Dapatkan promo yang eligible untuk user (NEW METHOD)
     */
    public function getEligiblePromos(Request $request)
    {
        try {
            $request->validate([
                'ticket_count' => 'required|integer|min:1',
                'total_amount' => 'required|numeric',
                'service_type' => 'nullable|string'
            ]);

            $userData = $this->getUserData();
            $eligiblePromos = $this->getEligiblePromosWithStatus(
                $userData,
                [
                    'jumlah_tiket' => $request->ticket_count,
                    'total_pembelian' => $request->total_amount
                ],
                $request->service_type ?? 'shuttle'
            );

            return response()->json([
                'success' => true,
                'promos' => $eligiblePromos,
                'user_member_status' => $userData['membership_status'] ?? 'non_member'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat promo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Hapus promo (AJAX)
     */
    public function removePromo(Request $request)
    {
        try {
            session()->forget('applied_promo');
            return response()->json([
                'success' => true,
                'message' => 'Promo berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus promo'
            ]);
        }
    }

    /**
     * Halaman detail promo
     */
    public function showPromoDetail($id)
    {
        $user = session()->get('user');

        $promo = Promo::where('id', $id)
            ->where('status', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_berakhir', '>=', now())
            ->first();

        if (!$promo) {
            return redirect()->route('customer.beranda')
                ->with('error', 'Promo tidak ditemukan atau sudah kadaluarsa.');
        }

        return view('customer.promo_detail', compact('user', 'promo'));
    }

    /**
     * Proses pemesanan shuttle dengan validasi promo di backend
     */
    public function prosesPemesanan(Request $request)
    {
        \Log::info('Proses Pemesanan Request Data:', $request->all());

        $validator = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwals,id',
            'jumlah_penumpang' => 'required|integer|min:1|max:10',
            'nama_pemesan' => 'required|string|max:100',
            'telepon_pemesan' => 'required|string|max:20',
            'email_pemesan' => 'required|email|max:100',
            'penumpang' => 'required|array|min:1',
            'penumpang.*.nama_lengkap' => 'required|string|max:100',
            'penumpang.*.nik' => 'required|string|digits:16',
            'penumpang.*.jenis_kelamin' => 'required|string|in:L,P',
            'kode_promo' => 'nullable|string|exists:promo,kode_promo',
            'catatan' => 'nullable|string',
            'diskon_amount' => 'nullable|numeric',
            'total_after_discount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan dalam pengisian data');
        }

        DB::beginTransaction();

        try {
            $jadwal = Jadwal::with('shuttle')->findOrFail($request->jadwal_id);

            if ($jadwal->kursi_tersedia < $request->jumlah_penumpang) {
                throw new \Exception('Kursi tidak tersedia. Sisa kursi: ' . $jadwal->kursi_tersedia);
            }

            $hargaPerOrang = $jadwal->harga_total;
            $hargaTotal = $hargaPerOrang * $request->jumlah_penumpang;
            $diskon = $request->diskon_amount ?? 0;
            $promoId = null;

            // VALIDASI PROMO DI BACKEND
            if ($request->kode_promo) {
                $userData = $this->getUserData();
                $validation = $this->validatePromoWithUser(
                    Promo::where('kode_promo', strtoupper($request->kode_promo))->first(),
                    $userData,
                    [
                        'jumlah_tiket' => $request->jumlah_penumpang,
                        'total_pembelian' => $hargaTotal
                    ]
                );

                if (!$validation['valid']) {
                    throw new \Exception($validation['message']);
                }

                $promo = Promo::where('kode_promo', strtoupper($request->kode_promo))->first();
                if ($promo) {
                    $promoId = $promo->id;
                    $diskon = $validation['diskon'];
                }
            }

            $loyaltyDiscount = session()->get('loyalty_discount');
            $diskonLoyalty = 0;

            if ($loyaltyDiscount) {
                $user = Auth::user();
                if ($loyaltyDiscount['user_id'] == ($user->id ?? null)) {
                    $diskonLoyalty = $loyaltyDiscount['discount_amount'];
                    if ($user && $user->isMemberActive()) {
                        $user->useLoyaltyPoints($loyaltyDiscount['points_used']);
                    }
                    session()->forget('loyalty_discount');
                }
            }

            $totalBayar = $hargaTotal - $diskon - $diskonLoyalty;

            if ($request->total_after_discount && $request->total_after_discount > 0) {
                $totalBayar = $request->total_after_discount;
            }

            $kodeBooking = $this->generateKodeBooking();

            // Bersihkan format telepon (hapus karakter non-digit)
            $teleponPemesan = preg_replace('/\D/', '', $request->telepon_pemesan);

            $pemesanan = Pemesanan::create([
                'kode_booking' => $kodeBooking,
                'customer_id' => Auth::id(),
                'jadwal_id' => $jadwal->id,
                'jumlah_penumpang' => $request->jumlah_penumpang,
                'harga_total' => $hargaTotal,
                'diskon' => $diskon + $diskonLoyalty,
                'total_bayar' => $totalBayar,
                'nama_pemesan' => $request->nama_pemesan,
                'telepon_pemesan' => $teleponPemesan,
                'email_pemesan' => $request->email_pemesan,
                'catatan' => $request->catatan,
                'kode_promo' => $request->kode_promo,
                'status' => 'menunggu_pembayaran',
                'waktu_kadaluarsa' => now()->addHours(24),
                'created_by' => Auth::id(),
            ]);

            foreach ($request->penumpang as $dataPenumpang) {
                DetailPenumpang::create([
                    'pemesanan_id' => $pemesanan->id,
                    'nama_lengkap' => $dataPenumpang['nama_lengkap'],
                    'nik' => $dataPenumpang['nik'],
                    'jenis_kelamin' => $dataPenumpang['jenis_kelamin'],
                    'telepon' => isset($dataPenumpang['telepon']) ? preg_replace('/\D/', '', $dataPenumpang['telepon']) : null,
                    'nomor_kursi' => null
                ]);
            }

            if ($promoId) {
                $promo = Promo::find($promoId);
                if ($promo && $promo->kuota) {
                    $promo->terpakai += 1;
                    $promo->save();
                }
            }

            $jadwal->kursi_tersedia -= $request->jumlah_penumpang;
            if ($jadwal->kursi_tersedia <= 0) {
                $jadwal->status = 'penuh';
            }
            $jadwal->save();

            $user = Auth::user();
            if ($user && $user->isMemberActive()) {
                $user->addMemberPoints(100);
                $loyaltyPointsToAdd = $user->calculateLoyaltyPointsToAdd();
                $user->addLoyaltyPoints($loyaltyPointsToAdd);
            }

            DB::commit();

            return redirect('/customer/kursi?pemesanan_id=' . $pemesanan->id);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Pemesanan failed:', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Gagal melakukan pemesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate kode booking
     */
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
            'pemesanan_id' => 'required|exists:pemesanan,id'
        ]);

        $pemesanan = Pemesanan::with(['jadwal.shuttle', 'detailPenumpang'])
            ->where('id', $validated['pemesanan_id'])
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        if ($pemesanan->status !== 'menunggu_pembayaran') {
            return redirect()->route('customer.beranda')
                ->with('error', 'Pemesanan ini sudah diproses atau dibatalkan.');
        }

        $shuttle = $pemesanan->jadwal->shuttle;

        // Gunakan method getLayoutWithStatus dari model Shuttle
        $layoutKursi = $shuttle->getLayoutWithStatus($pemesanan->jadwal_id);

        // Ambil kursi yang sudah dipilih oleh pemesanan ini
        $kursiSaya = $pemesanan->detailPenumpang->pluck('nomor_kursi')->filter()->toArray();

        // Ambil kursi yang sudah dipesan oleh orang lain
        $kursiTerpesan = KursiTerpesan::where('jadwal_id', $pemesanan->jadwal_id)
            ->where('status', 'terpesan')
            ->where('pemesanan_id', '!=', $pemesanan->id) // kecuali milik saya
            ->whereHas('pemesanan', function($query) {
                $query->whereNotIn('status', ['dibatalkan', 'expired']);
            })
            ->pluck('nomor_kursi')
            ->toArray();

        // Update status kursi berdasarkan data real
        foreach ($layoutKursi as &$kursi) {
            if (in_array($kursi['nomor'], $kursiTerpesan)) {
                // Kursi sudah dipesan orang lain
                $kursi['status'] = 'terpesan';
                $kursi['class'] = 'sold';
                $kursi['icon'] = 'fa-lock';
            } elseif (in_array($kursi['nomor'], $kursiSaya)) {
                // Kursi sudah dipilih oleh saya
                $kursi['status'] = 'selected';
                $kursi['class'] = 'selected';
                $kursi['icon'] = 'fa-user-check';
            } else {
                // Kursi tersedia
                $kursi['status'] = 'tersedia';
                $kursi['class'] = 'available';
                $kursi['icon'] = 'fa-check';
            }
        }

        return view('customer.kursi', [
            'pemesanan' => $pemesanan,
            'shuttle' => $shuttle,
            'layoutKursi' => $layoutKursi,
            'kursiSaya' => $kursiSaya,
            'kursiTerpesan' => $kursiTerpesan
        ]);
    }

    /**
     * Proses pemilihan kursi
     */
    public function prosesPemilihanKursi(Request $request)
    {
        $validated = $request->validate([
            'pemesanan_id' => 'required|exists:pemesanan,id',
            'kursi' => 'required|array|min:1',
            'kursi.*' => 'required|string|distinct'
        ]);

        DB::beginTransaction();

        try {
            $pemesanan = Pemesanan::with(['detailPenumpang', 'jadwal.shuttle'])
                ->where('id', $validated['pemesanan_id'])
                ->where('customer_id', Auth::id())
                ->where('status', 'menunggu_pembayaran')
                ->firstOrFail();

            // VALIDASI 1: Jumlah kursi harus sama dengan jumlah penumpang
            if (count($validated['kursi']) !== $pemesanan->jumlah_penumpang) {
                return redirect()->back()
                    ->with('error', 'Jumlah kursi yang dipilih harus sama dengan jumlah penumpang.');
            }

            // VALIDASI 2: Cek apakah kursi masih tersedia (tidak dipesan oleh pemesanan lain)
            $kursiTerpesan = KursiTerpesan::where('jadwal_id', $pemesanan->jadwal_id)
                ->whereIn('nomor_kursi', $validated['kursi'])
                ->where('status', 'terpesan')
                ->whereHas('pemesanan', function($query) {
                    $query->whereNotIn('status', ['dibatalkan', 'expired']);
                })
                ->pluck('nomor_kursi')
                ->toArray();

            if (!empty($kursiTerpesan)) {
                return redirect()->back()
                    ->with('error', 'Kursi ' . implode(', ', $kursiTerpesan) . ' sudah dipesan oleh penumpang lain.');
            }

            // HAPUS KURSI LAMA untuk pemesanan ini (jika ada)
            KursiTerpesan::where('pemesanan_id', $pemesanan->id)->delete();

            // SIMPAN KURSI BARU sebagai 'terpesan' (PERMANEN LOCKED)
            $detailPenumpang = DetailPenumpang::where('pemesanan_id', $pemesanan->id)->get();

            foreach ($detailPenumpang as $index => $penumpang) {
                $nomorKursi = $validated['kursi'][$index] ?? null;

                if ($nomorKursi) {
                    // SIMPAN KE KURSI_TERPESAN dengan status 'terpesan' (PERMANEN LOCKED)
                    KursiTerpesan::create([
                        'jadwal_id' => $pemesanan->jadwal_id,
                        'nomor_kursi' => $nomorKursi,
                        'detail_penumpang_id' => $penumpang->id,
                        'pemesanan_id' => $pemesanan->id,
                        'status' => 'terpesan'
                    ]);

                    // Update nomor kursi di detail penumpang
                    $penumpang->update(['nomor_kursi' => $nomorKursi]);
                }
            }

            // Update timestamp pemesanan
            $pemesanan->touch();

            DB::commit();

            // Redirect ke detail pesanan dengan pesan sukses
            return redirect()->route('customer.detail_pemesanan', ['kode_booking' => $pemesanan->kode_booking])
                ->with('success', 'Kursi berhasil dipilih dan terkunci! Silakan lanjutkan ke pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in prosesPemilihanKursi: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal memilih kursi: ' . $e->getMessage());
        }
    }

    /**
     * Halaman riwayat pemesanan
     */
    public function showRiwayat(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();
        $riwayat = Pemesanan::with([
            'jadwal.shuttle',
            'jadwal.rutes',
            'detailPenumpang',
            'pembayaran' // Eager load pembayaran untuk status dinamis
        ])
        ->where('customer_id', $user->id)
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
    public function showDetailPemesanan($kode_booking)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();
        $pemesanan = Pemesanan::with([
            'jadwal.shuttle',
            'detailPenumpang',
            'transaksi'
        ])->where('kode_booking', $kode_booking)
        ->where('customer_id', $user->id)
        ->first();

        if (!$pemesanan) {
            return redirect()->route('customer.riwayat')
                ->with('error', 'Pemesanan tidak ditemukan.');
        }

        // Prepare data for the view
        $jadwal = $pemesanan->jadwal;
        $rute = $jadwal->rutes->first();

        $from = $rute ? $rute->kota_asal : 'Kota Asal';
        $to = $rute ? $rute->kota_tujuan : 'Kota Tujuan';
        $date = $jadwal->tanggal_keberangkatan;
        $time = $jadwal->waktu_keberangkatan;

        $customer_name = $pemesanan->nama_pemesan;
        $customer_phone = $pemesanan->telepon_pemesan;
        $customer_email = $pemesanan->email_pemesan;

        $penumpang = $pemesanan->detailPenumpang;
        $total = $pemesanan->total_bayar;

        return view('customer.detail_pesanan', [
            'pemesanan' => $pemesanan,
            'user' => $user,
            'from' => $from,
            'to' => $to,
            'date' => $date,
            'time' => $time,
            'customer_name' => $customer_name,
            'customer_phone' => $customer_phone,
            'customer_email' => $customer_email,
            'penumpang' => $penumpang,
            'total' => $total
        ]);
    }

    /**
     * Batalkan pemesanan
     */
    public function batalkanPemesanan($kode_booking)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        try {
            $pemesanan = Pemesanan::where('kode_booking', $kode_booking)
                ->where('customer_id', Auth::id())
                ->first();

            if (!$pemesanan) {
                return redirect()->route('customer.riwayat')
                    ->with('error', 'Pemesanan tidak ditemukan.');
            }

            if (!in_array($pemesanan->status, ['menunggu_konfirmasi', 'menunggu_pembayaran'])) {
                return redirect()->back()
                    ->with('error', 'Pemesanan tidak dapat dibatalkan karena sudah diproses.');
            }

            $jadwal = $pemesanan->jadwal;
            $jadwal->kursi_tersedia += $pemesanan->jumlah_penumpang;

            if ($jadwal->status === 'tidak_tersedia') {
                $jadwal->status = 'tersedia';
            }

            $jadwal->save();

            $pemesanan->status = 'dibatalkan';
            $pemesanan->status_pembayaran = 'dibatalkan';
            $pemesanan->save();

            if ($pemesanan->transaksi) {
                $pemesanan->transaksi->status = 'dibatalkan';
                $pemesanan->transaksi->save();
            }

            return redirect()->route('customer.riwayat')
                ->with('success', 'Pemesanan berhasil dibatalkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Halaman profil customer
     */
    public function profil()
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();
        $riwayatTerbaru = Pemesanan::where('customer_id', $user->id)
            ->with(['jadwal', 'outletAsal', 'outletTujuan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('customer.dashboardprofile', [
            'user' => $user,
            'riwayatTerbaru' => $riwayatTerbaru
        ]);
    }

    /**
     * Halaman profil customer dengan avatar
     */
    public function profilDetail()
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();
        return view('customer.profilcust', ['user' => $user]);
    }

    /**
     * Update profil dengan upload avatar - PERBAIKAN UTAMA
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        // Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:16|min:16',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048', // Max 2MB
        ], [
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus JPG, JPEG, PNG, GIF, atau WebP',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
            'nik.min' => 'NIK harus 16 digit',
            'nik.max' => 'NIK harus 16 digit',
        ]);

        DB::beginTransaction();

        try {
            // Update data user
            $user->name = $validated['name'];
            $user->username = $validated['username'] ?? $user->username;
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? $user->phone;
            $user->nik = $validated['nik'] ?? $user->nik;
            $user->tanggal_lahir = $validated['tanggal_lahir'] ?? $user->tanggal_lahir;
            $user->jenis_kelamin = $validated['jenis_kelamin'] ?? $user->jenis_kelamin;

            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = bcrypt($validated['password']);
            }

            // PERBAIKAN UTAMA: Handle upload avatar dengan benar
            if ($request->hasFile('avatar')) {
                // ✅ 1. Hapus avatar lama jika ada
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                
                // ✅ 2. Simpan file fisik ke storage/app/public/avatars
                $avatarPath = $request->avatar->store('avatars', 'public');
                // Hasil: "avatars/1738234567.jpg" (file benar-benar tersimpan)
                
                // ✅ 3. Simpan path ke database
                $user->avatar = $avatarPath;
            }

            $user->save();

            DB::commit();

            // Update session dengan avatar baru
            session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'nik' => $user->nik,
                'avatar' => $user->getSafeAvatarUrl(), // Gunakan method getSafeAvatarUrl
                'membership_status' => $user->membership_status,
                'membership_level' => $user->membership_level,
            ]);

            return redirect()->route('customer.profilcust')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update profile error: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Gagal memperbarui profil: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Upload avatar secara langsung (AJAX) - VERSI SUDAH BENAR
     */
    public function uploadAvatar(Request $request)
    {
        \Log::info('Upload Avatar Request:', $request->all());

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        // Validasi
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ], [
            'avatar.required' => 'Pilih file gambar terlebih dahulu',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus JPG, JPEG, PNG, GIF, atau WebP',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            \Log::error('Avatar validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        DB::beginTransaction();

        try {
            $file = $request->file('avatar');
            \Log::info('File uploaded:', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);

            // Generate nama file unik
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Simpan file ke storage public
            $path = $file->storeAs('avatars', $filename, 'public');
            
            \Log::info('File saved to:', ['path' => $path]);

            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
                \Log::info('Old avatar deleted:', ['old_path' => $user->avatar]);
            }

            // Update database - PERBAIKAN: Pastikan field avatar di-update
            $user->avatar = $path;
            $user->save();

            DB::commit();

            \Log::info('Avatar updated in database:', [
                'user_id' => $user->id,
                'avatar_path' => $path,
                'avatar_url' => $user->getSafeAvatarUrl()
            ]);

            // Update session dengan data fresh
            $request->session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'nik' => $user->nik,
                'avatar' => $user->getSafeAvatarUrl(),
                'membership_status' => $user->membership_status,
                'membership_level' => $user->membership_level,
            ]);

            // Juga update session langsung untuk header
            session(['avatar_url' => $user->getSafeAvatarUrl()]);
            session(['user_initials' => $user->initials]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupload',
                'avatar_url' => $user->getSafeAvatarUrl(),
                'has_avatar' => !empty($user->avatar),
                'initials' => $user->initials
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Upload avatar error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus avatar (AJAX)
     */
    public function deleteAvatar(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        DB::beginTransaction();

        try {
            // Hapus avatar dari storage jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Set avatar ke null di database
            $user->avatar = null;
            $user->save();

            DB::commit();

            // Update session
            session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'nik' => $user->nik,
                'avatar' => null,
                'membership_status' => $user->membership_status,
                'membership_level' => $user->membership_level,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus',
                'initials' => $user->initials,
                'avatar_url' => asset('images/default-avatar.png')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete avatar error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto profil'
            ], 500);
        }
    }

    /**
     * Halaman membership
     */
    public function membership()
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        if ($user->membership_status === 'non_member') {
            return view('customer.membership_non_member', ['user' => $user]);
        }

        if ($user->membership_status === 'pending') {
            $pendingPayment = MembershipPayment::where('user_id', $user->id)
                ->where('payment_status', 'pending')
                ->where('waktu_kadaluarsa', '>', now())
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$pendingPayment) {
                return redirect()->route('customer.membership.payment');
            }

            return view('customer.membership_pending', [
                'user' => $user,
                'pendingPayment' => $pendingPayment
            ]);
        }

        $membershipLevel = $user->membership_level ?? 'Bronze';
        $currentPoints = $user->member_point ?? 0;
        $loyaltyPoints = $user->loyalty_point ?? 0;

        $levelRanges = [
            'Bronze' => ['min' => 0, 'max' => 1000],
            'Silver' => ['min' => 1000, 'max' => 2500],
            'Gold' => ['min' => 2500, 'max' => 4500],
            'Platinum' => ['min' => 4500, 'max' => 6000],
        ];

        $levels = ['Bronze', 'Silver', 'Gold', 'Platinum'];
        $currentIndex = array_search($membershipLevel, $levels);
        $nextLevel = $currentIndex < count($levels) - 1 ? $levels[$currentIndex + 1] : 'Platinum';

        $currentMin = $levelRanges[$membershipLevel]['min'];
        $currentMax = $levelRanges[$membershipLevel]['max'];

        if ($currentPoints >= $currentMax) {
            $progressPercentage = 100;
        } elseif ($currentPoints <= $currentMin) {
            $progressPercentage = 0;
        } else {
            $progressPercentage = (($currentPoints - $currentMin) / ($currentMax - $currentMin)) * 100;
        }

        $pointsNeeded = 0;
        if ($membershipLevel !== 'Platinum') {
            $nextMin = $levelRanges[$nextLevel]['min'];
            $pointsNeeded = $nextMin - $currentPoints;
            if ($pointsNeeded < 0) $pointsNeeded = 0;
        }

        $daysRemaining = 0;
        if ($user->membership_end_date) {
            $daysRemaining = max(0, Carbon::parse($user->membership_end_date)->diffInDays(Carbon::now(), false));
        }

        $membership = (object) [
            'level' => $membershipLevel,
            'points' => $currentPoints,
            'loyalty_points' => $loyaltyPoints,
        ];

        return view('customer.membership', [
            'user' => $user,
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
            'membershipStartDate' => $user->membership_start_date,
            'membershipEndDate' => $user->membership_end_date,
        ]);
    }

    /**
     * Form pendaftaran membership
     */
    public function showMembershipForm()
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        if ($user->membership_status !== 'non_member') {
            return redirect()->route('customer.membership')
                ->with('info', 'Anda sudah terdaftar sebagai member.');
        }

        return view('customer.membership_form', ['user' => $user]);
    }

    /**
     * Proses pendaftaran membership
     */
    public function processMembershipRegistration(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'birthdate' => 'required|date|before:-17 years',
            'gender' => 'required|in:L,P',
            'agree_terms' => 'required|accepted',
        ], [
            'birthdate.before' => 'Anda harus berusia minimal 17 tahun untuk mendaftar membership.',
            'agree_terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan membership.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'tanggal_lahir' => $request->birthdate,
                'jenis_kelamin' => $request->gender,
                'membership_status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('customer.membership')
                ->with('success', 'Data berhasil disimpan! Silakan lanjutkan ke pembayaran membership.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Halaman pembayaran membership
     */
    public function showMembershipPayment()
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        if ($user->membership_status !== 'pending') {
            return redirect()->route('customer.membership')
                ->with('error', 'Anda tidak memiliki pembayaran membership yang tertunda.');
        }

        $existingPayment = MembershipPayment::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->where('waktu_kadaluarsa', '>', now())
            ->first();

        if ($existingPayment) {
            $metodePembayaran = MetodePembayaran::where('aktif', true)
                ->orderBy('urutan', 'asc')
                ->get();

            return view('customer.membership_payment', [
                'user' => $user,
                'payment' => $existingPayment,
                'metodePembayaran' => $metodePembayaran
            ]);
        }

        DB::beginTransaction();

        try {
            $amount = 100000;
            $transactionId = MembershipPayment::generateTransactionId();

            $payment = MembershipPayment::create([
                'user_id' => $user->id,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'discount' => 0,
                'total_amount' => $amount,
                'payment_status' => 'pending',
                'waktu_kadaluarsa' => now()->addHours(24),
            ]);

            DB::commit();

            $metodePembayaran = MetodePembayaran::where('aktif', true)
                ->orderBy('urutan', 'asc')
                ->get();

            return view('customer.membership_payment', [
                'user' => $user,
                'payment' => $payment,
                'metodePembayaran' => $metodePembayaran
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('customer.membership')
                ->with('error', 'Terjadi kesalahan saat membuat pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Proses pembayaran membership
     */
    public function processMembershipPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        // Validation rules - make manual transfer fields optional for online payments
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:membership_payments,transaction_id',
            'payment_method' => 'required|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_pengirim' => 'nullable|string|max:255',
            'tanggal_transfer' => 'nullable|date',
            'jumlah_transfer' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $payment = MembershipPayment::where('transaction_id', $request->transaction_id)
                ->where('user_id', $user->id)
                ->first();

            if (!$payment || $payment->payment_status !== 'pending' || $payment->isExpired()) {
                throw new \Exception('Transaksi tidak valid atau sudah kadaluarsa.');
            }

            $buktiPembayaran = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = 'membership_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('membership_payments', $filename, 'public');
                $buktiPembayaran = $path;
            }

            // Handle different payment methods
            $paymentStatus = 'pending'; // Default for manual transfer
            $paylabsResponse = null;

            // For Paylabs payments (QRIS and VA), create payment with Paylabs
            if (in_array($request->payment_method, ['qris', 'bca_va', 'mandiri_va', 'bni_va', 'bri_va'])) {
                try {
                    // Map payment method to Paylabs channel code
                    $channelMap = [
                        'qris' => 'QRIS',
                        'bca_va' => 'VA_BCA',
                        'mandiri_va' => 'VA_MANDIRI',
                        'bni_va' => 'VA_BNI',
                        'bri_va' => 'VA_BRI',
                    ];

                    $channelCode = $channelMap[$request->payment_method] ?? 'QRIS';

                    // Create Paylabs payment
                    $paylabsResponse = $this->paylabsService->createPayment($payment, $channelCode, ucfirst(str_replace('_', ' ', $request->payment_method)));

                    if ($paylabsResponse['success']) {
                        // For online payments, mark as success immediately (simulated)
                        // In production, this would wait for webhook callback
                        $paymentStatus = 'success';
                    } else {
                        throw new \Exception('Gagal membuat pembayaran Paylabs: ' . $paylabsResponse['error']);
                    }
                } catch (\Exception $e) {
                    Log::error('Paylabs payment creation failed: ' . $e->getMessage());
                    throw new \Exception('Gagal memproses pembayaran online. Silakan coba lagi.');
                }
            } elseif ($request->payment_method === 'manual_transfer') {
                $paymentStatus = 'pending'; // Wait for admin approval
            }

            $payment->update([
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'bukti_pembayaran' => $buktiPembayaran,
                'nama_pengirim' => $request->nama_pengirim,
                'tanggal_transfer' => $request->tanggal_transfer,
                'jumlah_transfer' => $request->jumlah_transfer,
                'paid_at' => $paymentStatus === 'success' ? now() : null,
                // Add Paylabs fields if available
                'paylabs_transaction_id' => $paylabsResponse['transaction_id'] ?? null,
                'qr_code' => $paylabsResponse['payment_data']['qrCode'] ?? null,
                'qris_url' => $paylabsResponse['payment_data']['qrisUrl'] ?? null,
                'no_virtual_account' => $paylabsResponse['payment_data']['vaCode'] ?? $paylabsResponse['payment_data']['vaNumber'] ?? null,
            ]);

            // Only activate membership for successful online payments or approved manual transfers
            if ($paymentStatus === 'success') {
                $user->update([
                    'membership_status' => 'active',
                    'membership_start_date' => now(),
                    'membership_end_date' => now()->addMonths(12),
                    'membership_fee' => $payment->total_amount,
                    'membership_payment_method' => $request->payment_method,
                    'membership_payment_status' => 'success',
                    'membership_transaction_id' => $payment->transaction_id,
                    'membership_level' => 'Bronze',
                    'member_point' => 0,
                    'loyalty_point' => 0,
                ]);

                session()->put('user', [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar_url,
                    'membership_status' => 'active',
                    'membership_level' => 'Bronze',
                ]);

                DB::commit();

                return redirect()->route('customer.membership')
                    ->with('success', 'Pembayaran berhasil! Membership Anda sekarang aktif.');
            } else {
                // For manual transfer, keep status as pending
                DB::commit();

                return redirect()->route('customer.membership')
                    ->with('info', 'Pembayaran telah dikirim dan menunggu konfirmasi admin. Status membership akan aktif setelah diverifikasi.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Gunakan loyalty points untuk diskon
     */
    public function useLoyaltyPoints(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        if (!$user->isMemberActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus menjadi member aktif untuk menggunakan loyalty points.'
            ]);
        }

        if ($user->loyalty_point < 50) {
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

        $discount = $user->calculateDiscountFromLoyaltyPoints($totalAmount);

        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghitung diskon.'
            ]);
        }

        $pointsUsed = 0;
        if ($user->loyalty_point >= 150) {
            $pointsUsed = 150;
        } elseif ($user->loyalty_point >= 100) {
            $pointsUsed = 100;
        } else {
            $pointsUsed = 50;
        }

        session()->put('loyalty_discount', [
            'user_id' => $user->id,
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
            'remaining_points' => $user->loyalty_point - $pointsUsed
        ]);
    }

    /**
     * Hapus loyalty discount
     */
    public function removeLoyaltyDiscount(Request $request)
    {
        session()->forget('loyalty_discount');
        return response()->json([
            'success' => true,
            'message' => 'Loyalty discount berhasil dihapus.'
        ]);
    }

    /**
     * Update points (untuk testing/admin)
     */
    public function updatePoints(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $validated = $request->validate([
            'points' => 'required|integer|min:0',
            'loyalty_points' => 'required|integer|min:0',
            'membership_level' => 'required|in:Bronze,Silver,Gold,Platinum'
        ]);

        try {
            $user->member_point = $validated['points'];
            $user->loyalty_point = $validated['loyalty_points'];
            $user->membership_level = $validated['membership_level'];
            $user->save();

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
        $faqs = Faq::where('status', 'aktif')
            ->orderBy('urutan', 'asc')
            ->get();

        $kontakSupport = MProfilePerusahaan::select('telepon', 'email', 'alamat')
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
        $syaratKetentuan = SyaratKetentuan::getUntukPengguna();
        return view('customer.syarat_ketentuan', compact('user', 'syaratKetentuan'));
    }

    /**
     * Halaman kebijakan privasi
     */
    public function kebijakanPrivasi()
    {
        $user = session()->get('user', []);
        $kebijakanPrivasi = KebijakanPrivasi::getAktif();
        return view('customer.kebijakan_privasi', compact('user', 'kebijakanPrivasi'));
    }

    /**
     * Halaman kontak
     */
    public function contact()
    {
        $user = session()->get('user');
        $masterKontak = MMasterKontak::where('status', 'active')->first();

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
            }
        }

        if (isset($masterKontak->jam_operasional) && is_string($masterKontak->jam_operasional)) {
            $masterKontak->jam_operasional = json_decode($masterKontak->jam_operasional, true);
        }

        return view('customer.contact', compact('user', 'masterKontak'));
    }

    /**
     * Proses pengiriman pesan kontak
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
            PesanKontak::create([
                'nama_pengirim' => $request->nama,
                'email_pengirim' => $request->email,
                'nomor_telepon' => $request->telepon,
                'pesan' => $request->pesan,
                'status' => 'terkirim',
            ]);

            return redirect()->back()
                ->with('success', 'Pesan Anda telah berhasil dikirim! Kami akan menghubungi Anda dalam waktu 1x24 jam.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.')
                ->withInput();
        }
    }

    /**
     * API: return policy content for AJAX modal
     * type: 'privacy' or 'terms'
     */
    public function getPolicy(Request $request, $type)
    {
        $type = strtolower($type);
        if (in_array($type, ['privacy', 'kebijakan', 'kebijakan-privasi'])) {
            $k = KebijakanPrivasi::getAktif();
            $title = $k->kp_judul ?? 'Kebijakan Privasi';
            $content = $k->kp_konten_html ?? '';
        } else {
            // default to terms
            $s = SyaratKetentuan::getUntukPengguna();
            $title = $s->sk_judul ?? 'Syarat & Ketentuan';
            $content = $s->sk_konten_html ?? '';
        }

        return response()->json([
            'title' => $title,
            'content' => $content,
        ]);
    }

    /**
 * Method aman untuk mengambil data dengan fallback
 */
public static function getAktif()
{
    try {
        // Cek apakah tabel ada
        if (!Schema::hasTable('master_harga')) {
            \Log::warning('Tabel master_harga tidak ditemukan di database');
            return null;
        }
        
        return self::aktif()->first();
    } catch (\Exception $e) {
        \Log::warning('Error mengambil master harga aktif: ' . $e->getMessage());
        return null;
    }
}

    /**
     * Cek harga paket (AJAX)
     */
    public function cekHargaPaket(Request $request)
    {
        \Log::info('Cek Harga Paket Request:', $request->all());

        try {
            $validated = $request->validate([
                'asal' => 'required|string',
                'tujuan' => 'required|string',
                'berat' => 'required|numeric|min:0.1',
                'panjang' => 'nullable|numeric|min:0',
                'lebar' => 'nullable|numeric|min:0',
                'tinggi' => 'nullable|numeric|min:0',
            ]);

            if ($validated['asal'] === $validated['tujuan']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kota asal dan tujuan tidak boleh sama!'
                ]);
            }

            $hargaPaket = HargaPaket::findHarga($validated['asal'], $validated['tujuan']);

            if (!$hargaPaket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Layanan pengiriman untuk rute ini belum tersedia.'
                ]);
            }

            $berat = floatval($validated['berat']);
            $panjang = floatval($validated['panjang'] ?? 0);
            $lebar = floatval($validated['lebar'] ?? 0);
            $tinggi = floatval($validated['tinggi'] ?? 0);

            $perhitungan = $hargaPaket->calculateHarga($berat, $panjang, $lebar, $tinggi);

            return response()->json([
                'success' => true,
                'data' => [
                    'kota_asal' => $validated['asal'],
                    'kota_tujuan' => $validated['tujuan'],
                    'berat_aktual' => number_format($perhitungan['berat_aktual'], 2, ',', '.'),
                    'berat_volumetric' => number_format($perhitungan['berat_volumetric'], 2, ',', '.'),
                    'berat_terpakai' => number_format($perhitungan['berat_terpakai'], 2, ',', '.'),
                    'harga_per_kg' => 'Rp ' . number_format($hargaPaket->harga_per_kg, 0, ',', '.'),
                    'harga_minimum' => 'Rp ' . number_format($hargaPaket->harga_minimum, 0, ',', '.'),
                    'harga_total' => 'Rp ' . number_format($perhitungan['harga_total'], 0, ',', '.'),
                    'harga_total_raw' => $perhitungan['harga_total'],
                    'estimasi_hari' => $perhitungan['estimasi_hari'],
                    'kode_harga' => $perhitungan['kode_harga'],
                    'keterangan' => $hargaPaket->keterangan
                ],
                'message' => 'Harga berhasil dihitung!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error cek harga paket:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Proses kirim paket
     */
    public function prosesKirimPaket(Request $request)
    {
        \Log::info('Proses Kirim Paket Request:', $request->all());

        try {
            $validated = $request->validate([
                'nama_pengirim' => 'required|string|max:100',
                'telepon_pengirim' => 'required|string|max:20',
                'email_pengirim' => 'nullable|email|max:100',
                'nama_penerima' => 'required|string|max:100',
                'telepon_penerima' => 'required|string|max:20',
                'alamat_tujuan' => 'nullable|string|max:255',
                'kota_asal' => 'required|string|max:100',
                'kota_tujuan' => 'required|string|max:100',
                'berat' => 'required|numeric|min:0.1|max:100', // max 100 kg
                'jarak' => 'required|numeric|min:1|max:1000', // max 1000 km
                'catatan' => 'nullable|string|max:500',
                'harga_total' => 'required|numeric',
                'kode_harga' => 'required|string'
            ]);

            $hargaPaket = HargaPaket::where('kode_harga', $validated['kode_harga'])->first();

            if (!$hargaPaket) {
                return redirect()->back()
                    ->with('error', 'Data harga tidak valid!')
                    ->withInput();
            }

            DB::beginTransaction();

            $kodeResi = 'PKT' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));

            $pengiriman = PengirimanPaket::create([
                'kode_resi' => $kodeResi,
                'user_id' => Auth::id(),
                'nama_pengirim' => $validated['nama_pengirim'],
                'telepon_pengirim' => $validated['telepon_pengirim'],
                'email_pengirim' => $validated['email_pengirim'],
                'nama_penerima' => $validated['nama_penerima'],
                'telepon_penerima' => $validated['telepon_penerima'],
                'kota_asal' => $validated['kota_asal'],
                'kota_tujuan' => $validated['kota_tujuan'],
                'berat' => $validated['berat'],
                'panjang' => $validated['panjang'] ?? 0,
                'lebar' => $validated['lebar'] ?? 0,
                'tinggi' => $validated['tinggi'] ?? 0,
                'keterangan' => $validated['catatan'] ?? null,
                'harga_total' => $validated['harga_total'],
                'kode_harga' => $validated['kode_harga'],
                'status' => 'pending',
                'tanggal_pengiriman' => now(),
            ]);

            DB::commit();

            return redirect()->route('customer.beranda')
                ->with('success', 'Paket berhasil diproses! Kode Resi: ' . $kodeResi);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error proses kirim paket:', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Gagal memproses paket: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store review with strict auth validation
     */
    public function storeReview(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'required|string|min:10|max:500'
            ]);

            // Cek apakah user sudah login (backend validation)
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu untuk memberikan review',
                    'requiresLogin' => true
                ], 401);
            }

            // Cek apakah user sudah memberikan review hari ini (optional)
            $todayReview = Review::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->first();

            if ($todayReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memberikan review hari ini. Silakan coba lagi besok.'
                ], 400);
            }

            // Simpan review ke database dengan status approved langsung
            $review = Review::create([
                'user_id' => auth()->id(),
                'rating' => $validated['rating'],
                'review' => $validated['review'],
                'status' => 'approved'
            ]);

            // Load user data untuk response
            $review->load('user');

            // Data untuk response
            $reviewData = [
                'id' => $review->id,
                'user_name' => $review->user->name,
                'avatar' => $review->user?->avatar_url ?? null,
                'rating' => $review->rating,
                'content' => $review->review,
                'date' => $review->created_at->format('d M Y')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dikirim!',
                'review' => $reviewData
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews for AJAX (baru)
     */
    public function getReviews(Request $request)
    {
        try {
            $reviews = Review::with('user')
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($review) {
                    return [
                        'id' => $review->id,
                        'user_name' => $review->user->name ?? 'User',
                        'avatar' => $review->user?->avatar_url ?? null,
                        'rating' => $review->rating,
                        'content' => $review->review,
                        'date' => $review->created_at->format('d M Y')
                    ];
                });

            return response()->json([
                'success' => true,
                'reviews' => $reviews,
                'total' => $reviews->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get review statistics (jumlah per rating)
     */
    public function getReviewStats(Request $request)
    {
        try {
            // Hitung statistik dari database
            $stats = [
                5 => Review::where('status', 'approved')->where('rating', 5)->count(),
                4 => Review::where('status', 'approved')->where('rating', 4)->count(),
                3 => Review::where('status', 'approved')->where('rating', 3)->count(),
                2 => Review::where('status', 'approved')->where('rating', 2)->count(),
                1 => Review::where('status', 'approved')->where('rating', 1)->count(),
            ];

            $totalReviews = array_sum($stats);
            $averageRating = Review::where('status', 'approved')->avg('rating') ?? 0;

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'totalReviews' => $totalReviews,
                'averageRating' => round($averageRating, 1),
                'percentage5Star' => $totalReviews > 0 ? round(($stats[5] / $totalReviews) * 100) : 0
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik review'
            ], 500);
        }
    }

    /**
     * Get filtered reviews by rating
     */
    public function getFilteredReviews(Request $request)
    {
        try {
            $rating = $request->input('rating', 0);

            $query = Review::with('user')
                ->where('status', 'approved');

            if ($rating > 0) {
                $query->where('rating', $rating);
            }

            $reviews = $query->orderBy('created_at', 'desc')
                ->get()
                ->map(function($review) {
                    return [
                        'id' => $review->id,
                        'user_name' => $review->user->name ?? 'User',
                        'avatar' => $review->user?->avatar_url ?? null,
                        'rating' => $review->rating,
                        'content' => $review->review,
                        'date' => $review->created_at->format('d M Y')
                    ];
                });

            return response()->json([
                'success' => true,
                'reviews' => $reviews,
                'total' => $reviews->count(),
                'filteredRating' => $rating
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat review'
            ], 500);
        }
    }

    /**
     * Get all promos (AJAX)
     */
    public function getPromos(Request $request)
    {
        try {
            // Get current date
            $now = Carbon::now();

            // Get all active promos
            $activePromos = Promo::where('status', true)
                ->whereDate('tanggal_mulai', '<=', $now)
                ->whereDate('tanggal_berakhir', '>=', $now)
                ->where(function($query) {
                    $query->whereNull('kuota')
                        ->orWhereRaw('terpakai < kuota');
                })
                ->orderBy('tanggal_berakhir', 'asc')
                ->get();

            // Get inactive/expired promos (within last 30 days)
            $thirtyDaysAgo = Carbon::now()->subDays(30);
            $inactivePromos = Promo::where(function($query) use ($now) {
                    $query->where('status', false)
                        ->orWhere('tanggal_berakhir', '<', $now);
                })
                ->where('tanggal_berakhir', '>=', $thirtyDaysAgo)
                ->orderBy('tanggal_berakhir', 'desc')
                ->get();

            // Combine and format promos
            $promos = [];

            // Format active promos
            foreach ($activePromos as $promo) {
                $promos[] = $this->formatPromoData($promo, true);
            }

            // Format inactive promos
            foreach ($inactivePromos as $promo) {
                $promos[] = $this->formatPromoData($promo, false);
            }

            return response()->json([
                'success' => true,
                'promos' => $promos,
                'message' => 'Promos loaded successfully',
                'total_active' => $activePromos->count(),
                'total_inactive' => $inactivePromos->count(),
                'total' => count($promos)
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting promos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load promos: ' . $e->getMessage(),
                'promos' => [],
                'total_active' => 0,
                'total_inactive' => 0,
                'total' => 0
            ], 500);
        }
    }

    /**
     * Format promo data for JSON response
     */
    private function formatPromoData($promo, $isActive)
    {
        $now = Carbon::now();
        $endDate = Carbon::parse($promo->tanggal_berakhir);
        $isExpired = $endDate < $now;
        $quotaExceeded = $promo->kuota && $promo->terpakai >= $promo->kuota;
        $canUse = $isActive && !$isExpired && !$quotaExceeded;

        return [
            'id' => $promo->id,
            'kode_promo' => $promo->kode_promo,
            'nama_promo' => $promo->nama_promo,
            'jenis_diskon' => $promo->jenis_diskon,
            'nilai_diskon' => (float) $promo->nilai_diskon,
            'maksimal_diskon' => (float) $promo->maksimal_diskon,
            'minimal_pembelian' => (float) $promo->minimal_pembelian,
            'tanggal_mulai' => $promo->tanggal_mulai->format('Y-m-d'),
            'tanggal_berakhir' => $promo->tanggal_berakhir->format('Y-m-d'),
            'kuota' => $promo->kuota,
            'terpakai' => $promo->terpakai,
            'status' => $promo->status,
            'deskripsi' => $promo->deskripsi,
            'tipe_promo' => $promo->tipe_promo,
            'is_active' => $isActive,
            'is_expired' => $isExpired,
            'quota_exceeded' => $quotaExceeded,
            'can_use' => $canUse,
            'remaining_quota' => $promo->kuota ? ($promo->kuota - $promo->terpakai) : null,
            'days_remaining' => $isActive ? max(0, $now->diffInDays($endDate, false)) : 0
        ];
    }

    /**
     * SmartSend page
     */
    public function smartsend()
    {
        $profile = MProfilePerusahaan::first();
        $user = session()->get('user', null);
        // Make status check case-insensitive
        $outlets = Outlet::whereRaw('LOWER(status) = ?', ['aktif'])->get();
        $outletsGrouped = $outlets->groupBy('kota');
    
        $layanan = []; // Tambahkan layanan jika ada
        
        $promos = Promo::where('status', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($promo) {
                return [
                    'id' => $promo->id,
                    'nama' => $promo->nama_promo,
                    'deskripsi' => $promo->deskripsi,
                    'gambar' => asset('storage/' . $promo->gambar_promo),
                    'periode' => Carbon::parse($promo->tanggal_mulai)->format('d M') . ' - ' . Carbon::parse($promo->tanggal_selesai)->format('d M Y'),
                ];
            })
            ->toArray();

        if (empty($promos)) {
            $promos = [
                [
                    'id' => 1,
                    'nama' => 'Diskon 30% Shuttle',
                    'deskripsi' => 'Nikmati diskon 30% untuk semua rute shuttle reguler. Berlaku untuk pemesanan minimal 2 tiket.',
                    'gambar' => asset('images/promo1.jpg'),
                    'periode' => '1 Mar - 31 Mar 2024',
                ]
            ];
        }

        // Data artikel
        $artikelsFromDB = Artikel::orderBy('tanggal_publikasi', 'desc')->take(3)->get();
        $articles = [];

        foreach ($artikelsFromDB as $artikel) {
            $articles[] = [
                'id' => $artikel->id,
                'image' => asset('images/default-article.jpg'),
                'category' => $artikel->kategori,
                'title' => $artikel->judul,
                'excerpt' => substr(strip_tags($artikel->konten), 0, 100) . '...',
                'date' => Carbon::parse($artikel->tanggal_publikasi)->translatedFormat('d F Y'),
                'read_time' => '5 min read',
                'tags' => explode(', ', $artikel->meta_keywords),
                'full_content' => $artikel->konten,
                'author' => $artikel->penulis
            ];
        }

        if (empty($articles)) {
            $articles = [
                [
                    'id' => 1,
                    'image' => asset('images/default-article.jpg'),
                    'category' => 'Tips & Trik',
                    'title' => 'Tips Perjalanan Aman dengan Shuttle Selama Liburan',
                    'excerpt' => 'Pelajari cara mempersiapkan perjalanan shuttle yang aman dan nyaman selama musim liburan untuk pengalaman terbaik.',
                    'date' => '15 Maret 2024',
                    'read_time' => '5 min read',
                    'tags' => ['Perjalanan', 'Tips', 'Liburan'],
                    'full_content' => '<h3>Persiapan Sebelum Perjalanan</h3><p>Perjalanan dengan shuttle selama liburan memerlukan persiapan yang matang. Pastikan Anda memesan tiket jauh-jauh hari untuk mendapatkan harga terbaik dan kursi pilihan. Smart Shuttle menawarkan pemesanan online yang mudah melalui website atau aplikasi kami.</p>',
                    'author' => 'Admin SmartShuttle'
                ]
            ];
        }

        $activeService = 'kirim-paket'; // Set default untuk SmartSend

        return view('customer.smartsend', compact(
            'profile', 
            'user', 
            'outlets',     
            'outletsGrouped', 
            'layanan', 
            'promos', 
            'articles', 
            'activeService'
        ));
    }

    /**
     * Halaman form cek resi
     */
    public function cekResi()
    {
        $user = session()->get('user', null);
        $profile = MProfilePerusahaan::first();
        
        return view('customer.cek_resi', compact('user', 'profile'));
    }

    /**
     * Proses validasi kode resi
     */
    public function prosesCekResi(Request $request)
    {
        $validated = $request->validate([
            'kode_resi' => 'required|string|max:20'
        ]);
        
        // Cari shipment berdasarkan kode resi (case insensitive)
        $shipment = Shipment::whereRaw('LOWER(kode_resi) = ?', [strtolower(trim($validated['kode_resi']))])->first();
        
        if (!$shipment) {
            return redirect()->route('customer.cek-resi')
                ->withErrors([
                    'kode_resi' => 'Kode resi "' . $validated['kode_resi'] . '" tidak ditemukan. Pastikan kode resi sudah benar.'
                ])
                ->withInput();
        }
        
        // Redirect ke halaman detail paket
        return redirect()->route('customer.detail-paket', ['kode_resi' => $shipment->kode_resi])
            ->with('success', 'Paket ditemukan!');
    }

    /**
     * Buat pengiriman paket baru dengan LOGIC HARGA BARU
     */
    public function buatPengirimanPaket(Request $request)
    {
        $validated = $request->validate([
            'nama_pengirim' => 'required|string|max:100',
            'telepon_pengirim' => 'required|string|max:20',
            'email_pengirim' => 'nullable|email|max:100',
            'nama_penerima' => 'required|string|max:100',
            'telepon_penerima' => 'required|string|max:20',
            'alamat_tujuan' => 'nullable|string|max:255',
            'kota_asal' => 'required|string|max:100',
            'kota_tujuan' => 'required|string|max:100',
            'berat' => 'required|numeric|min:0.1|max:100', // max 100 kg
            'jarak' => 'required|numeric|min:1|max:1000', // max 1000 km
            'catatan' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Generate kode resi baru dengan format ss-YYYYMMDD-XXXX
            $kodeResi = Shipment::generateKodeResi();
            
            // Hitung harga berdasarkan LOGIC BARU
            $harga = Shipment::hitungHarga($validated['berat'], $validated['jarak']);
            
            // Simpan ke database dengan semua data harga
            $shipment = Shipment::create([
                'kode_resi' => $kodeResi,
                'kota_asal' => $validated['kota_asal'],
                'kota_tujuan' => $validated['kota_tujuan'],
                'berat' => $validated['berat'],
                'jarak' => $validated['jarak'],
                'harga_berat' => $harga['harga_berat'],
                'harga_jarak' => $harga['harga_jarak'],
                'harga_total' => $harga['harga_total'],
                'nama_pengirim' => $validated['nama_pengirim'],
                'telepon_pengirim' => $validated['telepon_pengirim'],
                'nama_penerima' => $validated['nama_penerima'],
                'telepon_penerima' => $validated['telepon_penerima'],
                'alamat_tujuan' => $validated['alamat_tujuan'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'user_id' => auth()->id(),
                'status' => 'diproses',
                'tanggal_dibuat' => now(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Paket berhasil dibuat!',
                'data' => [
                    'kode_resi' => $kodeResi,
                    'redirect_url' => route('customer.detail-paket', ['kode_resi' => $kodeResi])
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal membuat pengiriman paket: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat paket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk cek status paket (AJAX) - UNTUK MODAL
     */
    public function cekStatusPaket(Request $request)
    {
        try {
            $validated = $request->validate([
                'resi' => 'required|string'
            ]);
            
            $shipment = Shipment::whereRaw('LOWER(kode_resi) = ?', [strtolower($validated['resi'])])->first();
            
            if (!$shipment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor resi tidak ditemukan'
                ]);
            }
            
            $status = $shipment->statusLabel;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'resi' => $shipment->kode_resi,
                    'status' => $shipment->status,
                    'status_text' => $status['label'],
                    'status_color' => $status['color'],
                    'kota_asal' => $shipment->kota_asal,
                    'kota_tujuan' => $shipment->kota_tujuan,
                    'nama_penerima' => $shipment->nama_penerima,
                    'berat' => $shipment->berat . ' kg',
                    'jarak' => $shipment->jarak . ' km',
                    'harga_total' => 'Rp ' . number_format($shipment->harga_total, 0, ',', '.'),
                    'tanggal_dibuat' => $shipment->tanggal_dibuat->format('d M Y H:i')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API untuk hitung harga real-time (AJAX)
     */
    public function hitungHargaPaket(Request $request)
    {
        try {
            $validated = $request->validate([
                'berat' => 'required|numeric|min:0.1|max:100',
                'jarak' => 'required|numeric|min:1|max:1000'
            ]);
            
            // Hitung harga dengan LOGIC BARU
            $harga = Shipment::hitungHarga($validated['berat'], $validated['jarak']);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'harga_berat' => 'Rp ' . number_format($harga['harga_berat'], 0, ',', '.'),
                    'harga_jarak' => 'Rp ' . number_format($harga['harga_jarak'], 0, ',', '.'),
                    'harga_total' => 'Rp ' . number_format($harga['harga_total'], 0, ',', '.'),
                    'harga_total_raw' => $harga['harga_total'],
                    'perhitungan' => $this->formatPerhitungan($validated['berat'], $validated['jarak'], $harga)
                ],
                'message' => 'Harga berhasil dihitung!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Helper: Format perhitungan untuk display
     */
    private function formatPerhitungan($berat, $jarak, $harga)
    {
        $text = "Perhitungan Harga:\n";
        
        // Bagian berat
        if ($berat <= 5) {
            $text .= "• Berat {$berat} kg (≤5 kg) = Rp 7.000\n";
        } else {
            $text .= "• Berat {$berat} kg = 5 kg pertama (Rp 7.000) + ";
            $text .= ($berat - 5) . " kg × Rp 2.000 = Rp " . number_format($harga['harga_berat'], 0, ',', '.') . "\n";
        }
        
        // Bagian jarak
        $kelipatan = ceil($jarak / 10);
        $text .= "• Jarak {$jarak} km = {$kelipatan} × 10 km × Rp 2.000 = Rp " . number_format($harga['harga_jarak'], 0, ',', '.') . "\n";
        
        // Total
        $text .= "• Total = Rp " . number_format($harga['harga_berat'], 0, ',', '.') . " + Rp " . number_format($harga['harga_jarak'], 0, ',', '.') . " = Rp " . number_format($harga['harga_total'], 0, ',', '.');
        
        return $text;
    }

    /**
     * API: Get outlet tujuan berdasarkan outlet asal
     */
    public function getOutletTujuanByRute(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'outlet_asal_id' => 'required|integer|exists:outlets,id'
            ]);

            if ($validator->fails()) {
                \Log::warning('Validation failed for getOutletTujuanByRute', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Outlet asal tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            $outletAsalId = $request->outlet_asal_id;

            // 1. Validasi outlet asal ada
            $outletAsal = Outlet::with('branch')->find($outletAsalId);
            if (!$outletAsal) {
                \Log::warning('Outlet asal not found: ' . $outletAsalId);
                return response()->json([
                    'success' => false,
                    'message' => 'Outlet asal tidak ditemukan'
                ], 404);
            }

            \Log::info('Getting destinations for: ' . $outletAsal->nama_outlet . ' (ID: ' . $outletAsalId . ')');

            // 2. Gunakan RuteSegment untuk mendapatkan destination outlets
            $outletTujuan = RuteSegment::getOutletTujuanValid($outletAsalId);

            \Log::info('Found ' . $outletTujuan->count() . ' destination outlets for outlet ' . $outletAsalId);

            // 3. Return success even if empty (user should see "no routes" message)
            return response()->json([
                'success' => true,
                'data' => $outletTujuan->values(),
                'total' => $outletTujuan->count(),
                'outlet_asal' => [
                    'id' => $outletAsal->id,
                    'nama' => $outletAsal->nama_outlet,
                    'kota' => $outletAsal->branch->kota ?? 'Unknown'
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error in getOutletTujuanByRute: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat rute. Silakan coba lagi.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    /**
 * API: KALKULATOR HARGA SMARTSEND (Cek Harga Saja - Read Only)
 */
public function kalkulatorHargaSmartSend(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'outlet_asal_id' => 'required|integer|exists:outlets,id',
            'outlet_tujuan_id' => 'required|integer|exists:outlets,id|different:outlet_asal_id',
            'berat' => 'required|numeric|min:0.1|max:100',
            'panjang' => 'nullable|numeric|min:0',
            'lebar' => 'nullable|numeric|min:0',
            'tinggi' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                'errors' => $validator->errors()
            ], 422);
        }

        $outletAsalId = $request->outlet_asal_id;
        $outletTujuanId = $request->outlet_tujuan_id;

        // 1. Validasi: Apakah rute valid?
        $jarak = \App\Models\RuteSegment::hitungJarak($outletAsalId, $outletTujuanId);
        
        if ($jarak <= 0) {
            \Log::warning('Rute tidak valid dari outlet ' . $outletAsalId . ' ke ' . $outletTujuanId);
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada rute yang tersedia antara kedua outlet yang dipilih'
            ], 404);
        }

        // 2. Hitung berat volumetric
        $beratAktual = floatval($request->berat);
        $volume = floatval($request->panjang ?? 0) * 
                 floatval($request->lebar ?? 0) * 
                 floatval($request->tinggi ?? 0);
        $beratVolumetric = $volume > 0 ? $volume / 6000 : 0;
        $beratTerpakai = max($beratAktual, $beratVolumetric);

        // 3. Coba ambil master harga dari database, jika tidak ada gunakan default
        try {
            $masterHarga = \App\Models\MasterHarga::aktif()->first();
        } catch (\Exception $e) {
            \Log::warning('Gagal mengambil master harga: ' . $e->getMessage());
            $masterHarga = null;
        }

        // Default values jika master_harga tidak ada
        $defaultHarga = [
            'berat_pertama' => 5,
            'harga_berat_pertama' => 7000,
            'harga_berat_berikutnya' => 2000,
            'kelipatan_jarak' => 10,
            'harga_per_kelipatan' => 2000
        ];

        // 4. Hitung harga dengan fallback ke default jika master_harga tidak ada
        $resultBerat = $this->hitungHargaBerat($beratTerpakai, $masterHarga ?? $defaultHarga);
        $resultJarak = $this->hitungHargaJarak($jarak, $masterHarga ?? $defaultHarga);
        
        $hargaBerat = $resultBerat['total'];
        $hargaJarak = $resultJarak['total'];
        $hargaTotal = $hargaBerat + $hargaJarak;
        
        // Build calculation breakdown
        $breakdownText = $this->buatCalculationBreakdown($beratTerpakai, $jarak, $resultBerat, $resultJarak);

        // 5. Siapkan data response
        $outletAsal = \App\Models\Outlet::with('branch')->find($outletAsalId);
        $outletTujuan = \App\Models\Outlet::with('branch')->find($outletTujuanId);

        return response()->json([
            'success' => true,
            'data' => [
                'outlet_asal' => [
                    'id' => $outletAsal->id,
                    'nama' => $outletAsal->nama_outlet,
                    'kota' => $outletAsal->branch->kota,
                    'alamat' => $outletAsal->alamat_lengkap
                ],
                'outlet_tujuan' => [
                    'id' => $outletTujuan->id,
                    'nama' => $outletTujuan->nama_outlet,
                    'kota' => $outletTujuan->branch->kota,
                    'alamat' => $outletTujuan->alamat_lengkap
                ],
                'jarak' => round($jarak, 2),
                'berat' => [
                    'aktual' => round($beratAktual, 2),
                    'volumetric' => round($beratVolumetric, 2),
                    'terpakai' => round($beratTerpakai, 2)
                ],
                'harga' => [
                    'berat' => $hargaBerat,
                    'jarak' => $hargaJarak,
                    'total' => $hargaTotal,
                    'formatted' => [
                        'berat' => 'Rp ' . number_format($hargaBerat, 0, ',', '.'),
                        'jarak' => 'Rp ' . number_format($hargaJarak, 0, ',', '.'),
                        'total' => 'Rp ' . number_format($hargaTotal, 0, ',', '.')
                    ]
                ],
                'perhitungan' => $breakdownText,
                'estimasi_waktu' => $this->hitungEstimasiWaktu($jarak),
                'note' => $masterHarga ? 
                    'Harga dihitung berdasarkan data master_harga' : 
                    '⚠️ Harga dihitung menggunakan nilai default (master_harga belum tersedia)'
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Error kalkulator harga: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem. Silakan hubungi administrator.'
        ], 500);
    }
}

    /**
     * Helper: Hitung harga berdasarkan berat
     */
    private function hitungHargaBerat($berat, $masterHarga = null)
    {
        $default = [
            'berat_pertama' => 5,
            'harga_berat_pertama' => 7000,
            'harga_berat_berikutnya' => 2000
        ];
        
        // Gunakan nilai dari masterHarga jika tersedia
        if ($masterHarga) {
            $beratPertama = $masterHarga->berat_pertama ?? $default['berat_pertama'];
            $hargaBeratPertama = $masterHarga->harga_berat_pertama ?? $default['harga_berat_pertama'];
            $hargaBeratBerikutnya = $masterHarga->harga_berat_berikutnya ?? $default['harga_berat_berikutnya'];
        } else {
            $beratPertama = $default['berat_pertama'];
            $hargaBeratPertama = $default['harga_berat_pertama'];
            $hargaBeratBerikutnya = $default['harga_berat_berikutnya'];
        }
        
        // Calculate total price
        if ($berat <= $beratPertama) {
            $hargaTotal = $hargaBeratPertama;
            $beratTambahan = 0;
            $hargaTambahan = 0;
        } else {
            $beratTambahan = $berat - $beratPertama;
            $hargaTambahan = ceil($beratTambahan) * $hargaBeratBerikutnya;
            $hargaTotal = $hargaBeratPertama + $hargaTambahan;
        }
        
        // Return dengan breakdown untuk calculator
        return [
            'total' => $hargaTotal,
            'breakdown' => [
                'berat_pertama' => $beratPertama,
                'harga_berat_pertama' => $hargaBeratPertama,
                'berat_tambahan' => round($beratTambahan, 2),
                'harga_berat_berikutnya' => $hargaBeratBerikutnya,
                'harga_tambahan' => $hargaTambahan,
                'rules_source' => 'master_harga'
            ]
        ];
    }

    /**
     * Helper: Hitung harga berdasarkan jarak
     */
    private function hitungHargaJarak($jarak, $masterHarga = null)
    {
        $default = [
            'kelipatan_jarak' => 10,
            'harga_per_kelipatan' => 2000
        ];
        
        // Gunakan nilai dari masterHarga jika tersedia
        if ($masterHarga) {
            $kelipatanJarak = $masterHarga->kelipatan_jarak ?? $default['kelipatan_jarak'];
            $hargaPerKelipatan = $masterHarga->harga_per_kelipatan ?? $default['harga_per_kelipatan'];
        } else {
            $kelipatanJarak = $default['kelipatan_jarak'];
            $hargaPerKelipatan = $default['harga_per_kelipatan'];
        }
        
        $kelipatan = ceil($jarak / $kelipatanJarak);
        $hargaTotal = $kelipatan * $hargaPerKelipatan;
        
        // Return dengan breakdown untuk calculator
        return [
            'total' => $hargaTotal,
            'breakdown' => [
                'jarak_total' => round($jarak, 2),
                'kelipatan_jarak' => $kelipatanJarak,
                'jumlah_kelipatan' => $kelipatan,
                'harga_per_kelipatan' => $hargaPerKelipatan,
                'rules_source' => 'master_harga'
            ]
        ];
    }

    /**
     * Helper: Build calculation breakdown text for UI display
     */
    private function buatCalculationBreakdown($berat, $jarak, $resultBerat, $resultJarak)
    {
        $beratBreakdown = $resultBerat['breakdown'];
        $jarakBreakdown = $resultJarak['breakdown'];
        
        $text = "INFORMASI HARGA PENGIRIMAN\n";
        $text .= str_repeat("─", 40) . "\n\n";
        
        // INFORMASI BERAT
        $text .= "INFORMASI BERAT:\n";
        $text .= "• 5 kg pertama: Rp " . number_format($beratBreakdown['harga_berat_pertama'], 0, ',', '.') . "\n";
        
        if ($berat > $beratBreakdown['berat_pertama']) {
            $text .= "• Tambahan " . $beratBreakdown['berat_tambahan'] . " kg × Rp " . number_format($beratBreakdown['harga_berat_berikutnya'], 0, ',', '.') . "\n";
        }
        
        $text .= "  Subtotal Berat: Rp " . number_format($resultBerat['total'], 0, ',', '.') . "\n\n";
        
        // INFORMASI JARAK
        $text .= "INFORMASI JARAK:\n";
        $text .= "• Total jarak: " . round($jarakBreakdown['jarak_total'], 1) . " km\n";
        $text .= "• Biaya per 10 km: Rp " . number_format($jarakBreakdown['harga_per_kelipatan'], 0, ',', '.') . "\n";
        $text .= "  Subtotal Jarak: Rp " . number_format($resultJarak['total'], 0, ',', '.') . "\n\n";
        
        // TOTAL
        $text .= str_repeat("─", 40) . "\n";
        $text .= "TOTAL BIAYA: Rp " . number_format($resultBerat['total'] + $resultJarak['total'], 0, ',', '.') . "\n";
        $text .= str_repeat("─", 40);
        
        return $text;
    }

    /**
     * Helper: Hitung estimasi waktu
     */
    private function hitungEstimasiWaktu($jarak)
    {
        $jam = $jarak / 60; // asumsi 60 km/jam
        $totalJam = ceil($jam) + 2; // +2 jam untuk proses
        
        if ($totalJam >= 24) {
            return ceil($totalJam / 24) . ' hari';
        }
        
        return $totalJam . ' jam';
    }

    /**
     * API: Get outlet tujuan berdasarkan outlet asal dan rute
     */
    public function getOutletTujuanByRuteOld(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'outlet_asal_id' => 'required|exists:outlets,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $outletAsal = Outlet::with('branch')->find($request->outlet_asal_id);
            $kotaAsal = $outletAsal->branch->kota ?? null;
            
            if (!$kotaAsal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kota outlet asal tidak ditemukan'
                ]);
            }

            // 1. Cari semua rute yang memiliki kota asal dalam rute_pemberhentian
            $rutes = Rute::where('status', 'aktif')
                ->where(function($query) use ($kotaAsal) {
                    $query->where('kota_asal', $kotaAsal)
                          ->orWhereJsonContains('rute_pemberhentian', [['kota' => $kotaAsal]]);
                })
                ->get();

            if ($rutes->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada rute yang melewati kota asal: ' . $kotaAsal
                ]);
            }

            $outletTujuanList = [];
            
            foreach ($rutes as $rute) {
                // Decode rute_pemberhentian
                $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
                
                // Cari posisi kota asal dalam rute
                $foundAsal = false;
                $startCollecting = false;
                
                foreach ($pemberhentian as $stop) {
                    // Jika menemukan kota asal, mulai kumpulkan outlet tujuan setelahnya
                    if (($stop['kota'] ?? '') === $kotaAsal) {
                        $foundAsal = true;
                        $startCollecting = true;
                        continue;
                    }
                    
                    // Jika sudah melewati kota asal, kumpulkan outlet tujuan
                    if ($startCollecting && isset($stop['outlets']) && is_array($stop['outlets'])) {
                        foreach ($stop['outlets'] as $outletName) {
                            // Cari outlet berdasarkan nama di kota tersebut
                            $outlet = Outlet::where('nama_outlet', $outletName)
                                ->whereHas('branch', function($q) use ($stop) {
                                    $q->where('kota', $stop['kota'] ?? '');
                                })
                                ->where('status', 'aktif')
                                ->first();
                            
                            if ($outlet && !in_array($outlet->id, array_column($outletTujuanList, 'id'))) {
                                $outletTujuanList[] = [
                                    'id' => $outlet->id,
                                    'nama_outlet' => $outlet->nama_outlet,
                                    'kota' => $stop['kota'] ?? '',
                                    'alamat' => $outlet->alamat_lengkap,
                                    'rute_id' => $rute->id,
                                    'rute_nama' => $rute->nama_rute,
                                ];
                            }
                        }
                    }
                }
                
                // Jika kota asal adalah kota_asal utama dari rute
                if (!$foundAsal && $rute->kota_asal === $kotaAsal) {
                    // Ambil semua outlet dari semua pemberhentian setelah kota asal
                    foreach ($pemberhentian as $stop) {
                        if (isset($stop['outlets']) && is_array($stop['outlets'])) {
                            foreach ($stop['outlets'] as $outletName) {
                                $outlet = Outlet::where('nama_outlet', $outletName)
                                    ->whereHas('branch', function($q) use ($stop) {
                                        $q->where('kota', $stop['kota'] ?? '');
                                    })
                                    ->where('status', 'aktif')
                                    ->first();
                                
                                if ($outlet && !in_array($outlet->id, array_column($outletTujuanList, 'id'))) {
                                    $outletTujuanList[] = [
                                        'id' => $outlet->id,
                                        'nama_outlet' => $outlet->nama_outlet,
                                        'kota' => $stop['kota'] ?? '',
                                        'alamat' => $outlet->alamat_lengkap,
                                        'rute_id' => $rute->id,
                                        'rute_nama' => $rute->nama_rute,
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'outlet_asal' => [
                        'id' => $outletAsal->id,
                        'nama' => $outletAsal->nama_outlet,
                        'kota' => $kotaAsal,
                    ],
                    'outlet_tujuan' => $outletTujuanList,
                    'total_tersedia' => count($outletTujuanList)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Kalkulator harga berdasarkan outlet asal-tujuan (CEK HARGA SAJA)
     */
    public function kalkulatorHargaRute(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'outlet_asal_id' => 'required|exists:outlets,id',
                'outlet_tujuan_id' => 'required|exists:outlets,id',
                'berat' => 'required|numeric|min:0.1|max:100',
                'panjang' => 'nullable|numeric|min:0',
                'lebar' => 'nullable|numeric|min:0',
                'tinggi' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Gunakan KalkulatorEstimasiController yang sudah ada
            $kalkulator = new KalkulatorEstimasiController();
            return $kalkulator->hitungEstimasi($request);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Cari rute yang menghubungkan dua kota
     */
    private function cariRuteUntukKota($kotaAsal, $kotaTujuan)
    {
        // Cari rute yang memiliki kedua kota dalam urutan yang benar
        $rutes = Rute::where('status', 'aktif')->get();
        
        foreach ($rutes as $rute) {
            $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
            
            $foundAsal = false;
            $foundTujuan = false;
            
            foreach ($pemberhentian as $stop) {
                if (($stop['kota'] ?? '') === $kotaAsal) {
                    $foundAsal = true;
                }
                
                if (($stop['kota'] ?? '') === $kotaTujuan) {
                    if ($foundAsal) {
                        $foundTujuan = true;
                        break;
                    }
                }
            }
            
            // Juga cek jika kota asal adalah kota_asal utama
            if ($rute->kota_asal === $kotaAsal) {
                $foundAsal = true;
                // Cek apakah kota tujuan ada dalam pemberhentian
                foreach ($pemberhentian as $stop) {
                    if (($stop['kota'] ?? '') === $kotaTujuan) {
                        $foundTujuan = true;
                        break;
                    }
                }
            }
            
            if ($foundAsal && $foundTujuan) {
                return $rute;
            }
        }
        
        return null;
    }

    /**
     * Helper: Hitung jarak antara dua kota dalam rute
     */
    private function hitungJarakDalamRute($rute, $kotaAsal, $kotaTujuan)
    {
        // Jika rute memiliki jarak total
        if ($rute->jarak) {
            $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
            
            // Hitung jumlah segment antara kedua kota
            $count = 0;
            $startCounting = false;
            
            foreach ($pemberhentian as $stop) {
                if (($stop['kota'] ?? '') === $kotaAsal) {
                    $startCounting = true;
                    continue;
                }
                
                if ($startCounting) {
                    $count++;
                    
                    if (($stop['kota'] ?? '') === $kotaTujuan) {
                        break;
                    }
                }
            }
            
            // Jika kota asal adalah kota_asal utama
            if ($rute->kota_asal === $kotaAsal && $count === 0) {
                $startCounting = false;
                foreach ($pemberhentian as $stop) {
                    if (!$startCounting && ($stop['kota'] ?? '') === $kotaTujuan) {
                        $count = 1; // Asumsi 1 segment
                        break;
                    }
                }
            }
            
            // Jika ditemukan, bagi jarak total secara proporsional
            if ($count > 0) {
                $totalSegments = count($pemberhentian);
                return ($count / $totalSegments) * $rute->jarak;
            }
        }
        
        // Fallback: gunakan jarak default jika tidak bisa dihitung
        return 100; // Default 100 km
    }

    /**
     * Helper: Format perhitungan detail untuk display
     */
    private function formatPerhitunganDetail($berat, $beratVolumetric, $beratTerpakai, $jarak, $hargaBerat, $hargaJarak, $hargaTotal, $tarifBerat, $tarifJarak)
    {
        $text = "**Detail Perhitungan:**\n\n";
        
        // Bagian berat
        $text .= "**1. Perhitungan Berat**\n";
        $text .= "- Berat aktual: " . number_format($berat, 2) . " kg\n";
        
        if ($beratVolumetric > 0) {
            $text .= "- Berat volumetric: " . number_format($beratVolumetric, 2) . " kg (P×L×T÷6000)\n";
            $text .= "- Berat terpakai: " . number_format($beratTerpakai, 2) . " kg (berat terbesar)\n";
        }
        
        if ($beratTerpakai <= $tarifBerat->berat_pertama) {
            $text .= "- Harga berat: " . $tarifBerat->berat_pertama . " kg pertama = Rp " . number_format($tarifBerat->harga_berat_pertama, 0, ',', '.') . "\n";
        } else {
            $text .= "- Harga berat: " . $tarifBerat->berat_pertama . " kg pertama (Rp " . number_format($tarifBerat->harga_berat_pertama, 0, ',', '.') . ") + ";
            $text .= ceil($beratTerpakai - $tarifBerat->berat_pertama) . " kg × Rp " . number_format($tarifBerat->harga_berat_berikutnya, 0, ',', '.') . "\n";
        }
        $text .= "- **Total harga berat: Rp " . number_format($hargaBerat, 0, ',', '.') . "**\n\n";
        
        // Bagian jarak
        $text .= "**2. Perhitungan Jarak**\n";
        $text .= "- Jarak tempuh: " . number_format($jarak, 2) . " km\n";
        $text .= "- Kelipatan: " . ceil($jarak / $tarifJarak->kelipatan_jarak) . " × " . $tarifJarak->kelipatan_jarak . " km\n";
        $text .= "- Harga jarak: " . ceil($jarak / $tarifJarak->kelipatan_jarak) . " × Rp " . number_format($tarifJarak->harga_per_kelipatan, 0, ',', '.') . "\n";
        $text .= "- **Total harga jarak: Rp " . number_format($hargaJarak, 0, ',', '.') . "**\n\n";
        
        // Total
        $text .= "**3. Total Biaya Pengiriman**\n";
        $text .= "- Harga berat: Rp " . number_format($hargaBerat, 0, ',', '.') . "\n";
        $text .= "- Harga jarak: Rp " . number_format($hargaJarak, 0, ',', '.') . "\n";
        $text .= "- **TOTAL: Rp " . number_format($hargaTotal, 0, ',', '.') . "**\n\n";
        
        $text .= "*Catatan: Jarak dihitung otomatis berdasarkan rute yang dipilih*";
        
        return $text;
    }

    /**
     * Helper: Get status text
     */
    private function getStatusText($status)
    {
        $statuses = [
            'diproses' => 'Sedang Diproses',
            'dalam_perjalanan' => 'Dalam Perjalanan',
            'sampai_tujuan' => 'Sampai di Kota Tujuan',
            'terkirim' => 'Terkirim',
            'dibatalkan' => 'Dibatalkan'
        ];
        
        return $statuses[$status] ?? 'Tidak Diketahui';
    }

    /**
     * Helper: Get status color
     */
    private function getStatusColor($status)
    {
        $colors = [
            'diproses' => 'warning',
            'dalam_perjalanan' => 'primary',
            'sampai_tujuan' => 'info',
            'terkirim' => 'success',
            'dibatalkan' => 'danger'
        ];
        
        return $colors[$status] ?? 'secondary';
    }

    /**
     * Halaman detail paket (tracking)
     */
    public function detailPaket($kode_resi)
    {
        $user = session()->get('user', null);
        
        // Cari shipment dengan relasi lengkap
        $shipment = Shipment::with([
            'rute',
            'segmentAsal.outlet.branch',
            'segmentTujuan.outlet.branch',
            'outletAsal.branch',
            'outletTujuan.branch',
            'trackingHistories' => function($query) {
                $query->orderBy('waktu_status', 'desc')
                    ->with(['outlet', 'segment', 'updatedByUser']);
            },
            'user'
        ])->where('kode_resi', $kode_resi)->first();
        
        if (!$shipment) {
            return redirect()->route('customer.cek-resi')
                ->with('error', 'Kode resi tidak ditemukan');
        }
        
        // Cek apakah user memiliki akses (pengirim atau admin)
        $isOwner = false;
        if ($user && $shipment->user_id) {
            $isOwner = $shipment->user_id == $user['id'];
        }
        
        // Get timeline
        $timeline = $shipment->timeline;
        
        // Get status label
        $statusLabel = $shipment->statusLabel;
        
        return view('customer.detail_paket', compact(
            'user',
            'shipment',
            'timeline',
            'statusLabel',
            'isOwner'
        ));
    }

    /**
     * API: Update status shipment (untuk admin)
     */
    public function updateStatusShipment(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $validator = Validator::make($request->all(), [
            'kode_resi' => 'required|exists:shipments,kode_resi',
            'status' => 'required|in:diproses,dalam_perjalanan,sampai_tujuan,terkirim,dibatalkan',
            'catatan' => 'nullable|string|max:500',
            'outlet_id' => 'nullable|exists:outlets,id',
            'segment_id' => 'nullable|exists:rute_segments,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        DB::beginTransaction();
        
        try {
            $shipment = Shipment::where('kode_resi', $request->kode_resi)->first();
            $user = Auth::user();
            
            // Update status shipment
            $shipment->status_pengiriman = $request->status;
            
            // Set timestamp berdasarkan status
            switch ($request->status) {
                case 'diterima_outlet_asal':
                    $shipment->waktu_diterima_outlet_asal = now();
                    break;
                case 'dalam_perjalanan':
                    $shipment->waktu_dalam_perjalanan = now();
                    break;
                case 'sampai_outlet_tujuan':
                    $shipment->waktu_sampai_outlet_tujuan = now();
                    break;
                case 'siap_diambil':
                    $shipment->waktu_siap_diambil = now();
                    break;
                case 'terkirim':
                    $shipment->waktu_terkirim = now();
                    break;
            }
            
            $shipment->save();
            
            // Buat tracking history
            $tracking = ShipmentTracking::create([
                'shipment_id' => $shipment->id,
                'outlet_id' => $request->outlet_id,
                'rute_segment_id' => $request->segment_id,
                'status' => $this->mapStatusToTracking($request->status),
                'deskripsi' => $this->generateDeskripsiStatus($request->status, $request->outlet_id),
                'catatan' => $request->catatan,
                'updated_by' => $user->id,
                'updated_by_role' => 'admin',
                'waktu_status' => now(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'data' => [
                    'shipment' => $shipment,
                    'tracking' => $tracking,
                    'status_label' => $shipment->statusLabel,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update status shipment error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Map status shipment ke tracking status
     */
    private function mapStatusToTracking($status)
    {
        $mapping = [
            'diproses' => 'paket_diproses',
            'diterima_outlet_asal' => 'paket_diterima',
            'dalam_perjalanan' => 'paket_dalam_perjalanan',
            'sampai_outlet_tujuan' => 'paket_sampai_outlet',
            'siap_diambil' => 'paket_siap_diambil',
            'terkirim' => 'paket_terkirim',
            'dibatalkan' => 'paket_batal',
        ];
        
        return $mapping[$status] ?? 'paket_diproses';
    }

    /**
     * Helper: Generate deskripsi status
     */
    private function generateDeskripsiStatus($status, $outletId = null)
    {
        $outlet = $outletId ? Outlet::find($outletId) : null;
        $outletName = $outlet ? $outlet->nama_outlet : 'outlet';
        
        $deskripsi = [
            'diproses' => 'Paket sedang diproses',
            'diterima_outlet_asal' => "Paket diterima di {$outletName}",
            'dalam_perjalanan' => 'Paket dalam perjalanan menuju outlet tujuan',
            'sampai_outlet_tujuan' => "Paket sampai di {$outletName}",
            'siap_diambil' => "Paket siap diambil di {$outletName}",
            'terkirim' => 'Paket telah terkirim ke penerima',
            'dibatalkan' => 'Pengiriman dibatalkan',
        ];
        
        return $deskripsi[$status] ?? 'Status diperbarui';
    }
}