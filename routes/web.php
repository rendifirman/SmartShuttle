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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ★★★ ROUTE UTAMA DAN TAMU ★★★
Route::get('/', [CustomerController::class, 'beranda'])->name('customer.beranda');
Route::get('/customer/beranda', [CustomerController::class, 'beranda'])->name('customer.beranda');

// Halaman statis - bisa diakses tamu
Route::get('/bantuan', [CustomerController::class, 'bantuan'])->name('customer.bantuan');
Route::get('/syarat-ketentuan', [CustomerController::class, 'syaratKetentuan'])->name('customer.syarat.ketentuan');
Route::get('/kebijakan-privasi', [CustomerController::class, 'kebijakanPrivasi'])->name('customer.kebijakan.privasi');
Route::get('/kontak', [CustomerController::class, 'contact'])->name('customer.contact');
Route::post('/kontak', [CustomerController::class, 'submitContact'])->name('customer.contact.submit');
Route::get('/syarat-ketentuan-membership', [CustomerController::class, 'syaratKetentuanMembership'])->name('customer.syarat.ketentuan.membership');

// Halaman outlet - bisa diakses tamu
Route::get('/customer/outlet', [CustomerController::class, 'outlet'])->name('customer.outlet');
Route::get('/customer/outlet/filter', [CustomerController::class, 'outlet'])->name('customer.outlet.filter');
Route::get('/outlet', [CustomerController::class, 'outlet'])->name('customer.outlet');

// Halaman pencarian shuttle - bisa diakses tamu
Route::get('/cari-shuttle', [CustomerController::class, 'showSearch'])->name('customer.search');
Route::post('/cari-shuttle', [CustomerController::class, 'search'])->name('customer.search.post');
Route::get('/customer/search', [CustomerController::class, 'showSearch'])->name('customer.search');
Route::post('/customer/search', [CustomerController::class, 'search'])->name('customer.search.post');

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

    // Password reset
    Route::prefix('password')->group(function () {
        Route::get('/forgot', [AuthController::class, 'showForgotForm'])->name('password.request');
        Route::post('/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
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

    // Promo API
    Route::post('/customer/pesan/validasi-promo', [CustomerController::class, 'validatePromo'])->name('customer.pesan.validasi_promo');

    // Loyalty Points API
    Route::post('/api/loyalty-points/use', [CustomerController::class, 'useLoyaltyPoints'])->name('api.loyalty-points.use');
    Route::post('/api/loyalty-points/remove', [CustomerController::class, 'removeLoyaltyDiscount'])->name('api.loyalty-points.remove');

    // Payment API
    Route::post('/payment/callback', [PembayaranController::class, 'webhook'])->name('api.payment.callback');
});

// ★★★ ADMIN ROUTES ★★★
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/kursi/peta/{jadwalId}', [KursiController::class, 'petaKursi'])
        ->name('admin.kursi.peta');
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

// Route untuk review
Route::post('/customer/review', [CustomerController::class, 'storeReview'])->name('customer.review.store');

// ★★★ ROUTE FALLBACK ★★★
Route::fallback(function () {
    return redirect()->route('customer.beranda');
});
