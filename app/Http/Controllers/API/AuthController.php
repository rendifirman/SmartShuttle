<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Mail\ResetTokenMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ... (existing methods tetap sama)

    public function performLogin(array $data)
    {
        // Validasi manual
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // Coba login dengan credentials
        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ], isset($data['remember']) ? true : false)) {
            return [
                'success' => false,
                'message' => 'Email atau password salah'
            ];
        }

        $user = Auth::user();

        // Cek status user
        if ($user->status === 'inactive') {
            // Jika akun inactive, logout session yang mungkin sudah aktif
            Auth::logout();
            return [
                'success' => false,
                'message' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.'
            ];
        }

        // Buat token API seperti biasa (untuk API)
        $token = $user->createToken('SmartShuttle-API')->plainTextToken;

        return [
            'success' => true,
            'user' => $user->load('roles'),
            'token' => $token,
            'role' => $user->getRoleNames()->first()
        ];
    }

    // PERBAIKAN DI SINI: Method showForgotForm
    public function showForgotForm()
    {
        // Pastikan view ini ada di resources/views/auth/forgot-password.blade.php
        return view('auth.forgot-password');
    }

    // Proses kirim link reset ke email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;
        \Log::info('Password reset requested', ['email' => $email]);

        // generate token 6 karakter (huruf besar + angka)
        $token = $this->generateShortToken(6);

        // hash token untuk disimpan
        $hashed = Hash::make($token);

        // simpan/replace di tabel password_resets
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $hashed, 'created_at' => now()]
        );

        // buat URL reset lengkap
        $resetUrl = route('password.reset', ['token' => $token]) . '?email=' . urlencode($email);

        // kirim email (gunakan Mailable)
        try {
            Mail::to($email)->send(new ResetTokenMail($token, $resetUrl));
        } catch (\Exception $e) {
            \Log::warning('Failed to send reset token email', ['err' => $e->getMessage()]);
        }

        return redirect()
            ->route('password.token')
            ->with('success', 'Token reset telah dikirim ke email Anda.')
            ->with('email_for_reset', $email);
    }

   private function generateShortToken($length = 6)
{
    // Hanya angka (0-9) dan huruf kapital (A-Z) - TIDAK ada huruf kecil
    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';

    for ($i = 0; $i < $length; $i++) {
        $token .= $chars[random_int(0, strlen($chars) - 1)];
    }

    return $token;
}

    // PERBAIKAN DI SINI: Method showTokenForm
    public function showTokenForm()
    {
        // Pastikan view ini ada di resources/views/auth/token.blade.php
        return view('auth.token');
    }

    public function verifyToken(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'token' => 'required|string'
    ]);

    $email = $request->email;
    $tokenInput = $request->token;

    $record = DB::table('password_resets')->where('email', $email)->first();

    if (!$record) {
        return back()->withErrors(['token' => 'Token tidak ditemukan untuk email ini. Silakan minta token lagi.']);
    }

    // cek expired (15 menit)
    $created = Carbon::parse($record->created_at);
    if ($created->diffInMinutes(now()) > 15) {
        // hapus record lama
        DB::table('password_resets')->where('email', $email)->delete();
        return back()->withErrors(['token' => 'Token sudah kadaluwarsa. Silakan minta token baru.']);
    }

    // verifikasi hash
    if (!Hash::check($tokenInput, $record->token)) {
        return back()->withErrors(['token' => 'Token tidak cocok. Periksa kembali token yang Anda terima.']);
    }

    // token valid - simpan email dan token di session
    session(['email' => $email, 'token' => $tokenInput]);

    \Log::info('Token verified successfully', ['email' => $email]);

    // redirect ke reset password dengan token dan email
    return redirect()->route('password.reset')
        ->with('success', 'Token berhasil diverifikasi. Silakan buat password baru.');
}

   // PERBAIKAN DI SINI: Method showResetForm
public function showResetForm(Request $request)
{
    // Ambil token dan email dari URL parameters ATAU dari session
    $token = $request->token ?: session('token');
    $email = $request->email ?: session('email');

    // DEBUG: Log untuk memastikan data tersedia
    \Log::info('showResetForm - Data:', [
        'token_from_request' => $request->token,
        'token_from_session' => session('token'),
        'email_from_request' => $request->email,
        'email_from_session' => session('email'),
        'final_token' => $token,
        'final_email' => $email
    ]);

    return view('auth.reset-password', [
        'token' => $token,
        'email' => $email
    ]);
}

