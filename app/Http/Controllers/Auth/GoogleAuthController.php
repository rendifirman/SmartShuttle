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
                    ]);
                } else {
                    // Buat user baru dengan data dari Google
                    $user = User::create([
                        'name' => $googleUser->getName(),
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
                    ]);
                }
            }

            // Login user (langsung masuk ke beranda)
            Auth::login($user, true);

            // Set session untuk customer
            session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'membership_status' => $user->membership_status,
                'membership_level' => $user->membership_level,
            ]);

            // Redirect ke beranda (langsung masuk)
            return redirect()->route('customer.beranda')
                ->with('success', 'Login dengan Google berhasil! Selamat datang di Smart Shuttle.');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('customer.login')
                ->withErrors('Login dengan Google gagal. Silakan coba lagi.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
