<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DriverController extends Controller
{
    /**
     * Show driver login form
     */
    public function showLogin()
    {
        return view('driver.login');
    }

    /**
     * Handle driver login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        \Log::info('Driver login attempt', ['email' => $request->email]);

        if (Auth::guard('driver')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Check if user has driver role
            $user = Auth::guard('driver')->user();

            \Log::info('Driver login - user found', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'status' => $user->status,
            ]);

            // Check user status
            if ($user->status !== 'active') {
                Auth::guard('driver')->logout();
                \Log::warning('Driver login failed - inactive account', ['email' => $request->email]);
                return back()->withErrors(['email' => 'Akun Anda tidak aktif.']);
            }

            // Check if user has driver role
            if (!$user->hasRole('driver')) {
                Auth::guard('driver')->logout();
                \Log::warning('Driver login failed - no driver role', [
                    'email' => $request->email,
                    'roles' => $user->getRoleNames()->toArray()
                ]);
                return back()->withErrors(['email' => 'Anda tidak memiliki akses driver.']);
            }

            \Log::info('Driver login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
            ]);

            return redirect()->intended(route('driver.dashboard'));
        }

        \Log::warning('Driver login failed - invalid credentials', ['email' => $request->email]);

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Show driver dashboard
     */
    public function dashboard()
    {
        return view('driver.dashboard');
    }

    /**
     * Handle driver logout
     */
    public function logout(Request $request)
    {
        Auth::guard('driver')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }

    /**
     * Show driver schedule
     */
    public function jadwal()
    {
        return view('driver.jadwal');
    }

    /**
     * Show driver reports
     */
    public function laporan()
    {
        return view('driver.laporan');
    }

    /**
     * Show driver trips
     */
    public function perjalanan()
    {
        return view('driver.perjalanan');
    }

    /**
     * Show driver profile
     */
    public function profile()
    {
        return view('driver.profile');
    }

    /**
     * Show driver profile edit form
     */
    public function profileEdit()
    {
        return view('driver.profile-edit');
    }

    /**
     * Show driver settings page
     */
    public function pengaturan()
    {
        return view('driver.pengaturan');
    }

    /**
     * Show driver help/FAQ page
     */
    public function bantuan()
    {
        return view('driver.bantuan');
    }
}
