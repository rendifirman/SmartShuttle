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
use App\Http\Controllers\Admin\ArmadaController;
use App\Http\Controllers\Admin\MasterTarifController;
use App\Http\Controllers\Admin\RuteController;
use App\Http\Controllers\KalkulatorEstimasiController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaylabsTestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

// ★★★ IMPORT CONTROLLER YANG DIPERLUKAN ★★★
use App\Http\Controllers\DriverJadwalController;

use App\Http\Middleware\UpdateAvatarSession;
use App\Http\Controllers\Admin\KontakPerusahaanController;
use App\Http\Controllers\Admin\AdminPemesananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ★★★ DRIVER AUTH ROUTES - ACCESSIBLE TO ANYONE (GUESTS & LOGGED-IN CUSTOMERS) ★★★
Route::prefix('driver')->group(function () {
    // Show driver login page - accessible to anyone (customers can log in as driver)
    Route::get('/login', [DriverController::class, 'showLogin'])
        ->name('driver.login');

    // Process driver login - accessible to anyone
    Route::post('/login', [DriverController::class, 'login'])
        ->name('driver.login.post');
});

// ★★★ SMARTSEND - KIRIM & CEK PAKET (ACCESSIBLE WITHOUT LOGIN) ★★★
Route::middleware(['web'])->prefix('smartsend')->name('customer.')->group(function () {
    // Halaman utama SmartSend
    Route::get('/', [CustomerController::class, 'smartsend'])->name('smartsend');

    // API untuk SmartSend (gunakan method baru di CustomerController)
    Route::post('/get-outlet-tujuan', [CustomerController::class, 'getOutletTujuanByRute'])
        ->name('smartsend.get-outlet-tujuan');

    Route::post('/kalkulator-harga', [CustomerController::class, 'kalkulatorHargaSmartSend'])
        ->name('smartsend.kalkulator-harga');

    // Cek status paket (AJAX)
    Route::post('/cek-status', [CustomerController::class, 'cekStatusPaket'])
        ->name('cek-status-paket');

    // Halaman cek resi (form input)
    Route::get('/cek-resi', [CustomerController::class, 'cekResi'])->name('cek-resi');

    // Proses validasi resi
    Route::post('/cek-resi', [CustomerController::class, 'prosesCekResi'])
        ->name('proses-cek-resi');

    // Halaman detail paket
    Route::get('/detail-paket/{kode_resi}', [CustomerController::class, 'detailPaket'])
        ->name('detail-paket');

    // Tracking paket
    Route::get('/tracking/{kode_resi}', [CustomerController::class, 'trackingPaket'])
        ->name('tracking-paket');
});

