<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\SecurityController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\OutletController;
use App\Http\Controllers\API\LayananController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\PemesananController; // TAMBAHKAN INI
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\KursiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ==================== PUBLIC ROUTES ====================

// AUTHENTICATION
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// PASSWORD RESET
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// Paylabs public callback (expects POST from Paylabs)
Route::post('/pembayaran/callback', [\App\Http\Controllers\PembayaranController::class, 'webhook']);

// PUBLIC BRANCH APIs
Route::prefix('branches')->group(function () {
    Route::get('/', [BranchController::class, 'index']);
    Route::get('/cities', [BranchController::class, 'cities']);
    Route::get('/nearby', [BranchController::class, 'nearby']);
    Route::get('/{id}', [BranchController::class, 'show']);
    Route::get('/{id}/outlets', [BranchController::class, 'outlets']);
});

// PUBLIC OUTLET APIs
Route::prefix('outlets')->group(function () {
    Route::get('/', [OutletController::class, 'index']);
    Route::get('/types', [OutletController::class, 'types']);
    Route::get('/nearby', [OutletController::class, 'nearby']);
    Route::get('/{id}', [OutletController::class, 'show']);
});

// PUBLIC LAYANAN APIs
Route::prefix('layanan')->group(function () {
    Route::get('/', [LayananController::class, 'index']);
    Route::get('/homepage', [LayananController::class, 'forHomepage']);
    Route::get('/kategori', [LayananController::class, 'kategoriList']);
    Route::get('/kategori/{kategori}', [LayananController::class, 'byKategori']);
    Route::get('/{id}', [LayananController::class, 'show']);
    Route::get('/slug/{slug}', [LayananController::class, 'bySlug']);
});

// PUBLIC SCHEDULE APIs
Route::prefix('schedules')->group(function () {
    Route::get('/', [ScheduleController::class, 'index']);
    Route::get('/active', [ScheduleController::class, 'active']);
    Route::post('/search', [ScheduleController::class, 'search']);
    Route::get('/today', [ScheduleController::class, 'today']);
    Route::get('/upcoming', [ScheduleController::class, 'upcoming']);
    Route::get('/layanan/{layananId}', [ScheduleController::class, 'byLayanan']);
    Route::get('/route/{routeId}', [ScheduleController::class, 'byRoute']);
    Route::get('/{id}', [ScheduleController::class, 'show']);
    Route::get('/{id}/availability', [ScheduleController::class, 'checkAvailability']);
});

// PUBLIC KURSI APIs
Route::middleware('api')->group(function () {
    Route::post('/validasi-kursi', [KursiController::class, 'validasiKursiAPI']);
    Route::get('/kursi-tersedia/{jadwalId}', [KursiController::class, 'getKursiTersediaAPI']);
    Route::post('/kursi-validate', [KursiController::class, 'validateSeatsAPI']);
});
Route::get('/kursi-tersedia/{jadwalId}', [KursiController::class, 'getKursiTersediaAPI'])
    ->name('api.kursi.tersedia');

Route::post('/validasi-kursi', [KursiController::class, 'validasiKursiAPI'])
    ->name('api.kursi.validasi');

// PUBLIC PROMO APIs (UNTUK VALIDASI)
Route::post('/promo/validate', [PemesananController::class, 'validatePromoAPI']);

