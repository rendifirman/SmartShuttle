<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KursiController;
use App\Http\Controllers\ETicketController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Customer\CekReservasiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\Admin\ProfilePerusahaanController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PaylabsTestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ★★★ AUTH ROUTES ★★★
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email.forgot');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ★★★ ROUTE UTAMA DAN TAMU ★★★
Route::get('/', [CustomerController::class, 'beranda'])->name('customer.beranda');
Route::get('/customer/beranda', [CustomerController::class, 'beranda']);

// Halaman statis - bisa diakses tamu
Route::get('/bantuan', [CustomerController::class, 'bantuan'])->name('customer.bantuan');
Route::get('/syarat-ketentuan', [CustomerController::class, 'syaratKetentuan'])->name('customer.syarat.ketentuan');
Route::get('/kebijakan-privasi', [CustomerController::class, 'kebijakanPrivasi'])->name('customer.kebijakan.privasi');
// Boleh diakses tamu (guest) - GET
Route::get('/kontak', [CustomerController::class, 'contact'])
    ->name('customer.contact');

// Hanya boleh diakses user yang sudah login - POST
Route::post('/kontak', [CustomerController::class, 'submitContact'])
    ->name('customer.contact.submit')
    ->middleware('auth.customer');
Route::get('/syarat-ketentuan-membership', [CustomerController::class, 'syaratKetentuanMembership'])->name('customer.syarat.ketentuan.membership');

// Halaman outlet - bisa diakses tamu
Route::get('/customer/outlet', [CustomerController::class, 'outlet'])->name('customer.outlet');
Route::get('/customer/outlet/filter', [CustomerController::class, 'outlet'])->name('customer.outlet.filter');
Route::post('/customer/outlet/loadMore', [CustomerController::class, 'loadMoreOutlets'])
    ->name('customer.outlet.loadMore');
Route::get('/outlet', [CustomerController::class, 'outlet']);

// Halaman SmartSend - bisa diakses tamu
Route::get('/customer/smartsend', function() {
    return view('customer.smartsend');
})->name('customer.smartsend');

// Halaman pencarian shuttle - bisa diakses tamu
Route::get('/cari-shuttle', [CustomerController::class, 'showSearch'])->name('customer.search');
Route::post('/cari-shuttle', [CustomerController::class, 'search'])->name('customer.search.post');
Route::get('/customer/search', [CustomerController::class, 'showSearch']);
Route::post('/customer/search', [CustomerController::class, 'search']);

// ★★★ CEK RESERVASI - BISA DIAKSES TANPA LOGIN ★★★
Route::get('/customer/cek-reservasi', function() {
    return view('customer.cek-reservasi');
})->name('customer.cek-reservasi');

Route::post('/customer/cek-reservasi', [CekReservasiController::class, 'proses'])
    ->name('customer.cek-reservasi.proses');

// Route untuk hasil reservasi (bisa diakses tanpa login)
Route::get('/customer/cek-reservasi/hasil/{kode}', [CekReservasiController::class, 'hasil'])
    ->name('customer.cek-reservasi.hasil');

// Google OAuth Routes - minimal middleware for proper OAuth flow
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
    ->name('login.google');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
    ->withoutMiddleware('Illuminate\Http\Middleware\ValidatePathEncoding')
    ->name('login.google.callback');