// ★★★ BUNGKUS SEMUA ROUTE YANG BUTUH AVATAR UPDATE DENGAN MIDDLEWARE ★★★
Route::middleware([UpdateAvatarSession::class])->group(function () {

// ★★★ BERANDA ROUTES (DRIVERJADWAL DATA ONLY) ★★★
// Halaman utama - menampilkan jadwal dari DriverJadwal (hanya yang sudah diklaim driver)
Route::get('/', [CustomerController::class, 'beranda'])->name('customer.beranda');
Route::get('/customer/beranda', [CustomerController::class, 'beranda']);

// Beranda dengan filter - mendukung filter asal, tujuan, tanggal, penumpang
Route::get('/beranda', [CustomerController::class, 'beranda'])->name('customer.beranda.filter');

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

    Route::get('/syarat-ketentuan-membership', [CustomerController::class, 'syaratKetentuanMembership'])
        ->name('customer.syarat.ketentuan.membership');

    // Halaman outlet - bisa diakses tamu
    Route::get('/customer/outlet', [CustomerController::class, 'outlet'])->name('customer.outlet');
    Route::get('/customer/outlet/filter', [CustomerController::class, 'outlet'])->name('customer.outlet.filter');
    Route::post('/customer/outlet/loadMore', [CustomerController::class, 'loadMoreOutlets'])
        ->name('customer.outlet.loadMore');
    Route::get('/outlet', [CustomerController::class, 'outlet']);

    // ★★★ PENCARIAN SHUTTLE - DRIVERJADWAL DATA ONLY ★★★
    // Halaman pencarian - mendukung parameter: asal, tujuan, tanggal, penumpang
    // Hanya menampilkan jadwal dari DriverJadwal (jadwal yang sudah diklaim driver)
    Route::get('/cari-shuttle', [CustomerController::class, 'showSearch'])->name('customer.search');
    Route::post('/cari-shuttle', [CustomerController::class, 'search'])->name('customer.search.post');

    // ★★★ ROUTE UNTUK FORM PENCARIAN (PERLU UNTUK route('customer.showSearch')) ★★★
    // Halaman form pencarian dan menampilkan hasil
    Route::get('/customer/search', [CustomerController::class, 'showSearch'])
        ->name('customer.showSearch');
    Route::post('/customer/search', [CustomerController::class, 'search']);

    // Alias untuk kompatibilitas
    Route::get('/search', [CustomerController::class, 'showSearch'])->name('customer.search.alt');

    // ★★★ CEK RESERVASI - BISA DIAKSES TANPA LOGIN ★★★
    Route::get('/customer/cek-reservasi', function() {
        return view('customer.cek-reservasi');
    })->name('customer.cek-reservasi');

    Route::post('/customer/cek-reservasi', [CekReservasiController::class, 'proses'])
        ->name('customer.cek-reservasi.proses');

    // Route untuk hasil reservasi (bisa diakses tanpa login)
    Route::get('/customer/cek-reservasi/hasil/{kode}', [CekReservasiController::class, 'hasil'])
        ->name('customer.cek-reservasi.hasil');

    // ★★★ AUTH ROUTES - HANYA UNTUK TAMU ★★★
    Route::middleware(['guest.customer'])->group(function () {
        // Login/Register tradisional
        Route::get('/customer/login', [CustomerController::class, 'showLogin'])->name('customer.login');
        Route::post('/customer/login', [CustomerController::class, 'login'])->name('customer.login.post');
        Route::get('/customer/register', [CustomerController::class, 'showRegister'])->name('customer.register');
        Route::post('/customer/register', [CustomerController::class, 'register'])->name('customer.register.post');

        // Google OAuth
        Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
            ->name('login.google');
        Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
            ->name('login.google.callback');

        // Email verification & password reset handled by standalone routes below

        // Simple web password reset routes (used by login views)
        Route::get('/password/reset', function () {
            return view('auth.passwords.email');
        })->name('password.request');

    });

    // ★★★ LOGOUT ★★★
    Route::post('/customer/logout', [CustomerController::class, 'logout'])
        ->name('customer.logout')
        ->middleware('auth.customer');

    // ★★★ ROUTES YANG BUTUH LOGIN ★★★
    Route::middleware(['auth:web'])->group(function () {

        // ============================================================
        // ★★★ AVATAR ROUTES - TAMBAHKAN DI SINI ★★★
        // ============================================================

        // AVATAR UPLOAD ROUTE
        Route::post('/customer/avatar/upload', [CustomerController::class, 'uploadAvatar'])
            ->name('customer.avatar.upload');

        // AVATAR DELETE ROUTE
        Route::delete('/customer/avatar/delete', [CustomerController::class, 'deleteAvatar'])
            ->name('customer.avatar.delete');

        // ============================================================

        // ★★★ PROFIL & DASHBOARD ★★★
        Route::get('/customer/dashboardprofile', [CustomerController::class, 'profil'])->name('customer.dashboardprofile');
        Route::get('/customer/profil', [CustomerController::class, 'profil'])->name('customer.profil');
        Route::get('/customer/profilcust', [CustomerController::class, 'profilDetail'])->name('customer.profilcust');
        Route::put('/customer/profilcust/update', [CustomerController::class, 'updateProfile'])->name('customer.profilcust.update');

        // ★★★ CUSTOMER ROUTES DARI PROMPT ★★★
        Route::middleware(['role:customer'])->prefix('customer')->name('customer.')->group(function () {
            // Beranda customer
            Route::get('/beranda-jadwal', [CustomerController::class, 'berandaCustomer'])->name('beranda.jadwal');

            // Search jadwal driver
            Route::get('/search-jadwal', [CustomerController::class, 'searchJadwalDriver'])->name('search.jadwal');

            // Booking dari driver jadwal
            Route::get('/booking-driver/{id_jadwal_driver}', [CustomerController::class, 'bookingFromDriver'])->name('booking.driver');
        });

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
        Route::get('/customer/pesan', [CustomerController::class, 'showBooking'])->name('customer.pesan.form');
        Route::post('/pesan-shuttle', [CustomerController::class, 'prosesPemesanan'])->name('customer.pesan.submit');

    // Route untuk booking dari driver_jadwals (id_jadwal_driver)
    Route::get('/customer/pesan/{id_jadwal_driver}', [CustomerController::class, 'pesan'])->name('customer.pesan')->middleware('auth');
        Route::post('/customer/pemesanan/proses', [CustomerController::class, 'prosesPemesanan'])->name('customer.pemesanan.proses');

        // ★★★ PROMO ★★★
        Route::get('/customer/promo/{id}', [CustomerController::class, 'showPromoDetail'])->name('customer.promo.detail');
        Route::post('/apply-promo', [CustomerController::class, 'validatePromo'])->name('customer.apply-promo');
        Route::post('/remove-promo', [CustomerController::class, 'removePromo'])->name('customer.remove-promo');
        Route::get('/get-promos', [CustomerController::class, 'getPromos'])->name('customer.get-promos');

        // ★★★ KURSI ★★★
        Route::get('/customer/kursi', [CustomerController::class, 'showPemilihanKursi'])->name('customer.kursi');
        Route::post('/customer/kursi/proses', [CustomerController::class, 'prosesPemilihanKursi'])->name('customer.kursi.proses');
        // AJAX endpoints to lock/unlock seats during selection
        Route::post('/customer/kursi/lock', [KursiController::class, 'lockSeat'])->name('customer.kursi.lock');
        Route::post('/customer/kursi/unlock', [KursiController::class, 'unlockSeat'])->name('customer.kursi.unlock');

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
        Route::post('/customer/detail-pemesanan/{kode_booking}/konfirmasi', [CustomerController::class, 'konfirmasiDetail'])->name('customer.detail_pemesanan.konfirmasi');
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
        Route::post('/cek-harga-paket', [CustomerController::class, 'cekHargaPaket'])->name('customer.cek-harga-paket');
        Route::post('/kirim-paket/proses', [CustomerController::class, 'prosesKirimPaket'])->name('customer.kirim-paket.proses');
    }); // ★★★ TUTUP ROUTES YANG BUTUH LOGIN ★★★

}); // ★★★ TUTUP MIDDLEWARE GROUP UpdateAvatarSession ★★★

// Alias route for default Laravel auth redirect
// Some auth middleware redirect guests to route('login') —
// map that to the customer login page used in this app.
Route::get('/login', function() {
    return redirect()->route('customer.login');
})->name('login');

// ★★★ ADMIN AUTH ROUTES ★★★
Route::middleware(['guest:admin'])->prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');
});