// ==================== PROTECTED ROUTES (MEMBUTUHKAN AUTH) ====================
Route::middleware(['auth:sanctum'])->group(function () {
    // AUTHENTICATION
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/sessions', [AuthController::class, 'getSessions']);
    Route::delete('/sessions/{tokenId}', [AuthController::class, 'revokeSession']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
// PAYMENT APIs
Route::prefix('payment')->group(function () {
    // Create payment
    Route::post('/create', [\App\Http\Controllers\API\PaymentController::class, 'createPayment']);

    // Get payment status
    Route::get('/status/{kodePembayaran}', [\App\Http\Controllers\API\PaymentController::class, 'getPaymentStatus']);

    // Get payment methods
    Route::get('/methods', [\App\Http\Controllers\API\PaymentController::class, 'getPaymentMethods']);

    // Simulate payment (for demo)
    Route::post('/simulate', [\App\Http\Controllers\API\PaymentController::class, 'simulatePayment']);

    // Get QR code
    Route::get('/qr-code/{kodePembayaran}', [\App\Http\Controllers\API\PaymentController::class, 'getQRCode']);

    // Paylabs callback (public)
    Route::post('/callback', [\App\Http\Controllers\API\PaymentController::class, 'callback'])
        ->name('api.payment.callback');
});

// Tambahkan juga di web.php untuk web routes
Route::post('/payment/{kodePembayaran}/simulate/{status?}', [\App\Http\Controllers\PembayaranController::class, 'simulasiPembayaran'])
    ->name('api.customer.pembayaran.simulasi')
    ->where('status', 'success|failed|expired');

Route::get('/payment/qr-code/{kodePembayaran}', [\App\Http\Controllers\PembayaranController::class, 'generateQRCode'])
    ->name('api.customer.pembayaran.qrcode');

Route::post('/payment/webhook', [\App\Http\Controllers\PembayaranController::class, 'webhook'])
    ->name('api.customer.pembayaran.webhook');
    // PROFILE
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/avatar', [ProfileController::class, 'updateProfilePicture']);

    // PEMESANAN APIs
    Route::prefix('pemesanan')->group(function () {
        Route::get('/', [PemesananController::class, 'index']); // Daftar pemesanan
        Route::post('/', [PemesananController::class, 'store']); // Buat pemesanan baru
        Route::get('/riwayat', [PemesananController::class, 'riwayat']); // Riwayat pemesanan
        Route::get('/{kode_booking}', [PemesananController::class, 'show']); // Detail pemesanan
        Route::put('/{kode_booking}/cancel', [PemesananController::class, 'cancel']); // Batalkan pemesanan
        Route::post('/{kode_booking}/pilih-kursi', [PemesananController::class, 'pilihKursi']); // Pilih kursi
        Route::post('/{kode_booking}/bayar', [PemesananController::class, 'bayar']); // Proses pembayaran
        Route::get('/{kode_booking}/eticket', [PemesananController::class, 'eTicket']); // E-Ticket
    });

    // EMAIL VERIFICATION
    Route::post('/email/verification-notification', function (Request $request) {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email sudah terverifikasi']);
        }

        // Untuk testing, langsung verifikasi
        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email berhasil diverifikasi']);
    });

    // SECURITY
    Route::post('/security/two-factor/enable', [SecurityController::class, 'enableTwoFactor']);
    Route::post('/security/two-factor/disable', [SecurityController::class, 'disableTwoFactor']);
    Route::get('/security/settings', [SecurityController::class, 'getSecuritySettings']);

    // TEST ROUTE FOR AVATAR UPLOAD
    Route::post('/test-avatar-upload', function (Request $request) {
        try {
            if (!$request->hasFile('avatar')) {
                return response()->json(['message' => 'Tidak ada file yang diupload'], 400);
            }

            $file = $request->file('avatar');
            $path = $file->store('test-avatars', 'public');

            return response()->json([
                'message' => 'File berhasil diupload',
                'path' => $path,
                'url' => url('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Upload gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // TEST ROUTE FOR EMAIL VERIFICATION
    Route::post('/test-verify-email', function (Request $request) {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email sudah terverifikasi']);
        }

        $user->markEmailAsVerified();

        return response()->json([
            'message' => 'Email berhasil diverifikasi (test)',
            'user' => $user->fresh()
        ]);
    });
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth:sanctum', 'admin.role'])->group(function () {
    Route::post('/admin/create-user', [AuthController::class, 'createUserWithRole']);
    Route::post('/users/assign-role', [RoleController::class, 'assignRole']);
    Route::post('/users/remove-role', [RoleController::class, 'removeRole']);
    Route::get('/users/{userId}/roles', [RoleController::class, 'getUserRoles']);
});
Route::get('/test-db', function() {
    try {
        // Test 1: Cek koneksi
        \DB::connection()->getPdo();
        $dbName = \DB::connection()->getDatabaseName();

        // Test 2: Cek tabel
        $tableExists = \Schema::hasTable('metode_pembayaran');

        // Test 3: Query sederhana
        $count = \DB::table('metode_pembayaran')->count();
        $first = \DB::table('metode_pembayaran')->first();

        return response()->json([
            'success' => true,
            'database' => $dbName,
            'table_exists' => $tableExists,
            'count' => $count,
            'first_record' => $first,
            'columns' => array_keys((array)$first)
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
// Testing Paylabs Integration - Untuk developer internal
Route::prefix('dev')->group(function () {
    // Test connection to Paylabs (public for testing)
    Route::get('/paylabs/test-connection', function () {
        try {
            $paylabsService = new \App\Services\PaylabsService();

            $result = $paylabsService->testConnection();

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Connection to Paylabs successful' : 'Connection failed',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ], 500);
        }
    });

    // Test signature generation
    Route::get('/paylabs/test-signature', function () {
        try {
            $paylabsService = new \App\Services\PaylabsService();

            $testData = [
                'merchantId' => config('paylabs.mid'),
                'merchantTradeNo' => 'TEST' . time(),
                'amount' => 100000,
                'currency' => 'IDR'
            ];

            $signature = $paylabsService->generateSignature($testData);

            return response()->json([
                'success' => true,
                'message' => 'Signature generated successfully',
                'data' => [
                    'original_data' => $testData,
                    'signature' => $signature,
                    'signature_length' => strlen($signature)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Signature generation failed: ' . $e->getMessage()
            ], 500);
        }
    });

    // Simulate Paylabs callback (for testing)
    Route::post('/paylabs/simulate-callback', function (Request $request) {
        \Log::info('Simulated Paylabs Callback:', $request->all());

        // Panggil callback handler yang asli
        $paymentController = new \App\Http\Controllers\API\PaymentController(
            new \App\Services\PaylabsService()
        );

        return $paymentController->callback($request);
    });
    // Tambahkan di routes/api.php
Route::prefix('payment')->group(function () {
    // Create payment
    Route::post('/create', [\App\Http\Controllers\API\PaymentController::class, 'createPayment']);

    // Get payment status
    Route::get('/status/{kodePembayaran}', [\App\Http\Controllers\API\PaymentController::class, 'getPaymentStatus']);

    // Get payment methods
    Route::get('/methods', [\App\Http\Controllers\API\PaymentController::class, 'getPaymentMethods']);

    // Paylabs callback (public)
    Route::post('/callback', [\App\Http\Controllers\API\PaymentController::class, 'callback'])
        ->name('api.dev.payment.callback');

    // Test Paylabs connection
    Route::get('/test-connection', [\App\Http\Controllers\API\PaymentController::class, 'testConnection'])
        ->name('api.payment.test');

    // Simulate payment (for demo)
    Route::post('/simulate', [\App\Http\Controllers\API\PaymentController::class, 'simulatePayment'])
        ->middleware('auth:sanctum');

    // Get QR code
    Route::get('/qr-code/{kodePembayaran}', [\App\Http\Controllers\API\PaymentController::class, 'getQRCode']);
});
});
