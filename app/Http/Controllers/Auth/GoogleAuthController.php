<?php
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
    ->stateless()
    ->with(['prompt' => 'select_account'])
    ->redirect();

    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            // Decode query string to handle URL encoded parameters
            $code = $request->input('code');
            $state = $request->input('state');

            if (!$code) {
                throw new \Exception('Authorization code not found');
            }

           $googleUser = Socialite::driver('google')->stateless()->user();


            \Log::info('Google OAuth Response:', [
                'id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName()
            ]);

            // Cek apakah user sudah ada berdasarkan Google ID
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Cek apakah email sudah terdaftar (untuk menghindari duplikat)
                $userEmail = filter_var($googleUser->getEmail(), FILTER_VALIDATE_EMAIL);
                if (!$userEmail) {
                    throw new \Exception('Invalid email format from Google');
                }

                $user = User::where('email', $userEmail)->first();

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
                            'username' => $this->generateUsername($userEmail),
                            'email' => $userEmail,
                            'password' => Hash::make(Str::random(16)),
                            'google_id' => $googleUser->getId(),
                            'provider' => 'google',
                            'avatar' => $googleUser->getAvatar(),
                            'email_verified_at' => now(),
                            'membership_status' => 'non_member',
                            'membership_level' => 'Bronze',
                            'member_point' => 0,
                            'loyalty_point' => 0,
                            'status' => 'active',
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

            // Login user using Auth::guard('web') dan session storage
            Auth::guard('web')->login($user, true);

            // Store user data di session seperti CustomerController
            session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'membership_status' => $user->membership_status,
                'membership_level' => $user->membership_level,
            ]);

            // Regenerate session to persist authentication immediately
            try {
                session()->regenerate();
                session()->save();
            } catch (\Exception $e) {
                \Log::warning('Session regeneration/save failed after Google login', ['error' => $e->getMessage()]);
            }

            // Debug logs to help diagnose session/auth persistence issues
            try {
                \Log::info('Post-GoogleLogin debug', [
                    'auth_check' => Auth::check(),
                    'auth_user_id' => Auth::id(),
                    'session_id' => session()->getId(),
                    'session_user' => session()->get('user')
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to log post-login debug info', ['error' => $e->getMessage()]);
            }

            \Log::info('Google login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'provider' => 'google'
            ]);

            // Redirect ke beranda (langsung masuk)
            return redirect()->route('customer.beranda');

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