// ★★★ ADMIN ROUTES ★★★
// Admin routes tidak perlu include UpdateAvatarSession karena pakai guard berbeda
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Apply role checking to all admin routes except logout
    Route::middleware(['admin.role'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/kursi/peta/{jadwalId}', [KursiController::class, 'petaKursi'])
            ->name('kursi.peta');

        // Master Data Routes - Armada routes using Admin\ArmadaController
        Route::get('/armada', [ArmadaController::class, 'index'])
            ->middleware('permission:view_armada')
            ->name('armada');
        Route::get('/armada/create', [ArmadaController::class, 'create'])
            ->middleware('permission:manage_armada')
            ->name('armada.create');
        Route::post('/armada', [ArmadaController::class, 'store'])
            ->middleware('permission:manage_armada')
            ->name('armada.store');
        Route::get('/armada/{id}', [ArmadaController::class, 'show'])
            ->middleware('permission:view_armada')
            ->name('armada.show');
        Route::get('/armada/{id}/edit', [ArmadaController::class, 'edit'])
            ->middleware('permission:manage_armada')
            ->name('armada.edit');
        Route::put('/armada/{id}', [ArmadaController::class, 'update'])
            ->middleware('permission:manage_armada')
            ->name('armada.update');
        Route::delete('/armada/{id}', [ArmadaController::class, 'destroy'])
            ->middleware('permission:manage_armada')
            ->name('armada.destroy');
        Route::post('/armada/{id}/update-images', [ArmadaController::class, 'updateImages'])
            ->middleware('permission:manage_armada')
            ->name('armada.updateImages');
        Route::get('/armada/{id}/get-images', [ArmadaController::class, 'getImages'])
            ->middleware('permission:view_armada')
            ->name('armada.getImages');

        // ★★★ ROUTE JADWAL MANUAL ★★★
        // Jadwal Routes
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
        Route::get('/jadwal/drivers-by-rute', [JadwalController::class, 'getDriversByRute'])->name('jadwal.driversByRute');
        Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
        Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
        Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
        Route::get('/jadwal/{id}/penumpang', [JadwalController::class, 'showPenumpang'])->name('jadwal.penumpang');

        // ★★★ MASTER TARIF ROUTES ★★★
        Route::get('/master-tarif', [MasterTarifController::class, 'index'])
            ->name('master-tarif.index');
        Route::get('/master-tarif/create', [MasterTarifController::class, 'create'])
            ->name('master-tarif.create');
        Route::post('/master-tarif', [MasterTarifController::class, 'store'])
            ->name('master-tarif.store');
        Route::get('/master-tarif/{id}', [MasterTarifController::class, 'show'])
            ->name('master-tarif.show');
        Route::get('/master-tarif/{id}/edit', [MasterTarifController::class, 'edit'])
            ->name('master-tarif.edit');
        Route::put('/master-tarif/{id}', [MasterTarifController::class, 'update'])
            ->name('master-tarif.update');
        Route::delete('/master-tarif/{id}', [MasterTarifController::class, 'destroy'])
            ->name('master-tarif.destroy');
        Route::post('/master-tarif/{id}/deactivate', [MasterTarifController::class, 'deactivate'])
            ->name('master-tarif.deactivate');
        Route::post('/master-tarif/{id}/activate', [MasterTarifController::class, 'activate'])
            ->name('master-tarif.activate');
        Route::get('/master-tarif/export/csv', [MasterTarifController::class, 'export'])
            ->name('master-tarif.export');

        Route::get('/pusat', [AdminController::class, 'pusat'])
            ->middleware('permission:view_profile_perusahaan')
            ->name('pusat');
        Route::get('/cabangperusahaan', [AdminController::class, 'cabangPerusahaan'])
            ->middleware(['permission:view_cabang', 'branch.access'])
            ->name('cabangperusahaan');
        Route::get('/outletperusahaan', [AdminController::class, 'outletPerusahaan'])
            ->middleware(['permission:view_outlet', 'branch.access'])
            ->name('outletperusahaan');
        Route::get('/outletperusahaan/create', [AdminController::class, 'createOutlet'])
            ->middleware(['permission:manage_outlet', 'branch.access'])
            ->name('outletperusahaan.create');
        Route::post('/outletperusahaan', [AdminController::class, 'storeOutlet'])
            ->middleware(['permission:manage_outlet', 'branch.access'])
            ->name('outletperusahaan.store');
        Route::get('/outletperusahaan/{id}', [AdminController::class, 'showOutlet'])
            ->middleware(['permission:view_outlet', 'branch.access'])
            ->name('outletperusahaan.show');
        Route::get('/outletperusahaan/{id}/edit', [AdminController::class, 'editOutlet'])
            ->middleware(['permission:manage_outlet', 'branch.access'])
            ->name('outletperusahaan.edit');
        Route::put('/outletperusahaan/{id}', [AdminController::class, 'updateOutlet'])
            ->middleware(['permission:manage_outlet', 'branch.access'])
            ->name('outletperusahaan.update');
        Route::delete('/outletperusahaan/{id}', [AdminController::class, 'destroyOutlet'])
            ->middleware(['permission:manage_outlet', 'branch.access'])
            ->name('outletperusahaan.destroy');
        Route::get('/promo', [AdminController::class, 'promo'])
            ->middleware('permission:view_promo')
            ->name('promo');
        Route::get('/promo/create', [AdminController::class, 'createPromo'])
            ->middleware('permission:manage_promo')
            ->name('promo.create');
        Route::post('/promo', [AdminController::class, 'storePromo'])
            ->middleware('permission:manage_promo')
            ->name('promo.store');
        Route::get('/promo/{id}', [AdminController::class, 'showPromo'])
            ->middleware('permission:view_promo')
            ->name('promo.show');
        Route::get('/promo/{id}/edit', [AdminController::class, 'editPromo'])
            ->middleware('permission:manage_promo')
            ->name('promo.edit');
        Route::put('/promo/{id}', [AdminController::class, 'updatePromo'])
            ->middleware('permission:manage_promo')
            ->name('promo.update');
        Route::delete('/promo/{id}', [AdminController::class, 'destroyPromo'])
            ->middleware('permission:manage_promo')
            ->name('promo.destroy');
        Route::get('/driver', [AdminController::class, 'driver'])
            ->middleware('permission:view_driver')
            ->name('driver');
        Route::get('/pegawai', [AdminController::class, 'pegawai'])
            ->middleware('permission:view_pegawai')
            ->name('pegawai');
        Route::get('/pegawai/create', [AdminController::class, 'createPegawai'])
            ->middleware('permission:manage_pegawai')
            ->name('pegawai.create');
        Route::post('/pegawai', [AdminController::class, 'storePegawai'])
            ->middleware('permission:manage_pegawai')
            ->name('pegawai.store');
        Route::get('/pegawai/{id}', [AdminController::class, 'showPegawai'])
            ->middleware('permission:view_pegawai')
            ->name('pegawai.show');
        Route::get('/pegawai/{id}/edit', [AdminController::class, 'editPegawai'])
            ->middleware('permission:manage_pegawai')
            ->name('pegawai.edit');
        Route::put('/pegawai/{id}', [AdminController::class, 'updatePegawai'])
            ->middleware('permission:manage_pegawai')
            ->name('pegawai.update');
        Route::delete('/pegawai/{id}', [AdminController::class, 'destroyPegawai'])
            ->middleware('permission:manage_pegawai')
            ->name('pegawai.destroy');
        Route::get('/pegawai/{id}/get-data', [AdminController::class, 'getPegawaiData'])
            ->middleware('permission:view_pegawai')
            ->name('pegawai.get-data');
        // Rute Routes
        Route::get('/rute', [RuteController::class, 'index'])
            ->middleware('permission:view_rute')
            ->name('rute.index');
        Route::get('/rute/create', [RuteController::class, 'create'])
            ->middleware('permission:manage_rute')
            ->name('rute.create');
        Route::post('/rute', [RuteController::class, 'store'])
            ->middleware('permission:manage_rute')
            ->name('rute.store');
        Route::get('/rute/{id}', [RuteController::class, 'show'])
            ->middleware('permission:view_rute')
            ->name('rute.show');
        Route::get('/rute/{id}/edit', [RuteController::class, 'edit'])
            ->middleware('permission:manage_rute')
            ->name('rute.edit');
        Route::put('/rute/{id}', [RuteController::class, 'update'])
            ->middleware('permission:manage_rute')
            ->name('rute.update');
        Route::delete('/rute/{id}', [RuteController::class, 'destroy'])
            ->middleware('permission:manage_rute')
            ->name('rute.destroy');

        // Transaksi Routes
        Route::get('/smartsend-transaksi', [AdminController::class, 'smartsendTransaksi'])
            ->middleware('permission:view_smartsend_transaksi')
            ->name('smartsend-transaksi');
        Route::get('/perjalanan', [AdminController::class, 'perjalanan'])
            ->middleware('permission:view_perjalanan_transaksi')
            ->name('perjalanan');
        Route::get('/perjalanan/create', [AdminController::class, 'createPerjalanan'])
            ->middleware('permission:manage_perjalanan_transaksi')
            ->name('perjalanan.create');
        Route::post('/perjalanan', [AdminController::class, 'storePerjalanan'])
            ->middleware('permission:manage_perjalanan_transaksi')
            ->name('perjalanan.store');
        Route::get('/perjalanan/{id}/edit', [AdminController::class, 'editPerjalanan'])
            ->middleware('permission:manage_perjalanan_transaksi')
            ->name('perjalanan.edit');
        Route::put('/perjalanan/{id}', [AdminController::class, 'updatePerjalanan'])
            ->middleware('permission:manage_perjalanan_transaksi')
            ->name('perjalanan.update');
        Route::delete('/perjalanan/{id}', [AdminController::class, 'destroyPerjalanan'])
            ->middleware('permission:manage_perjalanan_transaksi')
            ->name('perjalanan.destroy');
        Route::get('/tiket-perjalanan', [AdminController::class, 'tiketPerjalanan'])
            ->middleware('permission:view_perjalanan_transaksi')
            ->name('tiket-perjalanan');
        Route::get('/armada-transaksi', [AdminController::class, 'armadaTransaksi'])
            ->middleware('permission:view_armada_transaksi')
            ->name('armada-transaksi');

        // SmartSend Routes
        Route::get('/smartsend-tiket', [AdminController::class, 'smartsendTiket'])
            ->middleware('permission:view_smartsend_tiket')
            ->name('smartsend-tiket');
        Route::get('/smartsend-perjalanan', [AdminController::class, 'smartsendPerjalanan'])
            ->middleware('permission:view_smartsend_perjalanan')
            ->name('smartsend-perjalanan');
        Route::get('/smartsend-armada', [AdminController::class, 'smartsendArmada'])
            ->middleware('permission:view_smartsend_armada')
            ->name('smartsend-armada');

        // SmartRent Route
        Route::get('/smartrent', [AdminController::class, 'smartrent'])
            ->middleware('permission:view_smartrent')
            ->name('smartrent');

        // Laporan Route
        Route::get('/laporan', [AdminController::class, 'laporan'])
            ->middleware('permission:view_laporan')
            ->name('laporan');

        // Pengaturan Routes
        Route::get('/user', [AdminController::class, 'user'])
            ->middleware('permission:view_user')
            ->name('user');
        Route::post('/user', [AdminController::class, 'storeUser'])
            ->middleware('permission:manage_user')
            ->name('user.store');
        Route::get('/menu', [AdminController::class, 'menu'])
            ->middleware('permission:view_menu')
            ->name('menu');

        // Kontak Perusahaan Routes
        Route::get('/kontakperusahaan', [KontakPerusahaanController::class, 'index'])
            ->middleware('permission:view_kontak')
            ->name('kontakperusahaan');
        Route::put('/kontakperusahaan/{id}', [KontakPerusahaanController::class, 'update'])
            ->middleware('permission:manage_kontak')
            ->name('kontakperusahaan.update');

        // Artikel Management Routes
        Route::get('/artikel', [AdminController::class, 'artikel'])
            ->middleware('permission:view_artikel')
            ->name('artikel.index');
        Route::get('/artikel/create', [AdminController::class, 'createArtikel'])
            ->middleware('permission:manage_artikel')
            ->name('artikel.create');
        Route::post('/artikel', [AdminController::class, 'storeArtikel'])
            ->middleware('permission:manage_artikel')
            ->name('artikel.store');
        Route::get('/artikel/{id}', [AdminController::class, 'showArtikel'])
            ->middleware('permission:view_artikel')
            ->name('artikel.show');
        Route::get('/artikel/{id}/edit', [AdminController::class, 'editArtikel'])
            ->middleware('permission:manage_artikel')
            ->name('artikel.edit');
        Route::put('/artikel/{id}', [AdminController::class, 'updateArtikel'])
            ->middleware('permission:manage_artikel')
            ->name('artikel.update');
        Route::delete('/artikel/{id}', [AdminController::class, 'destroyArtikel'])
            ->middleware('permission:manage_artikel')
            ->name('artikel.destroy');

        // ★★★ ADMIN PEMESANAN API ROUTES ★★★
        Route::get('/api/jadwal', [AdminPemesananController::class, 'getJadwal'])
            ->middleware('permission:view_perjalanan_transaksi')
            ->name('api.jadwal');
        Route::get('/api/promo/validate', [AdminPemesananController::class, 'validatePromo'])
            ->middleware('permission:view_perjalanan_transaksi')
            ->name('api.promo.validate');
        Route::get('/api/jadwal/{id}/kursi', [AdminPemesananController::class, 'getTakenSeats'])
            ->middleware('permission:view_perjalanan_transaksi')
            ->name('api.jadwal.kursi');
        Route::post('/api/pemesanan/create', [AdminPemesananController::class, 'createPemesanan'])
            ->middleware('permission:manage_perjalanan_transaksi')
            ->name('api.pemesanan.create');
        // Admin booking page (admin flow mirroring customer flow)
        Route::get('/transaksi/pemesanan-baru', [AdminPemesananController::class, 'showCreatePage'])
            ->middleware('permission:manage_perjalanan_transaksi')
            ->name('admin.pemesanan.create.page');
        Route::delete('/api/pemesanan/{id}', [AdminPemesananController::class, 'deletePemesanan'])
            ->middleware('permission:manage_perjalanan_transaksi')
            ->name('api.pemesanan.delete');
        // Admin booking - redirect to customer pesan with admin session
        Route::get('/admin-booking', [AdminPemesananController::class, 'adminBooking'])
            ->middleware('permission:manage_perjalanan_transaksi')
            ->name('booking');
        // Back to admin from customer pesan
        Route::get('/back-to-admin', [AdminPemesananController::class, 'backToAdmin'])
            ->middleware('permission:manage_perjalanan_transaksi')
            ->name('back');

    }); // Close admin.role middleware group

    // Logout Route (outside admin.role middleware)
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
}); // Close auth:admin middleware group

