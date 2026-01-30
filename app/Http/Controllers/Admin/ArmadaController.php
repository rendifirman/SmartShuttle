<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shuttle;
use App\Models\MLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArmadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shuttles = Shuttle::with('layanan')->paginate(10);

        return view('admin.armada.index', [
            'title' => 'Master Data - Armada',
            'pageTitle' => 'Armada',
            'shuttles' => $shuttles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layanans = MLayanan::all();

        return view('admin.armada.create', [
            'title' => 'Tambah Armada',
            'pageTitle' => 'Tambah Armada',
            'layanans' => $layanans
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'layanan_id' => 'required|exists:m_layanan,id_layanan',
            'nama_shuttle' => 'required|string|max:255',
            'tipe_shuttle' => 'required|string|max:255',
            'kapasitas_kursi' => 'required|integer|min:1',
            'total_kursi' => 'required|integer|min:1',
            'nomor_polisi' => 'required|string|max:20',
            'fasilitas' => 'nullable|string',
            'status' => 'required|in:aktif,tidak_aktif',
            'gambar_depan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_samping' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_belakang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_interior' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['gambar_depan', 'gambar_samping', 'gambar_belakang', 'gambar_interior']);

        // Handle file uploads
        $imageFields = ['gambar_depan', 'gambar_samping', 'gambar_belakang', 'gambar_interior'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/shuttles', $filename);
                $data[$field] = $filename;
            }
        }

        // Generate layout kursi
        $data['layout_kursi'] = $this->generateSeatLayout($request->total_kursi);

        Shuttle::create($data);

        return redirect()->route('admin.armada.index')->with('success', 'Armada berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $shuttle = Shuttle::with('layanan')->findOrFail($id);

        return view('admin.armada.show', [
            'title' => 'Detail Armada',
            'pageTitle' => 'Detail Armada',
            'shuttle' => $shuttle
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $shuttle = Shuttle::findOrFail($id);
        $layanans = MLayanan::all();

        return view('admin.armada.edit', [
            'title' => 'Edit Armada',
            'pageTitle' => 'Edit Armada',
            'shuttle' => $shuttle,
            'layanans' => $layanans
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $shuttle = Shuttle::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'layanan_id' => 'required|exists:m_layanan,id_layanan',
            'nama_shuttle' => 'required|string|max:255',
            'tipe_shuttle' => 'required|string|max:255',
            'kapasitas_kursi' => 'required|integer|min:1',
            'total_kursi' => 'required|integer|min:1',
            'nomor_polisi' => 'required|string|max:20',
            'fasilitas' => 'nullable|string',
            'status' => 'required|in:aktif,tidak_aktif',
            'gambar_depan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_samping' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_belakang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_interior' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['gambar_depan', 'gambar_samping', 'gambar_belakang', 'gambar_interior']);

        // Handle file uploads
        $imageFields = ['gambar_depan', 'gambar_samping', 'gambar_belakang', 'gambar_interior'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($shuttle->$field && Storage::exists('public/shuttles/' . $shuttle->$field)) {
                    Storage::delete('public/shuttles/' . $shuttle->$field);
                }

                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/shuttles', $filename);
                $data[$field] = $filename;
            }
        }

        // Update layout kursi if total_kursi changed
        if ($request->total_kursi != $shuttle->total_kursi) {
            $data['layout_kursi'] = $this->generateSeatLayout($request->total_kursi);
        }

        $shuttle->update($data);

        return redirect()->route('admin.armada.index')->with('success', 'Armada berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shuttle = Shuttle::findOrFail($id);

        // Delete associated images
        $imageFields = ['gambar_depan', 'gambar_samping', 'gambar_belakang', 'gambar_interior'];
        foreach ($imageFields as $field) {
            if ($shuttle->$field && Storage::exists('public/shuttles/' . $shuttle->$field)) {
                Storage::delete('public/shuttles/' . $shuttle->$field);
            }
        }

        $shuttle->delete();

        return redirect()->route('admin.armada.index')->with('success', 'Armada berhasil dihapus');
    }

    /**
     * Update images for the specified resource.
     */
    public function updateImages(Request $request, $id)
    {
        $shuttle = Shuttle::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'gambar_depan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_samping' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_belakang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_interior' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $imageFields = ['gambar_depan', 'gambar_samping', 'gambar_belakang', 'gambar_interior'];
        $updatedImages = [];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($shuttle->$field && Storage::exists('public/shuttles/' . $shuttle->$field)) {
                    Storage::delete('public/shuttles/' . $shuttle->$field);
                }

                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/shuttles', $filename);
                $shuttle->$field = $filename;
                $updatedImages[$field] = $filename;
            }
        }

        $shuttle->save();

        return response()->json([
            'success' => true,
            'message' => 'Images updated successfully',
            'images' => $updatedImages
        ]);
    }

    /**
     * Get images for the specified resource.
     */
    public function getImages($id)
    {
        $shuttle = Shuttle::findOrFail($id);

        $images = [
            'gambar_depan' => $shuttle->gambar_depan ? Storage::url('shuttles/' . $shuttle->gambar_depan) : null,
            'gambar_samping' => $shuttle->gambar_samping ? Storage::url('shuttles/' . $shuttle->gambar_samping) : null,
            'gambar_belakang' => $shuttle->gambar_belakang ? Storage::url('shuttles/' . $shuttle->gambar_belakang) : null,
            'gambar_interior' => $shuttle->gambar_interior ? Storage::url('shuttles/' . $shuttle->gambar_interior) : null,
        ];

        return response()->json([
            'success' => true,
            'images' => $images
        ]);
    }

    /**
     * Generate seat layout based on total seats
     */
    private function generateSeatLayout($totalSeats)
    {
        $layout = [];
        $rows = ceil($totalSeats / 3);

        for ($row = 1; $row <= $rows; $row++) {
            for ($col = 1; $col <= 3; $col++) {
                $currentSeatCount = count($layout);
                if ($currentSeatCount >= $totalSeats) {
                    break 2;
                }

                $colLetter = chr(64 + $col);
                $seatNumber = $row . $colLetter;

                $layout[] = [
                    'nomor' => $seatNumber,
                    'posisi' => $col == 2 ? 'tengah' : ($col == 1 ? 'kiri' : 'kanan'),
                    'tipe' => 'reguler',
                    'harga_tambahan' => 0
                ];
            }
        }

        return json_encode($layout);
    }
}
