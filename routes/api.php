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
use App\Http\Controllers\KursiController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\SmartShuttlePasswordReset;

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
Route::post('/forgot-password', function (Request $request) {
    try {
        Log::info('Forgot password request', ['email' => $request->email]);

        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email tidak ditemukan'], 404);
        }

        // Generate simple token 6 digit
        $token = Str::upper(Str::random(6));

        // Simpan token ke database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Kirim token via email yang lebih baik
        try {
            $user->notify(new SmartShuttlePasswordReset($token));
            Log::info('Password reset email sent', ['email' => $request->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send email', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Token berhasil dibuat tapi gagal mengirim email',
                'token' => $token // Hanya untuk development
            ], 200);
        }

        return response()->json([
            'message' => 'Kode reset password telah dikirim ke email Anda'
        ]);

    } catch (\Exception $e) {
        Log::error('Forgot password error', [
            'email' => $request->email,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'message' => 'Terjadi kesalahan sistem'
        ], 500);
    }
});

Route::post('/reset-password', function (Request $request) {
    try {
        Log::info('Reset password request', ['email' => $request->email]);

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string|size:6',
            'password' => 'required|confirmed|min:8'
        ]);

        // Cari token di database
        $record = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Tidak ada permintaan reset password untuk email ini'
            ], 400);
        }

        if (!Hash::check($request->token, $record->token)) {
            Log::warning('Invalid token attempt', [
                'email' => $request->email,
                'provided_token' => $request->token
            ]);
            return response()->json([
                'message' => 'Kode reset password tidak valid'
            ], 400);
        }

        // Check token expiry (60 menit)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json([
                'message' => 'Kode reset password telah kadaluarsa'
            ], 400);
        }

        // Update password user
        $user = \App\Models\User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Log::info('Password reset successful', ['email' => $request->email]);

        return response()->json([
            'message' => 'Password berhasil direset! Silakan login dengan password baru Anda'
        ]);

    } catch (\Exception $e) {
        Log::error('Reset password error', [
            'email' => $request->email,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'message' => 'Terjadi kesalahan sistem'
        ], 500);
    }
});

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
    ->name('customer.pembayaran.simulasi')
    ->where('status', 'success|failed|expired');

Route::get('/payment/qr-code/{kodePembayaran}', [\App\Http\Controllers\PembayaranController::class, 'generateQRCode'])
    ->name('customer.pembayaran.qrcode');

Route::post('/payment/webhook', [\App\Http\Controllers\PembayaranController::class, 'webhook'])
    ->name('customer.pembayaran.webhook');
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