// ★★★ DRIVER ROUTES ★★★
Route::middleware(['auth:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [DriverController::class, 'logout'])->name('logout');

    // Additional driver routes
    Route::get('/jadwal', [DriverScheduleController::class, 'jadwal'])->name('jadwal');
    Route::get('/laporan', [DriverController::class, 'laporan'])->name('laporan');
    Route::get('/perjalanan', [DriverController::class, 'perjalanan'])->name('perjalanan');
    Route::get('/profile', [DriverController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [DriverController::class, 'profileEdit'])->name('profile.edit');
    Route::post('/profile/update', [DriverController::class, 'updateProfile'])->name('profile.update');
    Route::get('/pengaturan', [DriverController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan/update-schedule-accept-mode', [DriverController::class, 'updateScheduleAcceptMode'])->name('pengaturan.update-schedule-accept-mode');
    Route::get('/bantuan', [DriverController::class, 'bantuan'])->name('bantuan');

    // ★★★ API ENDPOINT: Ambil data penumpang real-time untuk trip tertentu ★★★
    Route::get('/api/passengers/{tripId}', [DriverController::class, 'getPassengersRealtime'])->name('api.passengers.realtime');

    // ★★★ ROUTES DRIVER JADWAL (FROM PROMPT) - Menggunakan DriverJadwalController ★★★
    Route::get('/dashboard', [DriverJadwalController::class, 'dashboard'])->name('dashboard');

    // Jadwal tersedia dari admin
    Route::get('/jadwal-tersedia', [DriverJadwalController::class, 'daftarJadwalTersedia'])
        ->name('jadwal.tersedia');

    // Ambil jadwal
    Route::post('/ambil-jadwal/{idJadwal}', [DriverJadwalController::class, 'ambilJadwal'])
        ->name('jadwal.ambil');

    // Jadwal saya
    Route::get('/jadwal-saya', [DriverJadwalController::class, 'jadwalSaya'])
        ->name('jadwal.saya');

    // Detail jadwal
    Route::get('/jadwal/{idJadwalDriver}', [DriverJadwalController::class, 'detailJadwal'])
        ->name('jadwal.detail');

    // Update status
    Route::put('/jadwal/{idJadwalDriver}/status', [DriverJadwalController::class, 'updateStatus'])
        ->name('jadwal.update-status');

    // Batalkan jadwal
    Route::delete('/jadwal/{idJadwalDriver}/batalkan', [DriverJadwalController::class, 'batalkanJadwal'])
        ->name('jadwal.batalkan');

    // ★★★ ROUTE UNTUK BACKWARD COMPATIBILITY ★★★
    Route::get('/jadwal', [DriverJadwalController::class, 'jadwalSaya'])->name('jadwal');
    Route::get('/available-schedules', [DriverJadwalController::class, 'daftarJadwalTersedia'])->name('available-schedules');

    // ★★★ DRIVER SCHEDULE ROUTES (DARI CODE ASLI) - Alternatif menggunakan DriverJadwalController ★★★
    Route::get('/available-schedules', [DriverJadwalController::class, 'availableSchedules'])
        ->name('available-schedules');

    Route::post('/take-schedule/{jadwalId}', [DriverJadwalController::class, 'takeSchedule'])
        ->name('schedule.take');

    Route::get('/my-schedules', [DriverJadwalController::class, 'mySchedules'])
        ->name('my-schedules');

    Route::put('/schedule/{id}/status', [DriverJadwalController::class, 'updateStatus'])
        ->name('schedule.update-status');
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

    // Kalkulator Estimasi API
    Route::post('/estimasi/get-outlet-tujuan', [KalkulatorEstimasiController::class, 'getOutletTujuan'])
        ->name('api.estimasi.get-outlet-tujuan');
    Route::post('/estimasi/hitung', [KalkulatorEstimasiController::class, 'hitungEstimasi'])
        ->name('api.estimasi.hitung');

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

    // Driver location API (driver updates location during trip)
    Route::post('/driver/location', [\App\Http\Controllers\API\DriverLocationController::class, 'updateLocation'])
        ->name('api.driver.location.update');

    // Start journey endpoint (driver clicks "Mulai Perjalanan")
    Route::post('/driver/journey/start', [\App\Http\Controllers\API\DriverLocationController::class, 'startJourney'])
        ->name('api.driver.journey.start');

    // Get journey state for a trip (driver-only)
    Route::get('/driver/journey/{tripId}/state', [\App\Http\Controllers\API\DriverLocationController::class, 'getJourneyState'])
        ->name('api.driver.journey.state');

    // Get complete trip details including stop_points (driver-only)
    Route::get('/driver/trip/{tripId}/detail', [\App\Http\Controllers\API\DriverLocationController::class, 'getTripDetail'])
        ->name('api.driver.trip.detail');

    // ★★★ Get passengers from admin jadwal penumpang data source ★★★
    Route::get('/driver/trip/{tripId}/passengers-admin', [\App\Http\Controllers\API\DriverLocationController::class, 'getTripPassengersFromAdmin'])
        ->name('api.driver.trip.passengers.admin');

    Route::post('/driver/journey/start', [\App\Http\Controllers\API\DriverLocationController::class, 'startJourney'])
        ->name('api.driver.journey.start');

    Route::post('/driver/trip/complete', [\App\Http\Controllers\API\DriverLocationController::class, 'completeTrip'])
        ->name('api.driver.trip.complete');

    Route::get('/driver/location/{driverId}/{tripId}/latest', [\App\Http\Controllers\API\DriverLocationController::class, 'getLatestLocation'])
        ->name('api.driver.location.latest');

    Route::get('/driver/location/{driverId}/{tripId}/all', [\App\Http\Controllers\API\DriverLocationController::class, 'getTripLocations'])
        ->name('api.driver.location.trip.locations');

    // Get active driver locations (for admin dashboard polling)
    Route::get('/driver/locations/active', [\App\Http\Controllers\API\DriverLocationController::class, 'getActiveDriverLocations'])
        ->name('api.driver.location.active');
});

// ★★★ ROUTE DEBUG (UNTUK TESTING) ★★★
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
            'requestType' => 'createPayment',
            'merchantId' => config('paylabs.mid'),
            'merchantTradeNo' => 'TEST' . time(),
            'amount' => 1000,
            'currency' => 'IDR',
        ];

        // Build the string to sign (same logic as in PaylabsService::generateSignature)
        ksort($payload);
        $stringToSign = '';
        foreach ($payload as $key => $value) {
            if ($value !== null && $value !== '') {
                $stringToSign .= $key . '=' . $value . '&';
            }
        }
        $stringToSign = rtrim($stringToSign, '&');

        $service = new \App\Services\PaylabsService();
        $signatureBase64 = $service->generateSignature($payload);
        $signatureRaw = base64_decode($signatureBase64);
        $signatureLong = bin2hex($signatureRaw);

        return response()->json([
            'success' => true,
            'signed_payload' => $stringToSign,
            'signature_base64' => $signatureBase64,
            'signature_raw_hex' => $signatureLong,
        ]);
    } catch (\Exception $e) {
        \Log::error('Paylabs signature test error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
})->name('paylabs.signature_test');

// Route untuk review
Route::post('/customer/review', [CustomerController::class, 'storeReview'])->name('customer.review.store');

// Route artikel
Route::get('/customer/artikel', [ArtikelController::class, 'index'])->name('customer.artikel');
Route::get('/customer/artikel/{slug}', [ArtikelController::class, 'show'])->name('customer.artikel.detail');
Route::get('/customer/artikel/kategori/{kategori}', [ArtikelController::class, 'kategori'])->name('customer.artikel.kategori');

// Route untuk kursi (separate namespace to avoid name collision)
Route::prefix('kursi')->name('kursi.')->group(function () {
    Route::get('/', [KursiController::class, 'index'])->name('index');
    Route::post('/proses', [KursiController::class, 'prosesKursi'])->name('proses');
    Route::get('/detail/{kode}', [KursiController::class, 'detailPesanan'])->name('detail_pesanan');
    Route::post('/batalkan/{pemesananId}', [KursiController::class, 'batalkanKursi'])->name('batalkan');
});

// Promo routes
Route::prefix('api/promo')->group(function () {
    Route::post('/eligible', [CustomerController::class, 'getEligiblePromos'])
        ->name('api.promo.eligible');
    Route::post('/validate', [CustomerController::class, 'validatePromo'])
        ->name('api.promo.validate');
});

// Route untuk Profile Perusahaan Admin
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/profile-perusahaan', [ProfilePerusahaanController::class, 'index'])
        ->middleware('permission:manage_profile_perusahaan')
        ->name('admin.profileperusahaan');
    Route::post('/profile-perusahaan/update', [ProfilePerusahaanController::class, 'update'])
        ->middleware('permission:manage_profile_perusahaan')
        ->name('admin.profileperusahaan.update');
    Route::post('/profile-perusahaan/layanan/{id}/update', [ProfilePerusahaanController::class, 'updateLayanan'])
        ->middleware('permission:manage_profile_perusahaan')
        ->name('admin.profileperusahaan.layanan.update');
    Route::post('/profile-perusahaan/layanan/create', [ProfilePerusahaanController::class, 'createLayanan'])
        ->middleware('permission:manage_profile_perusahaan')
        ->name('admin.profileperusahaan.layanan.create');
    Route::delete('/profile-perusahaan/layanan/{id}/delete', [ProfilePerusahaanController::class, 'deleteLayanan'])
        ->middleware('permission:manage_profile_perusahaan')
        ->name('admin.profileperusahaan.layanan.delete');
});