// ★★★ AUTH ROUTES - HANYA UNTUK TAMU ★★★
Route::middleware(['ensure.session', 'guest.customer'])->group(function () {
    // Login/Register tradisional
    Route::get('/customer/login', [CustomerController::class, 'showLogin'])->name('customer.login');
    Route::post('/customer/login', [CustomerController::class, 'login'])->name('customer.login.post');
    Route::get('/customer/register', [CustomerController::class, 'showRegister'])->name('customer.register');
    Route::post('/customer/register', [CustomerController::class, 'register'])->name('customer.register.post');


    // Password reset
    Route::prefix('password')->group(function () {
        Route::get('/forgot', [AuthController::class, 'showForgotForm'])->name('password.request');
        Route::post('/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email.custom');
        Route::get('/token', [AuthController::class, 'showTokenForm'])->name('password.token');
        Route::post('/token/verify', [AuthController::class, 'verifyToken'])->name('password.token.verify');
        Route::get('/reset', [AuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset', [AuthController::class, 'reset'])->name('password.update');
    });

    // Alias untuk kompatibilitas password reset
    Route::get('/password/reset/{token}', function ($token) {
        return redirect()->route('password.reset')->with('token', $token);
    })->name('password.reset.with.token');
});

// ★★★ LOGOUT ★★★
Route::post('/customer/logout', [CustomerController::class, 'logout'])
    ->name('customer.logout')
    ->middleware('auth.customer');

// ============================================================
// ★★★ AVATAR ROUTES - TAMBAHKAN DI SINI SEBELUM MIDDLEWARE ★★★
// ============================================================

// AVATAR UPLOAD ROUTE
Route::post('/customer/avatar/upload', [CustomerController::class, 'uploadAvatar'])
    ->middleware('auth')
    ->name('customer.avatar.upload');

// AVATAR DELETE ROUTE
Route::delete('/customer/avatar/delete', [CustomerController::class, 'deleteAvatar'])
    ->middleware('auth')
    ->name('customer.avatar.delete');

// ============================================================

// ★★★ ROUTES YANG BUTUH LOGIN ★★★
Route::middleware(['auth.customer'])->group(function () {

    // ★★★ PROFIL & DASHBOARD ★★★
    Route::get('/customer/dashboardprofile', [CustomerController::class, 'profil'])->name('customer.dashboardprofile');
    Route::get('/customer/profil', [CustomerController::class, 'profil'])->name('customer.profil');
    Route::get('/customer/profilcust', [CustomerController::class, 'profilDetail'])->name('customer.profilcust');
    Route::put('/customer/profilcust/update', [CustomerController::class, 'updateProfile'])->name('customer.profilcust.update');

    // ★★★ MEMBERSHIP ★★★
    Route::get('/customer/membership', [CustomerController::class, 'membership'])->name('customer.membership');
    Route::get('/membership/form', [CustomerController::class, 'showMembershipForm'])->name('customer.membership.form');
    Route::post('/membership/form', [CustomerController::class, 'processMembershipRegistration'])->name('customer.membership.form.submit');
    Route::get('/membership/payment', [CustomerController::class, 'showMembershipPayment'])->name('customer.membership.payment');
    Route::post('/membership/payment', [CustomerController::class, 'processMembershipPayment'])->name('customer.membership.payment.submit');
    Route::post('/membership/payment/simulate', [CustomerController::class, 'simulateMembershipPayment'])->name('customer.membership.payment.simulate');
    Route::delete('/membership/payment/cancel', [CustomerController::class, 'cancelMembershipPayment'])->name('customer.membership.payment.cancel');
    Route::get('/membership/pending', [CustomerController::class, 'showMembershipPending'])->name('customer.membership.pending');
    Route::post('/membership/renew', [CustomerController::class, 'renewMembership'])->name('customer.membership.renew');

    // ★★★ LOYALTY POINTS ★★★
    Route::post('/loyalty-points/use', [CustomerController::class, 'useLoyaltyPoints'])->name('customer.useLoyaltyPoints');
    Route::post('/loyalty-points/remove', [CustomerController::class, 'removeLoyaltyDiscount'])->name('customer.removeLoyaltyDiscount');
    Route::post('/membership/update-points', [CustomerController::class, 'updatePoints'])->name('customer.updatePoints');

    // ★★★ PEMESANAN SHUTTLE ★★★
    Route::get('/customer/pesan', [CustomerController::class, 'showBooking'])->name('customer.pesan');
    Route::post('/pesan-shuttle', [CustomerController::class, 'prosesPemesanan'])->name('customer.pesan.submit');
    Route::post('/customer/pemesanan/proses', [CustomerController::class, 'prosesPemesanan'])->name('customer.pemesanan.proses');

    // ★★★ PROMO ★★★
    Route::get('/customer/promo/{id}', [CustomerController::class, 'showPromoDetail'])->name('customer.promo.detail');
    Route::post('/apply-promo', [CustomerController::class, 'validatePromo'])->name('customer.apply-promo');
    Route::post('/remove-promo', [CustomerController::class, 'removePromo'])->name('customer.remove-promo');
    Route::get('/get-promos', [CustomerController::class, 'getPromos'])->name('customer.get-promos');

    // ★★★ KURSI ★★★
    Route::get('/customer/kursi', [CustomerController::class, 'showPemilihanKursi'])->name('customer.kursi');
    Route::post('/customer/kursi/proses', [CustomerController::class, 'prosesPemilihanKursi'])->name('customer.kursi.proses');

    // ★★★ PEMBAYARAN ★★★
    Route::get('/customer/pembayaran/{kode_booking}', [PembayaranController::class, 'index'])->name('customer.pembayaran');
    Route::post('/customer/pembayaran/pilih-metode/{kode_booking}', [PembayaranController::class, 'pilihMetode'])->name('customer.pembayaran.pilih_metode');
    Route::get('/customer/pembayaran/simulasi/{kodePembayaran}/{status?}', [PembayaranController::class, 'simulasiPembayaran'])->name('customer.pembayaran.simulasi');
    Route::get('/customer/pembayaran/cek-status/{kodePembayaran}', [PembayaranController::class, 'cekStatus'])->name('customer.pembayaran.cek_status');
    Route::get('/customer/pembayaran/qr-code/{kodePembayaran}', [PembayaranController::class, 'generateQRCode'])->name('customer.pembayaran.qrcode');
    Route::get('/customer/pembayaran/status/{kodePembayaran}', [PembayaranController::class, 'cekStatus'])->name('customer.pembayaran.status');

    // ★★★ RIWAYAT ★★★
    Route::get('/customer/riwayat', [CustomerController::class, 'showRiwayat'])->name('customer.riwayat');
    Route::get('/customer/detail-pemesanan/{kode_booking}', [CustomerController::class, 'showDetailPemesanan'])->name('customer.detail_pemesanan');
    Route::post('/customer/batalkan-pemesanan/{kode_booking}', [CustomerController::class, 'batalkanPemesanan'])->name('customer.batalkan_pemesanan');

    // ★★★ E-TICKET ★★★
    Route::get('/customer/e-ticket/{kode_booking}', [ETicketController::class, 'show'])->name('customer.e_ticket');
    Route::get('/customer/e-ticket/{kode_booking}/download', [ETicketController::class, 'download'])->name('customer.e_ticket.download');
    Route::get('/customer/e-ticket/{kode_booking}/qr', [ETicketController::class, 'qrCode'])->name('customer.e_ticket.qr');
    Route::get('/customer/ticket', [ETicketController::class, 'showByQuery'])->name('customer.ticket.query');

    // ★★★ KIRIM PAKET ★★★
    Route::get('/customer/kirim-paket', function() {
        return view('customer.kirim_paket');
    })->name('customer.kirim-paket');
    Route::get('/customer/smartsend', function() {
        return view('customer.smartsend');
    })->name('customer.smartsend');
    Route::post('/cek-harga-paket', [CustomerController::class, 'cekHargaPaket'])->name('customer.cek-harga-paket');
    Route::post('/kirim-paket/proses', [CustomerController::class, 'prosesKirimPaket'])->name('customer.kirim-paket.proses');
});

// ★★★ API ROUTES (UNTUK AJAX) ★★★
Route::prefix('api')->group(function () {
    // Kursi API
    Route::get('/kursi-tersedia/{jadwalId}', [KursiController::class, 'getKursiTersediaAPI'])
        ->name('api.kursi.tersedia');
    Route::post('/validasi-kursi', [KursiController::class, 'validasiKursiAPI'])
        ->name('api.kursi.validasi');

    // Review routes
    Route::get('/reviews', [CustomerController::class, 'getReviews'])->name('api.reviews.get');
    Route::post('/reviews', [CustomerController::class, 'storeReview'])->name('api.reviews.store');
    Route::get('/reviews/filter', [CustomerController::class, 'getFilteredReviews'])->name('api.reviews.filter');
    Route::get('/reviews/stats', [CustomerController::class, 'getReviewStats'])->name('api.reviews.stats');

    // Promo API
    Route::post('/customer/pesan/validasi-promo', [CustomerController::class, 'validatePromo'])->name('customer.pesan.validasi_promo');

    // Loyalty Points API
    Route::post('/api/loyalty-points/use', [CustomerController::class, 'useLoyaltyPoints'])->name('api.loyalty-points.use');
    Route::post('/api/loyalty-points/remove', [CustomerController::class, 'removeLoyaltyDiscount'])->name('api.loyalty-points.remove');

    // Payment API
    Route::post('/payment/callback', [PembayaranController::class, 'webhook'])->name('api.payment.callback');

    // Policy content API (terms / privacy) for AJAX modals
    Route::get('/policy/{type}', [CustomerController::class, 'getPolicy'])->name('api.policy.get');

    // Promo routes
    Route::prefix('promo')->group(function () {
        Route::post('/eligible', [CustomerController::class, 'getEligiblePromos'])
            ->name('api.promo.eligible');
        Route::post('/validate', [CustomerController::class, 'validatePromo'])
            ->name('api.promo.validate');
    });

    // Kursi validation API
    Route::post('/validasi-kursi', [KursiController::class, 'validasiKursiAPI']);
    Route::get('/kursi-tersedia/{jadwalId}', [KursiController::class, 'getKursiTersediaAPI']);
    Route::post('/kursi-validate', [KursiController::class, 'validateSeatsAPI']);
});

// ★★★ ADMIN AUTH ROUTES ★★★
Route::middleware(['ensure.session', 'guest:admin'])->prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');
});

