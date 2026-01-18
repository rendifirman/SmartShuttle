<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function dashboard()
    {
        return view('driver.dashboard-driver');
    }

    /**
     * Show driver login form
     */
    public function showLogin()
    {
        return view('driver.login');
    }

    /**
     * Process driver login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('driver')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Check if user has driver role
            $user = Auth::guard('driver')->user();
            if (!$user->hasRole('driver')) {
                Auth::guard('driver')->logout();
                return back()->withErrors(['email' => 'Anda tidak memiliki akses driver.']);
            }

            return redirect()->intended(route('driver.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->onlyInput('email');
    }

    /**
     * Proses logout driver
     */
    public function logout(Request $request)
    {
        try {
            Auth::guard('driver')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('customer.beranda');

        } catch (\Exception $e) {
            return redirect()->route('driver.dashboard');
        }
    }
}