// Branch Management Routes
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    // Branch CRUD
    Route::prefix('cabang')->group(function () {
        Route::get('/create', [AdminController::class, 'createBranch'])
            ->middleware(['permission:manage_cabang', 'branch.access'])
            ->name('admin.cabang.create');
        Route::post('/', [AdminController::class, 'storeBranch'])
            ->middleware(['permission:manage_cabang', 'branch.access'])
            ->name('admin.cabang.store');
        Route::get('/{id}/edit', [AdminController::class, 'editBranch'])
            ->middleware(['permission:manage_cabang', 'branch.access'])
            ->name('admin.cabang.edit');
        Route::put('/{id}', [AdminController::class, 'updateBranch'])
            ->middleware(['permission:manage_cabang', 'branch.access'])
            ->name('admin.cabang.update');
        Route::delete('/{id}', [AdminController::class, 'destroyBranch'])
            ->middleware(['permission:manage_cabang', 'branch.access'])
            ->name('admin.cabang.destroy');
        Route::get('/{id}', [AdminController::class, 'getBranch'])
            ->middleware(['permission:view_cabang', 'branch.access'])
            ->name('admin.cabang.get');
    });
});

// ★★★ ROUTE FALLBACK ★★★
// Taruh di luar group middleware UpdateAvatarSession agar tidak terpengaruh
Route::fallback(function () {
    return redirect()->route('customer.beranda');
});

