<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\RuteJadwal;

class RuteJadwalController extends Controller
{
    /**
     * Display available schedules for customers (MODE-AWARE)
     * 
     * This method is designed to work correctly in BOTH schedule flow modes:
     * 
     * 🔄 DRIVER_CONFIRMATION MODE:
     *    - Admin creates schedules with status='open' (not yet assigned)
     *    - Drivers see open schedules and can claim them
     *    - When driver claims → status changes to 'active', id_driver set
     *    - Customers ONLY see claimed schedules (status='active')
     *    - Result: Customers never see unassigned schedules
     * 
     * 🎯 DIRECT_ASSIGN MODE:
     *    - Admin creates schedules with status='active' + driver selected
     *    - Drivers can only see their own assigned schedules (read-only)
     *    - No claim mechanism (take() action returns 403)
     *    - Customers see all admin-assigned active schedules immediately
     *    - Result: All schedules assigned upfront, no waiting period
     * 
     * ✨ KEY: Both modes query WHERE status='active', works because:
     *    - Confirmation: Only shows after driver claims
     *    - Direct: Admin creates with status='active' immediately
     * 
     * The flow mode is configurable and can be switched at any time via:
     *    Admin → Admin Dashboard → Jadwal List → Config Button
     */
    public function index()
    {
        $mode = appSetting('jadwal_flow_mode', 'driver_confirmation');
        
        // Query active schedules - semantics differ by mode but SQL is identical
        $jadwals = RuteJadwal::where('status', RuteJadwal::STATUS_ACTIVE)
            ->orderBy('tanggal')
            ->paginate(20);
            
        return view('customer.rute_jadwal.index', compact('jadwals', 'mode'));
    }
}