// ★★★ ADMIN ROUTES ★★★
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/kursi/peta/{jadwalId}', [KursiController::class, 'petaKursi'])
        ->name('admin.kursi.peta');

    // Master Data Routes
    Route::get('/pusat', [AdminController::class, 'pusat'])->name('admin.pusat');
    Route::get('/cabangperusahaan', [AdminController::class, 'cabangPerusahaan'])->name('admin.cabangperusahaan');
    Route::get('/outletperusahaan', [AdminController::class, 'outletPerusahaan'])->name('admin.outletperusahaan');
    Route::get('/outletperusahaan/create', [AdminController::class, 'createOutlet'])->name('admin.outletperusahaan.create');
    Route::post('/outletperusahaan', [AdminController::class, 'storeOutlet'])->name('admin.outletperusahaan.store');
    Route::get('/outletperusahaan/{id}', [AdminController::class, 'showOutlet'])->name('admin.outletperusahaan.show');
    Route::get('/outletperusahaan/{id}/edit', [AdminController::class, 'editOutlet'])->name('admin.outletperusahaan.edit');
    Route::put('/outletperusahaan/{id}', [AdminController::class, 'updateOutlet'])->name('admin.outletperusahaan.update');
    Route::delete('/outletperusahaan/{id}', [AdminController::class, 'destroyOutlet'])->name('admin.outletperusahaan.destroy');
    Route::get('/promo', [AdminController::class, 'promo'])->name('admin.promo');
    Route::get('/promo/create', [AdminController::class, 'createPromo'])->name('admin.promo.create');
    Route::post('/promo', [AdminController::class, 'storePromo'])->name('admin.promo.store');
    Route::get('/promo/{id}', [AdminController::class, 'showPromo'])->name('admin.promo.show');
    Route::get('/promo/{id}/edit', [AdminController::class, 'editPromo'])->name('admin.promo.edit');
    Route::put('/promo/{id}', [AdminController::class, 'updatePromo'])->name('admin.promo.update');
    Route::delete('/promo/{id}', [AdminController::class, 'destroyPromo'])->name('admin.promo.destroy');
    Route::get('/artikel', [AdminController::class, 'artikel'])->name('admin.artikel.index');
    Route::get('/artikel/create', [AdminController::class, 'createArtikel'])->name('admin.artikel.create');
    Route::post('/artikel', [AdminController::class, 'storeArtikel'])->name('admin.artikel.store');
    Route::get('/artikel/{id}/edit', [AdminController::class, 'editArtikel'])->name('admin.artikel.edit');
    Route::put('/artikel/{id}', [AdminController::class, 'updateArtikel'])->name('admin.artikel.update');
    Route::delete('/artikel/{id}', [AdminController::class, 'destroyArtikel'])->name('admin.artikel.destroy');
    Route::get('/artikel/{id}', [AdminController::class, 'showArtikel'])->name('admin.artikel.show');
    Route::get('/kontak', [AdminController::class, 'kontak'])->name('admin.kontak');
    Route::get('/kontakperusahaan', [AdminController::class, 'kontakPerusahaan'])->name('admin.kontakperusahaan');
    Route::put('/kontakperusahaan/{id}', [AdminController::class, 'updateKontakPerusahaan'])->name('admin.kontak.update');

    // ★★★ ROUTE JADWAL ★★★
    Route::prefix('jadwal')->group(function () {
        Route::get('/', [JadwalController::class, 'index'])->name('admin.jadwal');
        Route::get('/create', [JadwalController::class, 'create'])->name('admin.jadwal.create');
        Route::post('/', [JadwalController::class, 'store'])->name('admin.jadwal.store');
        Route::get('/{jadwal}', [JadwalController::class, 'show'])->name('admin.jadwal.show');
        Route::get('/{jadwal}/edit', [JadwalController::class, 'edit'])->name('admin.jadwal.edit');
        Route::put('/{jadwal}', [JadwalController::class, 'update'])->name('admin.jadwal.update');
        Route::delete('/{jadwal}', [JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
    });

    // Rute CRUD
    Route::prefix('rute')->group(function () {
        Route::get('/', [AdminController::class, 'rute'])->name('admin.rute');
        Route::get('/create', [AdminController::class, 'createRute'])->name('admin.rute.create');
        Route::post('/', [AdminController::class, 'storeRute'])->name('admin.rute.store');
        Route::get('/{id}/edit', [AdminController::class, 'editRute'])->name('admin.rute.edit');
        Route::put('/{id}', [AdminController::class, 'updateRute'])->name('admin.rute.update');
        Route::delete('/{id}', [AdminController::class, 'destroyRute'])->name('admin.rute.destroy');
        Route::get('/{id}', [AdminController::class, 'showRute'])->name('admin.rute.show');
    });

    // Armada CRUD Routes
    Route::prefix('armada')->group(function () {
        Route::get('/', [AdminController::class, 'armada'])->name('admin.armada');
        Route::get('/create', [AdminController::class, 'createShuttle'])->name('admin.armada.create');
        Route::post('/', [AdminController::class, 'storeShuttle'])->name('admin.armada.store');
        Route::get('/{id}/edit', [AdminController::class, 'editShuttle'])->name('admin.armada.edit');
        Route::put('/{id}', [AdminController::class, 'updateShuttle'])->name('admin.armada.update');
        Route::delete('/{id}', [AdminController::class, 'destroyShuttle'])->name('admin.armada.destroy');
        Route::get('/{id}', [AdminController::class, 'showShuttle'])->name('admin.armada.show');
    });

    Route::get('/driver', [AdminController::class, 'driver'])->name('admin.driver');
    Route::get('/pegawai', [AdminController::class, 'pegawai'])->name('admin.pegawai');

    // ★★★ TRANSAKSI ROUTES (DIPERBARUI) ★★★
    Route::get('/smartsend-transaksi', [AdminController::class, 'smartsendTransaksi'])->name('admin.smartsend-transaksi');
    Route::get('/perjalanan', [AdminController::class, 'perjalanan'])->name('admin.perjalanan');
    Route::get('/armada-transaksi', [AdminController::class, 'armadaTransaksi'])->name('admin.armada-transaksi');

    // SmartSend Routes
    Route::get('/smartsend-tiket', [AdminController::class, 'smartsendTiket'])->name('admin.smartsend-tiket');
    Route::get('/smartsend-perjalanan', [AdminController::class, 'smartsendPerjalanan'])->name('admin.smartsend-perjalanan');
    Route::get('/smartsend-armada', [AdminController::class, 'smartsendArmada'])->name('admin.smartsend-armada');

    // SmartRent Route
    Route::get('/smartrent', [AdminController::class, 'smartrent'])->name('admin.smartrent');

    // Laporan Route
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');

    // Pengaturan Routes
    Route::get('/user', [AdminController::class, 'user'])->name('admin.user');
    Route::get('/menu', [AdminController::class, 'menu'])->name('admin.menu');

    // Profile Perusahaan Admin
    Route::get('/profile-perusahaan', [ProfilePerusahaanController::class, 'index'])->name('admin.profileperusahaan');
    Route::post('/profile-perusahaan/update', [ProfilePerusahaanController::class, 'update'])->name('admin.profileperusahaan.update');
    Route::post('/profile-perusahaan/layanan/{id}/update', [ProfilePerusahaanController::class, 'updateLayanan'])->name('admin.profileperusahaan.layanan.update');
    Route::post('/profile-perusahaan/layanan/create', [ProfilePerusahaanController::class, 'createLayanan'])->name('admin.profileperusahaan.layanan.create');
    Route::delete('/profile-perusahaan/layanan/{id}/delete', [ProfilePerusahaanController::class, 'deleteLayanan'])->name('admin.profileperusahaan.layanan.delete');

    // Branch Management Routes
    Route::prefix('cabang')->group(function () {
        Route::get('/create', [AdminController::class, 'createBranch'])
            ->name('admin.cabang.create');
        Route::post('/', [AdminController::class, 'storeBranch'])
            ->name('admin.cabang.store');
        Route::get('/{id}/edit', [AdminController::class, 'editBranch'])
            ->name('admin.cabang.edit');
        Route::put('/{id}', [AdminController::class, 'updateBranch'])
            ->name('admin.cabang.update');
        Route::delete('/{id}', [AdminController::class, 'destroyBranch'])
            ->name('admin.cabang.destroy');
        Route::get('/{id}', [AdminController::class, 'getBranch'])
            ->name('admin.cabang.get');
    });

    // Logout Route
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
});