Route::get('/test-storage-write', function() {
    try {
        // Test tulis file
        $testContent = 'Test ' . date('Y-m-d H:i:s');
        Storage::disk('public')->put('test.txt', $testContent);

        // Test baca file
        $readContent = Storage::disk('public')->get('test.txt');

        return "Storage WRITE test: SUCCESS<br>Content: " . $readContent;
    } catch (\Exception $e) {
        return "Storage WRITE test: FAILED<br>Error: " . $e->getMessage();
    }
});

// Cek Resi Routes
Route::prefix('cek-resi')->name('customer.cek-resi.')->group(function () {
    Route::get('/', [CustomerController::class, 'cekResi'])->name('index');
    Route::post('/', [CustomerController::class, 'prosesCekResi'])->name('proses');
    Route::get('/detail/{kode_resi}', [CustomerController::class, 'detailPaket'])->name('detail');
    Route::post('/api/cek', [CustomerController::class, 'apiCekResi'])->name('api.cek');
    Route::get('/timeline/{id}', [CustomerController::class, 'getTimeline'])->name('timeline');
});

// Admin Routes untuk update status
Route::middleware(['auth:admin'])->prefix('admin/shipments')->name('admin.shipments.')->group(function () {
    Route::post('/{id}/update-status', [CustomerController::class, 'updateStatusShipment'])->name('update-status');
    Route::get('/{id}/tracking', [CustomerController::class, 'getTimeline'])->name('tracking');
});

