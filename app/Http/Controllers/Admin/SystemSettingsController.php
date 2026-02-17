<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\AppSetting;

class SystemSettingsController extends Controller
{
    public function __construct()
    {
        // Middleware handled at route level (auth:admin, CheckAdminRole)
    }

    public function index()
    {
        $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
        return view('admin.system_settings.index', compact('mode'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'jadwal_flow_mode' => 'required|in:driver_confirmation,direct_assign',
        ]);

        AppSetting::updateOrCreate(['key' => 'jadwal_flow_mode'], ['value' => $data['jadwal_flow_mode']]);
        Cache::forget('app_setting:jadwal_flow_mode');

        return redirect()->route('admin.system_settings.schedule_flow')->with('success', 'Pengaturan jadwal diperbarui.');
    }
}
