<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KursiController;
use App\Http\Controllers\ETicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('customer.beranda');
});
// Halaman beranda (bisa diakses tamu)
Route::get('/customer/beranda', [CustomerController::class, 'beranda'])->name('customer.beranda');

// Auth routes - hanya untuk tamu (belum login)
Route::middleware(['guest.customer'])->group(function () {
    Route::get('/customer/login', [CustomerController::class, 'showLogin'])->name('customer.login');
    Route::post('/customer/login', [CustomerController::class, 'login'])->name('customer.login.post');
    Route::get('/customer/register', [CustomerController::class, 'showRegister'])->name('customer.register');
    Route::post('/customer/register', [CustomerController::class, 'register'])->name('customer.register.post');
    Route::post('/logout', function() {
    Auth::logout();
    return redirect('/login');
})->name('logout');
});

// Logout - hanya untuk yang sudah login
Route::post('/customer/logout', [CustomerController::class, 'logout'])
    ->name('customer.logout')
    ->middleware('auth.customer');

// Halaman yang butuh login
Route::middleware(['auth.customer'])->group(function () {
    // Dashboard utama (tetap pakai profil)
    Route::get('/customer/dashboardprofile', [CustomerController::class, 'profil'])->name('customer.dashboardprofile');
    Route::get('/customer/membership', [CustomerController::class, 'membership'])->name('customer.membership');
    Route::get('/customer/profilcust', [CustomerController::class, 'profilDetail'])->name('customer.profilcust');
    Route::put('/customer/profilcust/update', [CustomerController::class, 'updateProfile'])->name('customer.profilcust.update');
    Route::get('/customer/riwayat', [CustomerController::class, 'showRiwayat'])->name('customer.riwayat');

    // Membership routes
    Route::get('/membership/form', [CustomerController::class, 'showMembershipForm'])->name('customer.membership.form');
    Route::post('/membership/form', [CustomerController::class, 'processMembershipRegistration'])->name('customer.membership.form.submit');
    Route::get('/membership/payment', [CustomerController::class, 'showMembershipPayment'])->name('customer.membership.payment');
    Route::post('/membership/payment', [CustomerController::class, 'processMembershipPayment'])->name('customer.membership.process-payment');
    Route::post('/membership/payment/simulate', [CustomerController::class, 'simulateMembershipPayment'])->name('customer.membership.payment.simulate');
    Route::delete('/membership/payment/cancel', [CustomerController::class, 'cancelMembershipPayment'])->name('customer.membership.payment.cancel');
    Route::get('/membership/pending', [CustomerController::class, 'showMembershipPending'])->name('customer.membership.pending');
    Route::post('/membership/renew', [CustomerController::class, 'renewMembership'])->name('customer.membership.renew');

    // Loyalty points
    Route::post('/loyalty-points/use', [CustomerController::class, 'useLoyaltyPoints'])->name('customer.useLoyaltyPoints');
    Route::post('/loyalty-points/remove', [CustomerController::class, 'removeLoyaltyDiscount'])->name('customer.removeLoyaltyDiscount');

    // Opsional: route untuk update points
    Route::post('/membership/update-points', [CustomerController::class, 'updatePoints'])->name('customer.updatePoints');
});

// Route untuk pencarian - bisa diakses tamu
Route::get('/customer/search', [CustomerController::class, 'showSearch'])->name('customer.search');
Route::post('/customer/search', [CustomerController::class, 'search'])->name('customer.search.post');

// ★★★ TAMBAHKAN ROUTE KIRIM PAKET DI SINI ★★★
Route::get('/customer/kirim-paket', [CustomerController::class, 'kirimPaket'])->name('customer.kirim-paket');
Route::post('/customer/kirim-paket', [CustomerController::class, 'prosesKirimPaket'])->name('customer.kirim-paket.proses');

// Route untuk outlet - bisa diakses tamu
Route::get('/customer/outlet', [OutletController::class, 'index'])->name('customer.outlet');

Route::get('/customer/contact', function () {
    return view('customer.contact');
})->name('customer.contact');

// ★★★ ROUTES PEMESANAN & KURSI ★★★
Route::middleware(['auth.customer'])->group(function () {
    // Pemesanan - GET untuk menampilkan form
    Route::get('/customer/pesan', [CustomerController::class, 'showBooking'])->name('customer.pesan');

    // Pemesanan - POST untuk proses data
    Route::post('/customer/pemesanan/proses', [PemesananController::class, 'prosesPemesanan'])
        ->name('customer.pemesanan.proses');

    Route::post('/customer/pesan/validasi-promo', [PemesananController::class, 'validasiPromo'])->name('customer.pesan.validasi_promo');

    // Routes untuk promo
    Route::post('/apply-promo', [CustomerController::class, 'validatePromo'])->name('customer.apply-promo');
    Route::post('/remove-promo', [CustomerController::class, 'removePromo'])->name('customer.remove-promo');

    // Kursi
    Route::get('/customer/kursi', [KursiController::class, 'index'])->name('customer.kursi');
    Route::post('/customer/kursi/proses', [KursiController::class, 'prosesKursi'])->name('customer.kursi.proses');
    Route::get('/customer/kursi/batalkan/{pemesananId}', [KursiController::class, 'batalkanKursi'])->name('customer.kursi.batalkan');

    // Detail Pesanan (setelah memilih kursi)
    Route::get('/customer/detail-pesanan/{kode}', [KursiController::class, 'detailPesanan'])->name('customer.detail_pesanan');

    // Pembayaran
    Route::get('/customer/pembayaran/{kode}', [PembayaranController::class, 'index'])->name('customer.pembayaran');
    Route::post('/customer/pembayaran/{kode}/pilih-metode', [PembayaranController::class, 'pilihMetode'])->name('customer.pembayaran.pilih_metode');
    Route::get('/customer/pembayaran/simulasi/{kodePembayaran}', [PembayaranController::class, 'simulasiPembayaran'])->name('customer.pembayaran.simulasi');
    Route::get('/customer/pembayaran/cek-status/{kodePembayaran}', [PembayaranController::class, 'cekStatus'])->name('customer.pembayaran.cek_status');

    // Riwayat
    Route::get('/customer/riwayat-pemesanan', [PemesananController::class, 'riwayat'])->name('customer.riwayat');
    Route::get('/customer/detail-pemesanan/{kode}', [PemesananController::class, 'detail'])->name('customer.detail_pemesanan');
});