// Proses reset password
public function reset(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email|exists:users,email',
        'password' => 'required|confirmed|min:8',
    ]);

    \Log::info('Attempting password reset (custom token)', [
        'email' => $request->email,
        'token_present' => $request->has('token')
    ]);

    // Ambil kembali token hashed dari table password_resets
    $record = DB::table('password_resets')->where('email', $request->email)->first();

    if (!$record) {
        return back()->withErrors(['email' => 'Token tidak valid atau sudah dipakai.'])->withInput();
    }

    // Cek token cocok
    if (!Hash::check($request->token, $record->token)) {
        return back()->withErrors(['token' => 'Token tidak valid.'])->withInput();
    }

    // Reset password
    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->setRememberToken(Str::random(60));
    $user->save();

    // Hapus token reset agar tidak dipakai ulang
    DB::table('password_resets')->where('email', $request->email)->delete();

    // Hapus token login sanctum (logout semua device)
    try {
        $user->tokens()->delete();
    } catch (\Exception $e) {
        \Log::warning('Error deleting tokens after reset', ['err' => $e->getMessage()]);
    }

    return redirect()->route('customer.login')
        ->with('success', 'Password berhasil diubah. Silakan login kembali.');
}
// Tambahkan method performRegistration
public function performRegistration(array $data)
{
    \Log::info('AuthController::performRegistration - Starting', ['data' => array_keys($data)]);

    try {
        // Validasi data
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            \Log::warning('AuthController::performRegistration - Validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // Buat user baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'active', // Default status aktif
            'email_verified_at' => now(), // Verifikasi email otomatis
        ]);

        // Berikan role 'customer'
        $user->assignRole('customer');

        \Log::info('AuthController::performRegistration - User created successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return [
            'success' => true,
            'user' => $user,
            'message' => 'Registrasi berhasil'
        ];

    } catch (\Exception $e) {
        \Log::error('AuthController::performRegistration - Exception', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return [
            'success' => false,
            'errors' => ['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]
        ];
    }
}
// API REGISTER METHOD
public function register(Request $request)
{
    \Log::info('AuthController::register API - Starting', $request->all());

    try {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->performRegistration($request->all());

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        // Generate token untuk response API
        $user = User::find($result['user']->id);
        $token = $user->createToken('SmartShuttle-API')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user->load('roles'),
            'token' => $token,
            'role' => $user->getRoleNames()->first(),
            'message' => 'Registrasi berhasil'
        ], 201);

    } catch (\Exception $e) {
        \Log::error('AuthController::register API - Exception', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem'
        ], 500);
    }
}

// API LOGIN METHOD
public function login(Request $request)
{
    \Log::info('AuthController::login API - Starting', $request->all());

    try {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->performLogin($request->all());

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => $result['user'],
            'token' => $result['token'],
            'role' => $result['role']
        ]);

    } catch (\Exception $e) {
        \Log::error('AuthController::login API - Exception', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem'
        ], 500);
    }
}

// LOGOUT METHOD
public function logout(Request $request)
{
    try {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ], 401);

    } catch (\Exception $e) {
        \Log::error('AuthController::logout - Exception', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem'
        ], 500);
    }
}

// LOGOUT ALL METHOD (logout dari semua device)
public function logoutAll(Request $request)
{
    try {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout dari semua device berhasil'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ], 401);

    } catch (\Exception $e) {
        \Log::error('AuthController::logoutAll - Exception', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem'
        ], 500);
    }
}

// GET SESSIONS METHOD
public function getSessions(Request $request)
{
    try {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 401);
        }

        $tokens = $user->tokens()->get()->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
                'expires_at' => $token->expires_at,
                'is_current' => $token->id === request()->user()->currentAccessToken()->id
            ];
        });

        return response()->json([
            'success' => true,
            'sessions' => $tokens
        ]);

    } catch (\Exception $e) {
        \Log::error('AuthController::getSessions - Exception', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem'
        ], 500);
    }
}

// REVOKE SESSION METHOD
public function revokeSession(Request $request, $tokenId)
{
    try {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 401);
        }

        $token = $user->tokens()->where('id', $tokenId)->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan'
            ], 404);
        }

        $token->delete();

        return response()->json([
            'success' => true,
            'message' => 'Session berhasil dihapus'
        ]);

    } catch (\Exception $e) {
        \Log::error('AuthController::revokeSession - Exception', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem'
        ], 500);
    }
}

// CHANGE PASSWORD METHOD
public function changePassword(Request $request)
{
    try {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah'
            ], 422);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Optional: logout dari semua device setelah ganti password
        // $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah'
        ]);

    } catch (\Exception $e) {
        \Log::error('AuthController::changePassword - Exception', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem'
        ], 500);
    }
}

// CREATE USER WITH ROLE (untuk admin)
public function createUserWithRole(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Berikan role
        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'user' => $user->load('roles'),
            'message' => 'User berhasil dibuat dengan role ' . $request->role
        ], 201);

    } catch (\Exception $e) {
        \Log::error('AuthController::createUserWithRole - Exception', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem'
        ], 500);
    }
}
}
