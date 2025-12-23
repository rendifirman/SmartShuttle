<?php
// app/Http/Controllers/Auth/GoogleAuthController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            \Log::info('Google OAuth Response:', [
                'id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName()
            ]);

            // Cek apakah user sudah ada berdasarkan Google ID
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Cek apakah email sudah terdaftar (untuk menghindari duplikat)
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // User sudah ada dengan email ini, update dengan Google ID
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'provider' => 'google',
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);

                    // Assign role customer jika belum punya role
                    if (!$user->hasAnyRole(['customer', 'admin', 'super_admin'])) {
                        $user->assignRole('customer');
                    }
                } else {
                    // Buat user baru dengan data dari Google
                    DB::beginTransaction();

                    try {
                        $user = User::create([
                            'name' => $googleUser->getName(),
                            'username' => $this->generateUsername($googleUser->getEmail()),
                            'email' => $googleUser->getEmail(),
                            'password' => Hash::make(Str::random(16)), // Random password
                            'google_id' => $googleUser->getId(),
                            'provider' => 'google',
                            'avatar' => $googleUser->getAvatar(),
                            'email_verified_at' => now(), // Auto verify karena dari Google
                            'membership_status' => 'non_member',
                            'membership_level' => 'Bronze',
                            'member_point' => 0,
                            'loyalty_point' => 0,
                            'status' => 'active', // Set status aktif default
                        ]);

                        // Berikan role 'customer'
                        $user->assignRole('customer');

                        DB::commit();

                        \Log::info('New user created via Google OAuth:', [
                            'user_id' => $user->id,
                            'email' => $user->email
                        ]);

                    } catch (\Exception $e) {
                        DB::rollBack();
                        \Log::error('Failed to create user via Google:', ['error' => $e->getMessage()]);
                        throw $e;
                    }
                }
            }

            // Cek status user
            if ($user->status === 'inactive') {
                \Log::warning('Google login attempt for inactive account', ['email' => $googleUser->getEmail()]);
                return redirect()->route('customer.login')
                    ->withErrors('Akun Anda dinonaktifkan. Silakan hubungi administrator.');
            }

            // Login user (langsung masuk ke beranda)
            Auth::login($user, true);

            // Set session untuk customer
            session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'membership_status' => $user->membership_status,
                'membership_level' => $user->membership_level,
            ]);

            \Log::info('Google login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'provider' => 'google'
            ]);

            // Redirect ke beranda (langsung masuk)
            return redirect()->route('customer.beranda')
                ->with('success', 'Login dengan Google berhasil! Selamat datang di Smart Shuttle.');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('customer.login')
                ->withErrors('Login dengan Google gagal. Silakan coba lagi.');
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