// ★★★ ADDITIONAL AUTH ROUTES (Alternative endpoints) ★★★
// Note: Primary auth routes are defined in auth.php using password.email name
// These routes are removed to avoid duplicate route names during caching

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

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

// ★★★ PAYLABS DEBUG ROUTES ★★★
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

Route::get('/debug/master-harga', function() {
    try {
        $exists = Schema::hasTable('master_harga');
        $count = $exists ? DB::table('master_harga')->count() : 0;
        $data = $exists ? DB::table('master_harga')->get() : [];

        return response()->json([
            'table_exists' => $exists,
            'row_count' => $count,
            'data' => $data,
            'database' => DB::getDatabaseName()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'database' => DB::getDatabaseName() ?? 'Not connected'
        ], 500);
    }
})->middleware('auth');

// CSRF Token refresh route
Route::get('/refresh-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
})->middleware('web');

// ★★★ SMART RENT ROUTES ★★★
use App\Http\Controllers\Customer\SmartRentController;

// SmartRent routes (accessible without login)
Route::middleware(['web'])->prefix('smartrent')->name('smartrent.')->group(function () {
    Route::get('/', [SmartRentController::class, 'index'])->name('index');

    // Halaman booking dengan filter
    Route::get('/booking', [SmartRentController::class, 'booking'])->name('booking');

    // Halaman checkout/pesanan
    Route::match(['get', 'post'], '/checkout', [SmartRentController::class, 'checkout'])->name('checkout');

    // Halaman detail kendaraan
    Route::get('/vehicle/{id}', [SmartRentController::class, 'vehicleDetail'])->name('vehicle.detail');

    // Proses order langsung
    Route::post('/order', [SmartRentController::class, 'order'])->name('order');

    // Halaman pembayaran
    Route::get('/payment', [SmartRentController::class, 'payment'])->name('payment');

    // Proses pembayaran
    Route::post('/process-payment', [SmartRentController::class, 'processPayment'])->name('process.payment');

    // Halaman konfirmasi
    Route::get('/confirmation', [SmartRentController::class, 'confirmation'])->name('confirmation');

    // Form checkout (GET)
    Route::get('/smartrent/checkout', [SmartRentController::class, 'showCheckoutForm'])->name('checkout.form');

    // Proses checkout (POST)
    Route::post('/smartrent/checkout/process', [SmartRentController::class, 'processCheckout'])->name('checkout.process');

    // Halaman pembayaran (GET)
    Route::get('/smartrent/payment', [SmartRentController::class, 'showPayment'])->name('payment.show');

    // API Routes
    Route::get('/api/vehicle/{id}', [SmartRentController::class, 'getVehicle']);
    Route::post('/api/check-availability', [SmartRentController::class, 'checkAvailability']);
});

