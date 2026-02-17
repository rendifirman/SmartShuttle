<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RuteJadwal;

class RuteJadwalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');

        if ($mode === 'driver_confirmation') {
            $open = RuteJadwal::where('status', RuteJadwal::STATUS_OPEN)->orderBy('tanggal')->get();
            return view('driver.rute_jadwal.index', ['mode' => $mode, 'open' => $open]);
        }

        // direct_assign: show assigned schedules to current driver
        $assigned = RuteJadwal::where('id_driver', auth()->id())->orderBy('tanggal')->get();
        return view('driver.rute_jadwal.index', ['mode' => $mode, 'assigned' => $assigned]);
    }

    public function take($id)
    {
        $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
        if ($mode !== 'driver_confirmation') {
            abort(403);
        }

        $jadwal = RuteJadwal::findOrFail($id);
        if ($jadwal->status !== RuteJadwal::STATUS_OPEN) {
            return redirect()->back()->with('error', 'Jadwal tidak tersedia.');
        }

        $jadwal->id_driver = auth()->id();
        $jadwal->status = RuteJadwal::STATUS_ACTIVE;
        $jadwal->save();

        return redirect()->back()->with('success', 'Jadwal berhasil diambil.');
    }
}
