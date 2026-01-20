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

        if (Auth::guard('driver')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('driver.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
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
