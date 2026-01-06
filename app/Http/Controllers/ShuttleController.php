<?php

namespace App\Http\Controllers;

use App\Models\Shuttle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShuttleController extends Controller
{
    public function edit(Shuttle $shuttle)
    {
        return view('admin.shuttles.edit', compact('shuttle'));
    }

    public function update(Request $request, Shuttle $shuttle)
    {
        $validated = $request->validate([
            'nama_shuttle' => 'required|string|max:255',
            'tipe_shuttle' => 'nullable|string|max:100',
            'kapasitas_kursi' => 'required|integer|min:1',
            'total_kursi' => 'required|integer|min:1',
            'fasilitas' => 'nullable|string',
            'nomor_polisi' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif,servis',
            'gambar_depan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_samping' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_belakang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_interior' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image uploads
        $imageFields = ['gambar_depan', 'gambar_samping', 'gambar_belakang', 'gambar_interior'];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image if exists
                if ($shuttle->$field && Storage::exists('public/shuttles/' . $shuttle->$field)) {
                    Storage::delete('public/shuttles/' . $shuttle->$field);
                }

                // Store new image
                $image = $request->file($field);
                $imageName = time() . '_' . $field . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/shuttles', $imageName);
                $validated[$field] = $imageName;
            } else {
                // Keep existing image
                unset($validated[$field]);
            }
        }

        $shuttle->update($validated);

        return redirect()->route('admin.shuttles.index')
            ->with('success', 'Shuttle berhasil diupdate.');
    }

    public function getShuttleInfo($jadwalId)
    {
        try {
            // Cari jadwal dan relasi shuttle
            $jadwal = Jadwal::with('shuttle')->findOrFail($jadwalId);

            if (!$jadwal->shuttle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shuttle tidak ditemukan untuk jadwal ini'
                ], 404);
            }

            $shuttle = $jadwal->shuttle;

            return response()->json([
                'success' => true,
                'data' => [
                    'nama_shuttle' => $shuttle->nama_shuttle,
                    'tipe_shuttle' => $shuttle->tipe_shuttle,
                    'kapasitas_kursi' => $shuttle->kapasitas_kursi,
                    'nomor_polisi' => $shuttle->nomor_polisi,
                    'status' => $shuttle->status,
                    'fasilitas' => $shuttle->fasilitas_array,
                    'gambar' => $shuttle->gambar_shuttle,
                    'layout_kursi' => $shuttle->layout_kursi_array
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
