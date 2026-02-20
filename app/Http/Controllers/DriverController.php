<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\DriverSchedule;

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
        $driver = Auth::guard('driver')->user();
        return view('driver.dashboard', compact('driver'));
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
        $driver = Auth::guard('driver')->user();
        
        // Ambil semua jadwal driver yang login
        $schedules = DriverSchedule::with('rute.bus')
            ->where('driver_id', $driver->id)
            ->orderBy('tanggal_berangkat', 'desc')
            ->orderBy('jam_berangkat', 'desc')
            ->get();

        return view('driver.jadwal', compact('schedules', 'driver'));
    }

    /**
     * Show driver reports
     */
    public function laporan()
    {
        $driver = Auth::guard('driver')->user();
        return view('driver.laporan', compact('driver'));
    }

    /**
     * Show driver trips
     */
    public function perjalanan()
    {
        $driver = Auth::guard('driver')->user();
        return view('driver.perjalanan', compact('driver'));
    }

    /**
     * Show driver profile
     */
    public function profile()
    {
        // Ambil data driver yang sedang login
        $driver = Auth::guard('driver')->user();
        
        return view('driver.profile', compact('driver'));
    }

    /**
     * Show driver profile edit form
     */
    public function profileEdit()
    {
        // Ambil data driver yang sedang login
        $driver = Auth::guard('driver')->user();
        
        return view('driver.profile-edit', compact('driver'));
    }

    /**
     * Update driver profile
     */
    public function updateProfile(Request $request)
    {
        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::guard('driver')->id(),
            'phone' => 'required|string|max:20',
            'nik' => 'required|string|size:16',
            'join_date' => 'required|date',
            'sim_number' => 'required|string|max:50',
            'sim_expiry_date' => 'required|date|after:today',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'ktp_file' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'sim_file' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'sim_expiry_date.after' => 'Masa berlaku SIM harus setelah hari ini',
            'nik.size' => 'NIK harus 16 digit',
            'phone.required' => 'Nomor telepon wajib diisi',
            'sim_number.required' => 'Nomor SIM wajib diisi',
        ]);

        // Ambil driver yang sedang login
        $driver = Auth::guard('driver')->user();

        // Update data di tabel users
        $driver->name = $request->name;
        $driver->email = $request->email;
        $driver->phone = $request->phone;
        $driver->nik = $request->nik;
        $driver->join_date = $request->join_date;
        $driver->sim_number = $request->sim_number;
        $driver->sim_expiry_date = $request->sim_expiry_date;
        
        // Handle upload avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($driver->avatar && Storage::disk('public')->exists($driver->avatar)) {
                Storage::disk('public')->delete($driver->avatar);
            }
            
            // Simpan avatar baru
            $avatarPath = $request->file('avatar')->store('driver-avatars', 'public');
            $driver->avatar = $avatarPath;
        }

        // Handle upload KTP
        if ($request->hasFile('ktp_file')) {
            // Hapus file lama jika ada
            if ($driver->ktp_file && Storage::disk('public')->exists($driver->ktp_file)) {
                Storage::disk('public')->delete($driver->ktp_file);
            }
            
            // Simpan file baru
            $ktpPath = $this->uploadFile($request->file('ktp_file'), 'driver-ktp', $driver->id);
            $driver->ktp_file = $ktpPath;
        }
        
        // Handle penghapusan KTP
        if ($request->remove_ktp == '1') {
            if ($driver->ktp_file && Storage::disk('public')->exists($driver->ktp_file)) {
                Storage::disk('public')->delete($driver->ktp_file);
            }
            $driver->ktp_file = null;
        }

        // Handle upload SIM
        if ($request->hasFile('sim_file')) {
            // Hapus file lama jika ada
            if ($driver->sim_file && Storage::disk('public')->exists($driver->sim_file)) {
                Storage::disk('public')->delete($driver->sim_file);
            }
            
            // Simpan file baru
            $simPath = $this->uploadFile($request->file('sim_file'), 'driver-sim', $driver->id);
            $driver->sim_file = $simPath;
        }
        
        // Handle penghapusan SIM
        if ($request->remove_sim == '1') {
            if ($driver->sim_file && Storage::disk('public')->exists($driver->sim_file)) {
                Storage::disk('public')->delete($driver->sim_file);
            }
            $driver->sim_file = null;
        }

        // Simpan semua perubahan
        $driver->save();

        return redirect()->route('driver.profile')
            ->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Helper function untuk upload file
     */
    private function uploadFile($file, $folder, $userId)
    {
        $filename = time() . '_' . $userId . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $filename, 'public');
    }

    /**
     * Show driver settings page
     */
    public function pengaturan()
    {
        $driver = Auth::guard('driver')->user();
        return view('driver.pengaturan', compact('driver'));
    }

    /**
     * Show driver help/FAQ page
     */
    public function bantuan()
    {
        $driver = Auth::guard('driver')->user();
        return view('driver.bantuan', compact('driver'));
    }
}