// Password reset routes
Route::prefix('password')->group(function () {
    Route::get('/forgot', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/token', [AuthController::class, 'showTokenForm'])->name('password.token');
    Route::post('/token/verify', [AuthController::class, 'verifyToken'])->name('password.token.verify');
    Route::get('/reset', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset', [AuthController::class, 'reset'])->name('password.update');
});

// Alias untuk kompatibilitas
Route::get('/password/reset/{token}', function ($token) {
    return redirect()->route('password.reset')->with('token', $token);
})->name('password.reset.with.token');

// API Routes untuk kursi
Route::prefix('api')->group(function () {
    Route::get('/kursi-tersedia/{jadwalId}', [KursiController::class, 'getKursiTersediaAPI'])
        ->name('api.kursi.tersedia');

    Route::post('/validasi-kursi', [KursiController::class, 'validasiKursiAPI'])
        ->name('api.kursi.validasi');
});

// Admin routes untuk peta kursi
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/kursi/peta/{jadwalId}', [KursiController::class, 'petaKursi'])
        ->name('admin.kursi.peta');
});

// Route untuk outlet - bisa diakses tamu
Route::get('/customer/outlet', [OutletController::class, 'index'])->name('customer.outlet');
Route::get('/customer/outlet/filter', [OutletController::class, 'filter'])->name('customer.outlet.filter');
Route::get('/outlet', [CustomerController::class, 'outlet'])->name('customer.outlet');

// Routes untuk customer
Route::middleware(['web'])->group(function () {
    // Halaman pencarian
    Route::get('/cari-shuttle', [CustomerController::class, 'showSearch'])->name('customer.search');

    // Proses pencarian (POST atau GET)
    Route::post('/cari-shuttle', [CustomerController::class, 'search']);

    // Proses pemesanan
    Route::post('/pesan-shuttle', [CustomerController::class, 'prosesPemesanan'])->name('customer.pesan.submit');
});

// ★★★ ROUTE E-TICKET YANG DISEDERHANAKAN ★★★
Route::middleware(['auth.customer'])->group(function () {
    // Route utama untuk e-ticket
    Route::get('/customer/e-ticket/{kode_booking}', [ETicketController::class, 'show'])
        ->name('customer.e_ticket'); // Nama route: customer.e_ticket

    // Route alternatif dengan query parameter (backup)
    Route::get('/customer/ticket', [ETicketController::class, 'showByQuery'])
        ->name('customer.ticket.query');

    // Route untuk download PDF
    Route::get('/customer/e-ticket/{kode_booking}/download', [ETicketController::class, 'download'])
        ->name('customer.e_ticket.download');

    // Route untuk QR Code
    Route::get('/customer/e-ticket/{kode_booking}/qr', [ETicketController::class, 'qrCode'])
        ->name('customer.e_ticket.qr');
});

// Route debug untuk testing (opsional, bisa dihapus di production)
Route::get('/debug/e-ticket/{kode_booking}', function($kode_booking) {
    \Log::info('Debug e-ticket access', [
        'kode_booking' => $kode_booking,
        'route' => 'direct_access',
        'time' => now()
    ]);

    return redirect()->route('customer.e_ticket', ['kode_booking' => $kode_booking]);
})->name('debug.e_ticket');

// Syarat dan ketentuan khusus membership
Route::get('/syarat-ketentuan-membership', [CustomerController::class, 'syaratKetentuanMembership'])->name('customer.syarat.ketentuan.membership');

// Static pages - bisa diakses tamu
Route::get('/bantuan', [CustomerController::class, 'bantuan'])->name('customer.bantuan');
Route::get('/syarat-ketentuan', [CustomerController::class, 'syaratKetentuan'])->name('customer.syarat.ketentuan');
Route::get('/kebijakan-privasi', [CustomerController::class, 'kebijakanPrivasi'])->name('customer.kebijakan.privasi');
Route::get('/kontak', [CustomerController::class, 'contact'])->name('customer.contact');
Route::post('/kontak', [CustomerController::class, 'submitContact'])->name('customer.contact.submit');