// ★★★ DRIVER AUTH ROUTES ★★★
Route::middleware(['ensure.session', 'guest:driver'])->prefix('driver')->group(function () {
    Route::get('/login', [DriverController::class, 'showLogin'])->name('driver.login');
    Route::post('/login', [DriverController::class, 'login'])->name('driver.login.post');
});

// ★★★ DRIVER ROUTES ★★★
Route::middleware(['auth'])->prefix('driver')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('driver.dashboard');
    Route::post('/logout', [DriverController::class, 'logout'])->name('driver.logout');

    // Additional driver routes
    Route::get('/jadwal', [DriverController::class, 'jadwal'])->name('driver.jadwal');
    Route::get('/laporan', [DriverController::class, 'laporan'])->name('driver.laporan');
    Route::get('/perjalanan', [DriverController::class, 'perjalanan'])->name('driver.perjalanan');
    Route::get('/profile', [DriverController::class, 'profile'])->name('driver.profile');
    Route::get('/profile/edit', [DriverController::class, 'profileEdit'])->name('driver.profile.edit');
    Route::get('/pengaturan', [DriverController::class, 'pengaturan'])->name('driver.pengaturan');
    Route::get('/bantuan', [DriverController::class, 'bantuan'])->name('driver.bantuan');
});

// ★★★ PAYLABS TEST ROUTES (UNTUK DEVELOPMENT) ★★★
Route::prefix('paylabs-test')->group(function () {
    // Test connection
    Route::get('/connection', function () {
        $service = new \App\Services\PaylabsService();
        return response()->json($service->testConnection());
    })->name('paylabs.test.connection');

    // Quick test
    Route::get('/quick', function () {
        $service = new \App\Services\PaylabsService();
        return response()->json($service->quickTest());
    })->name('paylabs.test.quick');

    // Real API test - authentic Paylabs integration test
    Route::get('/real-api/{method?}', function ($method = 'QRIS') {
        $service = new \App\Services\PaylabsService();

        // Map method to channel codes
        $methodMap = [
            'qris' => ['method' => 'QRIS', 'channel_code' => 'QRIS', 'channel_name' => 'QRIS'],
            'bca_va' => ['method' => 'VA_BCA', 'channel_code' => 'VA_BCA', 'channel_name' => 'BCA Virtual Account'],
            'mandiri_va' => ['method' => 'VA_MANDIRI', 'channel_code' => 'VA_MANDIRI', 'channel_name' => 'Mandiri Virtual Account'],
            'bni_va' => ['method' => 'VA_BNI', 'channel_code' => 'VA_BNI', 'channel_name' => 'BNI Virtual Account'],
            'bri_va' => ['method' => 'VA_BRI', 'channel_code' => 'VA_BRI', 'channel_name' => 'BRI Virtual Account'],
        ];

        $config = $methodMap[$method] ?? $methodMap['qris'];

        return response()->json($service->realApiTest(
            $config['method'],
            $config['channel_code'],
            $config['channel_name']
        ));
    })->name('paylabs.test.real_api');

    // Test create payment for different methods
    Route::get('/create/{method}', function ($method) {
        // Daftar channel code yang valid
        $channels = [
            'qris' => 'QRIS',
            'bca_va' => 'VA_BCA',
            'mandiri_va' => 'VA_MANDIRI',
            'bni_va' => 'VA_BNI',
            'bri_va' => 'VA_BRI',
            'dana' => 'EW_DANA',
            'gopay' => 'EW_GOPAY',
            'ovo' => 'EW_OVO',
            'shopeepay' => 'EW_SHOPEEPAY',
        ];

        if (!array_key_exists($method, $channels)) {
            return response()->json(['error' => 'Method not supported'], 400);
        }

        $channelCode = $channels[$method];
        $channelName = $method;

        // Buat pembayaran dummy
        $pembayaran = new \App\Models\Pembayaran();
        $pembayaran->id = rand(1, 1000);
        $pembayaran->kode_pembayaran = 'TEST' . time();
        $pembayaran->jumlah = 100000;
        $pembayaran->waktu_kadaluarsa = now()->addMinutes(30);
        $pembayaran->pemesanan = (object) [
            'kode_booking' => 'BOOKTEST',
            'jumlah_penumpang' => 1,
            'nama_pemesan' => 'Test User',
            'email_pemesan' => 'test@example.com',
            'telepon_pemesan' => '08123456789',
            'jadwal' => (object) [
                'rutes' => collect([
                    (object) ['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung']
                ])
            ]
        ];

        $service = new \App\Services\PaylabsService();
        $result = $service->createPayment($pembayaran, $channelCode, $channelName);
        return response()->json($result);
    })->name('paylabs.test.create');

    // Test page
    Route::get('/', function () {
        return view('paylabs-test.index');
    })->name('paylabs.test.index');

    // Test callback simulation page
    Route::get('/callback-simulate', function () {
        return view('paylabs-test.callback');
    })->name('paylabs.test.callback-simulate');

    // Process callback simulation
    Route::post('/callback-simulate', function (Request $request) {
        // Simulasikan callback dari Paylabs
        $paymentController = new \App\Http\Controllers\API\PaymentController(
            new \App\Services\PaylabsService()
        );
        return $paymentController->callback($request);
    });
});
Route::middleware(['auth:driver'])->prefix('driver')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('driver.dashboard');
    Route::post('/logout', [DriverController::class, 'logout'])->name('driver.logout');

    // Additional driver routes
    Route::get('/jadwal', [DriverController::class, 'jadwal'])->name('driver.jadwal');
    Route::get('/laporan', [DriverController::class, 'laporan'])->name('driver.laporan');
    Route::get('/perjalanan', [DriverController::class, 'perjalanan'])->name('driver.perjalanan');
    Route::get('/profile', [DriverController::class, 'profile'])->name('driver.profile');
    Route::get('/profile/edit', [DriverController::class, 'profileEdit'])->name('driver.profile.edit');
});

