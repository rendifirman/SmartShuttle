<?php
// app/Http\Controllers\Auth\GoogleAuthController.php
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
                    // Buat user baru
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'password' => Hash::make(Str::random(16)), // Random password
                        'google_id' => $googleUser->getId(),
                        'provider' => 'google',
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(), // Auto verify karena dari Google
                        'username' => $this->generateUsername($googleUser->getName()),
                    ]);
                }
            }

            // Login user
            Auth::login($user, true);

            // Set session untuk customer
            session(['user' => $user]);

            // Redirect ke dashboard berdasarkan role
            if ($user->hasRole('admin_pusat')) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.beranda');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('customer.login')
                ->withErrors('Login dengan Google gagal. Silakan coba lagi.');
        }
    }

    private function generateUsername($name)
    {
        $username = Str::slug($name, '');
        $username = preg_replace('/[^a-zA-Z0-9]/', '', $username);

        // Jika username terlalu pendek
        if (strlen($username) < 3) {
            $username .= Str::random(5);
        }

        // Cek apakah username sudah ada
        $count = User::where('username', $username)->count();
        if ($count > 0) {
            $username .= '_' . Str::random(4);
        }

        return $username;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
