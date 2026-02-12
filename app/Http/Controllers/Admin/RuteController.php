<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rute;
use App\Models\MLayanan;
use App\Models\MasterTarif;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RuteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Rute::query();

        // Apply filters
        if ($request->filled('kota_asal')) {
            $query->where('kota_asal', 'like', '%' . $request->kota_asal . '%');
        }

        if ($request->filled('kota_tujuan')) {
            $query->where('kota_tujuan', 'like', '%' . $request->kota_tujuan . '%');
        }

        if ($request->filled('layanan_id')) {
            $query->where('layanan_id', $request->layanan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_rute', 'like', '%' . $search . '%')
                  ->orWhere('nama_rute', 'like', '%' . $search . '%')
                  ->orWhere('kota_asal', 'like', '%' . $search . '%')
                  ->orWhere('kota_tujuan', 'like', '%' . $search . '%');
            });
        }

        $rutes = $query->paginate(15);

        // Get summary data
        $totalRute = Rute::count();
        $activeRute = Rute::where('status', 'aktif')->count();
        $inactiveRute = Rute::where('status', 'nonaktif')->count();

        // Get filter options
        $kotaAsalList = Rute::distinct()->pluck('kota_asal')->filter()->sort()->values();
        $kotaTujuanList = Rute::distinct()->pluck('kota_tujuan')->filter()->sort()->values();
        $layananList = MLayanan::where('status_aktif', true)->get();

        return view('admin.rute', [
            'title' => 'Data Rute',
            'pageTitle' => 'Data Rute',
            'rutes' => $rutes,
            'totalRute' => $totalRute,
            'activeRute' => $activeRute,
            'inactiveRute' => $inactiveRute,
            'kotaAsalList' => $kotaAsalList,
            'kotaTujuanList' => $kotaTujuanList,
            'layananList' => $layananList
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layanans = MLayanan::where('status_aktif', true)->get();
        $tarifs = MasterTarif::where('status', 'aktif')->get();
        $branches = Branch::with('outlets')->where('status', 'aktif')->get();

        return view('admin.rute-create', [
            'title' => 'Tambah Rute',
            'pageTitle' => 'Tambah Rute',
            'layanans' => $layanans,
            'tarifs' => $tarifs,
            'branches' => $branches
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'layanan_id' => 'required|exists:m_layanan,id_layanan',
            'cabang_asal_id' => 'required|exists:branches,id',
            'cabang_tujuan_id' => 'required|exists:branches,id',
            'nama_rute' => 'required|string|max:255',
            'durasi' => 'required|string|max:255',
            'jarak' => 'required|numeric|min:0',
            'harga_dasar' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'master_tarif_ids' => 'required|array|min:1',
            'master_tarif_ids.*' => 'exists:master_tarif,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Auto-fill kota_asal dan kota_tujuan dari cabang
        $cabangAsal = Branch::with('outlets')->find($data['cabang_asal_id']);
        $cabangTujuan = Branch::with('outlets')->find($data['cabang_tujuan_id']);

        if ($cabangAsal) {
            $data['kota_asal'] = $cabangAsal->kota;
        }
        if ($cabangTujuan) {
            $data['kota_tujuan'] = $cabangTujuan->kota;
        }

        // Auto-fill rute_pemberhentian dengan outlets dari cabang asal dan tujuan
        $rutePemberhentian = [];
        if ($cabangAsal && $cabangAsal->outlets) {
            $outletAsalList = $cabangAsal->outlets->where('status', 'aktif')->pluck('nama_outlet')->toArray();
            if (!empty($outletAsalList)) {
                $rutePemberhentian[] = [
                    'kota' => $cabangAsal->kota,
                    'cabang' => $cabangAsal->nama_cabang,
                    'outlets' => $outletAsalList,
                    'durasi_singgah' => 0,
                    'jenis' => 'asal'
                ];
            }
        }
        if ($cabangTujuan && $cabangTujuan->outlets) {
            $outletTujuanList = $cabangTujuan->outlets->where('status', 'aktif')->pluck('nama_outlet')->toArray();
            if (!empty($outletTujuanList)) {
                $rutePemberhentian[] = [
                    'kota' => $cabangTujuan->kota,
                    'cabang' => $cabangTujuan->nama_cabang,
                    'outlets' => $outletTujuanList,
                    'durasi_singgah' => 0,
                    'jenis' => 'tujuan'
                ];
            }
        }

        if (!empty($rutePemberhentian)) {
            $data['rute_pemberhentian'] = json_encode($rutePemberhentian, JSON_UNESCAPED_UNICODE);
        }

        // Bersihkan format harga_dasar (hapus titik/koma)
        $data['harga_dasar'] = str_replace(['.', ','], '', $data['harga_dasar']);

        $data['kode_rute'] = 'RUT-' . Str::upper(Str::random(6));
        $data['created_by'] = auth()->user()->id;

        // Prevent duplicate route between same kota_asal and kota_tujuan
        if (!empty($data['kota_asal']) && !empty($data['kota_tujuan'])) {
            $exists = Rute::where('kota_asal', $data['kota_asal'])
                ->where('kota_tujuan', $data['kota_tujuan'])
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Rute antara ' . $data['kota_asal'] . ' dan ' . $data['kota_tujuan'] . ' sudah ada')
                    ->withInput();
            }
        }

        $rute = Rute::create($data);

        // Simpan relasi master tarif
        if (!empty($request->master_tarif_ids)) {
            $rute->masterTarifs()->sync($request->master_tarif_ids);
        }

        return redirect()->route('admin.rute.index')
            ->with('success', 'Rute berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rute = Rute::with(['layanan', 'masterTarifs'])->findOrFail($id);

        return view('admin.rute-detail', [
            'title' => 'Detail Rute',
            'pageTitle' => 'Detail Rute',
            'rute' => $rute
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $rute = Rute::findOrFail($id);
        $layanans = MLayanan::where('status_aktif', true)->get();
        $tarifs = MasterTarif::where('status', 'aktif')->get();
        $branches = Branch::with('outlets')->where('status', 'aktif')->get();

        return view('admin.rute-edit', [
            'title' => 'Edit Rute',
            'pageTitle' => 'Edit Rute',
            'rute' => $rute,
            'layanans' => $layanans,
            'tarifs' => $tarifs,
            'branches' => $branches
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rute = Rute::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'layanan_id' => 'required|exists:m_layanan,id_layanan',
            'cabang_asal_id' => 'required|exists:branches,id',
            'cabang_tujuan_id' => 'required|exists:branches,id',
            'nama_rute' => 'required|string|max:255',
            'durasi' => 'required|string|max:255',
            'jarak' => 'required|numeric|min:0',
            'harga_dasar' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'master_tarif_ids' => 'required|array|min:1',
            'master_tarif_ids.*' => 'exists:master_tarif,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Auto-fill kota_asal dan kota_tujuan dari cabang
        $cabangAsal = Branch::with('outlets')->find($data['cabang_asal_id']);
        $cabangTujuan = Branch::with('outlets')->find($data['cabang_tujuan_id']);

        if ($cabangAsal) {
            $data['kota_asal'] = $cabangAsal->kota;
        }
        if ($cabangTujuan) {
            $data['kota_tujuan'] = $cabangTujuan->kota;
        }

        // Auto-fill rute_pemberhentian dengan outlets dari cabang asal dan tujuan
        $rutePemberhentian = [];
        if ($cabangAsal && $cabangAsal->outlets) {
            $outletAsalList = $cabangAsal->outlets->where('status', 'aktif')->pluck('nama_outlet')->toArray();
            if (!empty($outletAsalList)) {
                $rutePemberhentian[] = [
                    'kota' => $cabangAsal->kota,
                    'cabang' => $cabangAsal->nama_cabang,
                    'outlets' => $outletAsalList,
                    'durasi_singgah' => 0,
                    'jenis' => 'asal'
                ];
            }
        }
        if ($cabangTujuan && $cabangTujuan->outlets) {
            $outletTujuanList = $cabangTujuan->outlets->where('status', 'aktif')->pluck('nama_outlet')->toArray();
            if (!empty($outletTujuanList)) {
                $rutePemberhentian[] = [
                    'kota' => $cabangTujuan->kota,
                    'cabang' => $cabangTujuan->nama_cabang,
                    'outlets' => $outletTujuanList,
                    'durasi_singgah' => 0,
                    'jenis' => 'tujuan'
                ];
            }
        }

        if (!empty($rutePemberhentian)) {
            $data['rute_pemberhentian'] = json_encode($rutePemberhentian, JSON_UNESCAPED_UNICODE);
        }

        // Bersihkan format harga_dasar (hapus titik/koma)
        $data['harga_dasar'] = str_replace(['.', ','], '', $data['harga_dasar']);

        $data['updated_by'] = auth()->user()->id;

        // Prevent duplicate route when updating (exclude current record)
        if (!empty($data['kota_asal']) && !empty($data['kota_tujuan'])) {
            $exists = Rute::where('kota_asal', $data['kota_asal'])
                ->where('kota_tujuan', $data['kota_tujuan'])
                ->where('id', '!=', $rute->id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Rute antara ' . $data['kota_asal'] . ' dan ' . $data['kota_tujuan'] . ' sudah ada')
                    ->withInput();
            }
        }

        $rute->update($data);

        // Simpan relasi master tarif
        if (!empty($request->master_tarif_ids)) {
            $rute->masterTarifs()->sync($request->master_tarif_ids);
        }

        return redirect()->route('admin.rute.index')
            ->with('success', 'Rute berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rute = Rute::findOrFail($id);

        // Check if rute is still used
        if ($rute->shipments()->count() > 0 || $rute->jadwals()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Rute tidak dapat dihapus karena masih digunakan');
        }

        $rute->delete();

        return redirect()->route('admin.rute.index')
            ->with('success', 'Rute berhasil dihapus');
    }
}