// ★★★ PAYLABS DEBUG ROUTES ★★★
Route::get('/debug/e-ticket/{kode_booking}', function($kode_booking) {
    \Log::info('Debug e-ticket access', [
        'kode_booking' => $kode_booking,
        'route' => 'direct_access',
        'time' => now()
    ]);
    return redirect()->route('customer.e_ticket', ['kode_booking' => $kode_booking]);
})->name('debug.e_ticket');

// Test route: generate RSA signature only (no external API call)
Route::get('/paylabs/signature-test', function () {
    try {
        $keyFile = config('paylabs.private_key_file');

        $privateKeyContent = null;
        if ($keyFile) {
            $pathsToTry = [$keyFile, base_path($keyFile), storage_path($keyFile)];
            foreach ($pathsToTry as $p) {
                if ($p && file_exists($p)) {
                    $privateKeyContent = file_get_contents($p);
                    break;
                }
            }
        }

        if (empty($privateKeyContent)) {
            throw new \Exception('Private key file not found. Check config("paylabs.private_key_file").');
        }

        // Temporarily set the private key in config so PaylabsService will pick it up
        config(['paylabs.private_key' => $privateKeyContent]);

        // Sample payload to sign
        $payload = [
            'requestId' => 'TEST' . time(),
            'merchantId' => config('paylabs.mid'),
            'merchantTradeNo' => 'TEST' . time(),
            'amount' => '100000.00',
            'paymentType' => 'QRIS',
        ];

        $service = new \App\Services\PaylabsService();

        // Generate timestamp
        $timestamp = \Carbon\Carbon::now()->format('Y-m-d\TH:i:s.vP');

        // Generate signature
        $signature = $service->generateSignatureV23($payload, $timestamp, '/payment/v2.3/qris/create');
        $signatureRaw = base64_decode($signature);
        $signatureLong = bin2hex($signatureRaw);

        return response()->json([
            'success' => true,
            'payload' => $payload,
            'timestamp' => $timestamp,
            'signature_base64' => $signature,
            'signature_raw_hex' => $signatureLong,
            'signature_length' => strlen($signature),
        ]);
    } catch (\Exception $e) {
        \Log::error('Paylabs signature test error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => env('APP_DEBUG') ? $e->getTraceAsString() : null,
        ], 500);
    }
})->name('paylabs.signature_test');

