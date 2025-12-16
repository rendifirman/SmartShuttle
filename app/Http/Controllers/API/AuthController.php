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
}