// SmartRent alias routes
Route::get('/smartrent', [SmartRentController::class, 'index'])->name('customer.smartrent');
// Test routes - untuk development/debugging saja
if (app()->isLocal()) {
    Route::get('/test-tarif-relationship', function () {
        try {
            $output = "<h1>Testing Rute and MasterTarif Many-to-Many Relationship</h1>";

            // Test 1: Get all tarifs for a specific route
            $output .= "<h2>Test 1: Get all tarifs for routes</h2>";
            $rutes = \App\Models\Rute::limit(3)->get();
            foreach ($rutes as $rute) {
                $output .= "<p><strong>Route: " . $rute->nama_rute . "</strong><br>";
                $tarifs = $rute->masterTarifs;
                $output .= "Number of tarifs: " . $tarifs->count() . "<br>";
                if ($tarifs->count() > 0) {
                    $output .= "Tarifs: ";
                    foreach ($tarifs as $tarif) {
                        $output .= $tarif->nama_tarif . " (" . $tarif->kode_tarif . "), ";
                    }
                }
                $output .= "</p>";
            }

            // Test 2: Get all routes for a specific tarif
            $output .= "<h2>Test 2: Get all routes for tarifs</h2>";
            $tarifs = \App\Models\MasterTarif::limit(3)->get();
            foreach ($tarifs as $tarif) {
                $output .= "<p><strong>Tarif: " . $tarif->nama_tarif . "</strong><br>";
                $rutes = $tarif->rutes;
                $output .= "Number of routes: " . $rutes->count() . "<br>";
                if ($rutes->count() > 0) {
                    $output .= "Routes: ";
                    foreach ($rutes as $rute) {
                        $output .= $rute->nama_rute . " (" . $rute->kode_rute . "), ";
                    }
                }
                $output .= "</p>";
            }

            // Test 3: Check the pivot table
            $output .= "<h2>Test 3: Pivot Table Data</h2>";
            $pivotData = \Illuminate\Support\Facades\DB::table('rute_master_tarif')->get();
            $output .= "Total records in pivot table: " . count($pivotData) . "<br>";
            if (count($pivotData) > 0) {
                $output .= "<table border='1' style='margin-top: 10px;'><tr><th>Rute ID</th><th>Tarif ID</th><th>Created At</th></tr>";
                foreach ($pivotData->take(10) as $record) {
                    $output .= "<tr><td>" . $record->rute_id . "</td><td>" . $record->master_tarif_id . "</td><td>" . $record->created_at . "</td></tr>";
                }
                $output .= "</table>";
            }

            $output .= "<h2>✓ All tests completed successfully!</h2>";
            return $output;
        } catch (Exception $e) {
            return "<h1 style='color: red;'>ERROR</h1>" .
                   "<p>Message: " . $e->getMessage() . "</p>" .
                   "<p>File: " . $e->getFile() . ":" . $e->getLine() . "</p>" .
                   "<pre>" . $e->getTraceAsString() . "</pre>";
        }
    })->name('test.tarif.relationship');
}

// Temporary debug route to inspect session and auth guard states (remove when done)
Route::get('/debug-session', function () {
    return response()->json([
        'session_all' => session()->all(),
        'session_id' => session()->getId(),
        'auth_default_check' => auth()->check(),
        'auth_web_check' => auth('web')->check(),
        'auth_admin_check' => auth('admin')->check(),
        'auth_user' => auth()->user(),
        'auth_web_user' => auth('web')->user(),
        'auth_admin_user' => auth('admin')->user(),
        'session_cookie' => request()->cookie(config('session.cookie')),
    ]);
});