// Test route: create payment (sends POST to Paylabs)
Route::get('/paylabs/create-payment-test/{method?}', function ($method = 'qris') {
    try {
        $service = new \App\Services\PaylabsService();

        // Buat pembayaran dummy
        $pembayaran = new \App\Models\Pembayaran();
        $pembayaran->id = rand(1000, 9999);
        $pembayaran->kode_pembayaran = 'TEST' . time();
        $pembayaran->jumlah = 100000;
        $pembayaran->waktu_kadaluarsa = now()->addMinutes(30);
        $pembayaran->pemesanan = (object) [
            'kode_booking' => 'BOOKTEST',
            'jumlah_penumpang' => 1,
            'nama_pemesan' => 'Test User',
            'email_pemesan' => 'test@example.com',
            'telepon_pemesan' => '08123456789',
            'jadwal' => (object) [
                'rutes' => collect([
                    (object) ['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung']
                ])
            ]
        ];

        // Map method ke channel code
        $channelMap = [
            'qris' => ['code' => 'QRIS', 'name' => 'QRIS'],
            'bca_va' => ['code' => 'VA_BCA', 'name' => 'BCA'],
            'mandiri_va' => ['code' => 'VA_MANDIRI', 'name' => 'MANDIRI'],
            'bni_va' => ['code' => 'VA_BNI', 'name' => 'BNI'],
            'bri_va' => ['code' => 'VA_BRI', 'name' => 'BRI'],
            'dana' => ['code' => 'EW_DANA', 'name' => 'DANA'],
            'gopay' => ['code' => 'EW_GOPAY', 'name' => 'GOPAY'],
            'ovo' => ['code' => 'EW_OVO', 'name' => 'OVO'],
        ];

        $channel = $channelMap[$method] ?? $channelMap['qris'];

        $result = $service->createPayment($pembayaran, $channel['code'], $channel['name']);

        return response()->json([
            'success' => $result['success'] ?? false,
            'method' => $method,
            'channel_code' => $channel['code'],
            'result' => $result,
            'environment' => config('paylabs.environment', 'sandbox'),
            'testing_mode' => config('paylabs.testing.enabled', false)
        ]);

    } catch (\Exception $e) {
        \Log::error('Paylabs create-payment-test error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => env('APP_DEBUG') ? $e->getTraceAsString() : null,
        ], 500);
    }
})->name('paylabs.create_payment_test');

