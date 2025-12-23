<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\Branch;
use Illuminate\Support\Str;

class OutletController extends Controller
{
    public function index()
    {
        // Ambil semua data outlet dengan relasi branch
        $outlets = Outlet::with('branch')
            ->where('status', 'aktif')
            ->orderBy('nama_outlet')
            ->get();

        // Ambil data cabang untuk filter
        $branches = Branch::where('status', 'aktif')
            ->orderBy('kota')
            ->get();

        // Ambil data kota unik dari branches untuk filter
        $kotaList = Branch::select('kota')
            ->distinct()
            ->where('status', 'aktif')
            ->orderBy('kota')
            ->pluck('kota')
            ->toArray();

        return view('customer.outlet', compact('outlets', 'branches', 'kotaList'));
    }

    public function filter(Request $request)
    {
        $kota = $request->input('kota');
        $branchId = $request->input('branch_id');
        $branchName = $request->input('branch_name');

        $query = Outlet::with('branch')
            ->where('status', 'aktif');

        // Filter berdasarkan kota (menggunakan LIKE untuk partial match)
        if ($kota) {
            $query->whereHas('branch', function($q) use ($kota) {
                $q->where('kota', 'like', '%' . $kota . '%');
            });
        }

        // Filter berdasarkan branch_id (jika ada)
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        // Jika hanya ada branch_name tanpa branch_id (user mengetik manual)
        elseif ($branchName && !$branchId) {
            // Cari cabang berdasarkan nama (partial match)
            $query->whereHas('branch', function($q) use ($branchName) {
                $q->where('nama_cabang', 'like', '%' . $branchName . '%')
                  ->orWhere('kota', 'like', '%' . $branchName . '%')
                  ->orWhereRaw("CONCAT(nama_cabang, ' - ', kota) LIKE ?", ['%' . $branchName . '%']);
            });
        }

        $outlets = $query->orderBy('nama_outlet')->get();
        $branches = Branch::where('status', 'aktif')->orderBy('kota')->get();
        $kotaList = Branch::select('kota')
            ->distinct()
            ->where('status', 'aktif')
            ->orderBy('kota')
            ->pluck('kota')
            ->toArray();

        return view('customer.outlet', compact('outlets', 'branches', 'kotaList', 'kota', 'branchId', 'branchName'));
    }
}
