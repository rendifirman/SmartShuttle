<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\RuteJadwal;
use App\Models\AppSetting;

class RuteJadwalController extends Controller
{
    public function __construct()
    {
        // Middleware handled at route level (auth:admin)
    }

    public function index()
    {
        $jadwals = RuteJadwal::orderBy('tanggal')->orderBy('jam_berangkat')->paginate(30);
        $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
        return view('admin.rute_jadwal.index', compact('jadwals', 'mode'));
    }

    public function create()
    {
        // Get drivers for the form (in case direct_assign mode is enabled)
        // Assuming drivers are User::where('role', 'driver') or similar
        $drivers = \App\Models\User::where('status', 'active')->get(); // Adjust query as needed
        return view('admin.rute_jadwal.form', compact('drivers'));
    }

    public function store(Request $request)
    {
        $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');

        $rules = [
            'id_rute' => 'required|integer',
            'id_shuttle' => 'required|integer',
            'tanggal' => 'required|date',
            'jam_berangkat' => 'required',
        ];

        if ($mode === 'direct_assign') {
            $rules['id_driver'] = 'required|integer';
        }

        $data = $request->validate($rules);

        $jadwal = new RuteJadwal($data);
        if ($mode === 'direct_assign') {
            $jadwal->status = RuteJadwal::STATUS_ACTIVE;
        } else {
            $jadwal->status = RuteJadwal::STATUS_OPEN;
            $jadwal->id_driver = null;
        }

        $jadwal->save();

        return redirect()->route('admin.rute_jadwal.index')->with('success', 'Jadwal dibuat.');
    }

    /**
     * Update global jadwal flow mode from admin listing page.
     */
    public function updateConfig(Request $request)
    {
        $data = $request->validate([
            'jadwal_flow_mode' => 'required|in:driver_confirmation,direct_assign',
        ]);

        AppSetting::updateOrCreate(['key' => 'jadwal_flow_mode'], ['value' => $data['jadwal_flow_mode']]);
        Cache::forget('app_setting:jadwal_flow_mode');

        return redirect()->back()->with('success', 'Pengaturan jadwal diperbarui.');
    }
}
