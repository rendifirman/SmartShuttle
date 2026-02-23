<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterTarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MasterTarifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tarifs = MasterTarif::paginate(15);

        // Get summary data
        $totalTarif = MasterTarif::count();
        $tarifAktif = MasterTarif::where('status', 'aktif')->count();
        $tarifTidakAktif = MasterTarif::where('status', 'tidak_aktif')->count();

        // Get unique jenis tarif
        $jenisOptions = MasterTarif::distinct()->pluck('jenis_tarif')->filter()->sort()->values();

        return view('admin.master-tarif.index', [
            'title' => 'Master Data - Tarif',
            'pageTitle' => 'Master Tarif',
            'tarifs' => $tarifs,
            'totalTarif' => $totalTarif,
            'tarifAktif' => $tarifAktif,
            'tarifTidakAktif' => $tarifTidakAktif,
            'jenisOptions' => $jenisOptions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-tarif.create', [
            'title' => 'Tambah Master Tarif',
            'pageTitle' => 'Tambah Master Tarif',
            'jenisOptions' => ['penumpang', 'paket', 'cargo', 'charter']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_tarif' => 'required|string|max:255',
            'jenis_tarif' => 'required|in:penumpang,paket,cargo,charter',
            'sk_tarif' => 'nullable|string|max:255',
            'harga_dasar' => 'required|numeric|min:0',
            'harga_minimum' => 'required|numeric|min:0',
            'harga_maksimum' => 'nullable|numeric|min:0',
            'diskon_persentase' => 'required|numeric|min:0|max:100',
            'diskon_nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal_berlaku' => 'nullable|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_berlaku',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['kode_tarif'] = 'TAR-' . Str::upper(Str::random(6));
        $data['created_by'] = auth()->user()->id;

        MasterTarif::create($data);

        return redirect()->route('admin.master-tarif.index')
            ->with('success', 'Master Tarif berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tarif = MasterTarif::findOrFail($id);
        $relatedRutes = $tarif->rutes()->count();
        $relatedJadwals = $tarif->driverJadwals()->count();

        return view('admin.master-tarif.show', [
            'title' => 'Detail Master Tarif',
            'pageTitle' => 'Detail Master Tarif',
            'tarif' => $tarif,
            'relatedRutes' => $relatedRutes,
            'relatedJadwals' => $relatedJadwals
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tarif = MasterTarif::findOrFail($id);

        return view('admin.master-tarif.edit', [
            'title' => 'Edit Master Tarif',
            'pageTitle' => 'Edit Master Tarif',
            'tarif' => $tarif,
            'jenisOptions' => ['penumpang', 'paket', 'cargo', 'charter']
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tarif = MasterTarif::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_tarif' => 'required|string|max:255',
            'jenis_tarif' => 'required|in:penumpang,paket,cargo,charter',
            'sk_tarif' => 'nullable|string|max:255',
            'harga_dasar' => 'required|numeric|min:0',
            'harga_minimum' => 'required|numeric|min:0',
            'harga_maksimum' => 'nullable|numeric|min:0',
            'diskon_persentase' => 'required|numeric|min:0|max:100',
            'diskon_nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal_berlaku' => 'nullable|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_berlaku',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['updated_by'] = auth()->user()->id;

        $tarif->update($data);

        return redirect()->route('admin.master-tarif.index')
            ->with('success', 'Master Tarif berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tarif = MasterTarif::findOrFail($id);

        // Cek apakah tarif masih digunakan
        if ($tarif->rutes()->count() > 0 || $tarif->driverJadwals()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tarif tidak dapat dihapus karena masih digunakan');
        }

        $tarif->delete();

        return redirect()->route('admin.master-tarif.index')
            ->with('success', 'Master Tarif berhasil dihapus');
    }

    /**
     * Soft delete - ubah status menjadi tidak aktif
     */
    public function deactivate($id)
    {
        $tarif = MasterTarif::findOrFail($id);
        $tarif->update(['status' => 'tidak_aktif']);

        return redirect()->back()
            ->with('success', 'Master Tarif berhasil dinonaktifkan');
    }

    /**
     * Activate tarif
     */
    public function activate($id)
    {
        $tarif = MasterTarif::findOrFail($id);
        $tarif->update(['status' => 'aktif']);

        return redirect()->back()
            ->with('success', 'Master Tarif berhasil diaktifkan');
    }

    /**
     * Export tarif ke CSV
     */
    public function export()
    {
        $tarifs = MasterTarif::all();

        $csvFileName = 'master-tarif-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $headers = array(
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function() use ($tarifs) {
            $file = fopen('php://output', 'w');
            // Header
            fputcsv($file, ['Kode Tarif', 'Nama Tarif', 'Jenis', 'SK Tarif', 'Harga Dasar', 'Harga Min', 'Harga Max', 'Diskon %', 'Diskon Nominal', 'Berlaku Dari', 'Berlaku Sampai', 'Status']);

            foreach($tarifs as $tarif) {
                fputcsv($file, [
                    $tarif->kode_tarif,
                    $tarif->nama_tarif,
                    $tarif->jenis_tarif,
                    $tarif->sk_tarif,
                    $tarif->harga_dasar,
                    $tarif->harga_minimum,
                    $tarif->harga_maksimum,
                    $tarif->diskon_persentase,
                    $tarif->diskon_nominal,
                    $tarif->tanggal_berlaku,
                    $tarif->tanggal_kadaluarsa,
                    $tarif->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
