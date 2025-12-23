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
use Spatie\Permission\Models\Role;

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

    // PERBAIKAN: Method untuk handle registrasi Google via API
    public function googleAuth(Request $request)
    {
        \Log::info('Google Auth API Request:', $request->all());

        try {
            $validator = Validator::make($request->all(), [
                'google_id' => 'required|string',
                'email' => 'required|email',
                'name' => 'required|string',
                'avatar' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cek apakah user sudah ada berdasarkan Google ID
            $user = User::where('google_id', $request->google_id)->first();

            if (!$user) {
                // Cek apakah email sudah terdaftar (untuk menghindari duplikat)
                $user = User::where('email', $request->email)->first();

                if ($user) {
                    // User sudah ada dengan email ini, update dengan Google ID
                    $user->update([
                        'google_id' => $request->google_id,
                        'provider' => 'google',
                        'avatar' => $request->avatar,
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);

                    // Assign role customer jika belum punya role
                    if (!$user->hasAnyRole(['customer', 'admin', 'super_admin'])) {
                        $user->assignRole('customer');
                    }
                } else {
                    // Buat user baru dengan data dari Google
                    $user = User::create([
                        'name' => $request->name,
                        'username' => $this->generateUsername($request->email),
                        'email' => $request->email,
                        'password' => Hash::make(Str::random(16)),
                        'google_id' => $request->google_id,
                        'provider' => 'google',
                        'avatar' => $request->avatar,
                        'email_verified_at' => now(),
                        'membership_status' => 'non_member',
                        'membership_level' => 'Bronze',
                        'member_point' => 0,
                        'loyalty_point' => 0,
                        'status' => 'active',
                    ]);

                    // Berikan role 'customer'
                    $user->assignRole('customer');
                }
            }

            // Cek status user
            if ($user->status === 'inactive') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.'
                ], 403);
            }

            // Login user dan buat token
            Auth::login($user, true);
            $token = $user->createToken('SmartShuttle-Google-API')->plainTextToken;

            return response()->json([
                'success' => true,
                'user' => $user->load('roles'),
                'token' => $token,
                'role' => $user->getRoleNames()->first(),
                'message' => 'Login dengan Google berhasil'
            ]);

        } catch (\Exception $e) {
            \Log::error('Google Auth API Error:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateUsername($email)
    {
        $username = explode('@', $email)[0];
        $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', $username);

        // Cek jika username sudah ada
        $counter = 1;
        $finalUsername = $baseUsername;

        while (User::where('username', $finalUsername)->exists()) {
            $finalUsername = $baseUsername . $counter;
            $counter++;
        }

        return $finalUsername;
    }

    // ... (sisanya sama seperti sebelumnya)

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;
        \Log::info('Password reset requested', ['email' => $email]);

        $token = $this->generateShortToken(6);
        $hashed = Hash::make($token);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $hashed, 'created_at' => now()]
        );

        $resetUrl = route('password.reset', ['token' => $token]) . '?email=' . urlencode($email);

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
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $token .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $token;
    }

    public function showTokenForm()
    {
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

        $created = Carbon::parse($record->created_at);
        if ($created->diffInMinutes(now()) > 15) {
            DB::table('password_resets')->where('email', $email)->delete();
            return back()->withErrors(['token' => 'Token sudah kadaluwarsa. Silakan minta token baru.']);
        }

        if (!Hash::check($tokenInput, $record->token)) {
            return back()->withErrors(['token' => 'Token tidak cocok. Periksa kembali token yang Anda terima.']);
        }

        session(['email' => $email, 'token' => $tokenInput]);

        \Log::info('Token verified successfully', ['email' => $email]);

        return redirect()->route('password.reset')
            ->with('success', 'Token berhasil diverifikasi. Silakan buat password baru.');
    }

    public function showResetForm(Request $request)
    {
        $token = $request->token ?: session('token');
        $email = $request->email ?: session('email');

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

        $record = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Token tidak valid atau sudah dipakai.'])->withInput();
        }

        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'Token tidak valid.'])->withInput();
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        try {
            $user->tokens()->delete();
        } catch (\Exception $e) {
            \Log::warning('Error deleting tokens after reset', ['err' => $e->getMessage()]);
        }

        return redirect()->route('customer.login')
            ->with('success', 'Password berhasil diubah. Silakan login kembali.');
    }

    public function performRegistration(array $data)
    {
        \Log::info('AuthController::performRegistration - Starting', ['data' => array_keys($data)]);

        try {
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

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'status' => 'active',
                'email_verified_at' => now(),
                'membership_status' => 'non_member',
                'membership_level' => 'Bronze',
                'member_point' => 0,
                'loyalty_point' => 0,
            ]);

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

            // Untuk API registrasi, TIDAK login otomatis
            return response()->json([
                'success' => true,
                'user' => $result['user']->load('roles'),
                'message' => 'Registrasi berhasil. Silakan login dengan akun Anda.'
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

    // ... (metode lainnya tetap sama)

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

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini salah'
                ], 422);
            }

            $user->password = Hash::make($request->password);
            $user->save();

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

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

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