// Route untuk review
Route::post('/customer/review', [CustomerController::class, 'storeReview'])->name('customer.review.store');

// Route artikel
Route::get('/customer/artikel', [ArtikelController::class, 'index'])->name('customer.artikel');
Route::get('/customer/artikel/{slug}', [ArtikelController::class, 'show'])->name('customer.artikel.detail');
Route::get('/customer/artikel/kategori/{kategori}', [ArtikelController::class, 'kategori'])->name('customer.artikel.kategori');

// ★★★ PAYMENT WEBHOOK ROUTES ★★★
Route::prefix('payment')->group(function () {
    // Webhook callback dari Paylabs (public access)
    Route::post('/callback', [PembayaranController::class, 'webhook'])
        ->name('payment.callback');

    // API callback (alternatif)
    Route::post('/api-callback', [PaymentController::class, 'callback'])
        ->name('payment.api_callback');
});

// ★★★ ROUTE UNTUK TESTING VIEW ★★★
Route::get('/test-payment-view/{kodePembayaran}', function($kodePembayaran) {
    // Buat data dummy untuk testing view
    $pembayaran = new \App\Models\Pembayaran();
    $pembayaran->id = 1;
    $pembayaran->kode_pembayaran = $kodePembayaran;
    $pembayaran->jumlah = 100000;
    $pembayaran->metode = 'qris';
    $pembayaran->status = 'menunggu';
    $pembayaran->waktu_kadaluarsa = now()->addMinutes(30);
    $pembayaran->qr_code = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=SMARTSHUTTLE-' . $kodePembayaran;
    $pembayaran->qris_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=SMARTSHUTTLE-' . $kodePembayaran;
    $pembayaran->nmid = 'ID123456789012345';
    $pembayaran->platform_trade_no = 'PLT' . time();

    return view('customer.test-payment', [
        'pembayaran' => $pembayaran,
        'total' => 100000,
        'sisa_waktu_detik' => 1800,
        'pemesanan' => (object) [
            'kode_booking' => 'BOOKTEST123',
            'jumlah_penumpang' => 2,
            'nama_pemesan' => 'Test User',
            'email_pemesan' => 'test@example.com',
            'telepon_pemesan' => '08123456789',
            'jadwal' => (object) [
                'shuttle' => (object) ['nama_shuttle' => 'Shuttle Premium', 'plat_nomor' => 'B 1234 CD'],
                'rutes' => collect([
                    (object) ['kota_asal' => 'Jakarta'],
                    (object) ['kota_tujuan' => 'Bandung']
                ]),
                'tanggal_keberangkatan' => now()->addDays(1),
                'waktu_keberangkatan' => '08:00:00'
            ]
        ],
        'metodePembayaran' => collect([
            (object) ['kode' => 'qris', 'nama' => 'QRIS', 'biaya_admin' => 0],
            (object) ['kode' => 'bca_va', 'nama' => 'BCA Virtual Account', 'biaya_admin' => 4000],
            (object) ['kode' => 'mandiri_va', 'nama' => 'Mandiri Virtual Account', 'biaya_admin' => 4000],
        ]),
        'penumpang' => collect([
            (object) ['nama_lengkap' => 'John Doe', 'nik' => '1234567890123456', 'nomor_kursi' => 'A1'],
            (object) ['nama_lengkap' => 'Jane Doe', 'nik' => '6543210987654321', 'nomor_kursi' => 'A2'],
        ]),
        'from' => 'Jakarta',
        'to' => 'Bandung',
        'date' => now()->addDays(1)->format('Y-m-d'),
        'time' => '08:00',
        'customer_name' => 'Test User',
        'customer_phone' => '08123456789',
        'customer_email' => 'test@example.com',
    ]);
})->name('test.payment.view');

// Route untuk kursi (separate namespace to avoid name collision)
Route::prefix('kursi')->name('kursi.')->group(function () {
    Route::get('/', [KursiController::class, 'index'])->name('index');
    Route::post('/proses', [KursiController::class, 'prosesKursi'])->name('proses');
    Route::get('/detail/{kode}', [KursiController::class, 'detailPesanan'])->name('detail_pesanan');
    Route::post('/batalkan/{pemesananId}', [KursiController::class, 'batalkanKursi'])->name('batalkan');
});

// ★★★ ROUTE FALLBACK ★★★
Route::fallback(function () {
    return redirect()->route('customer.beranda');
});
