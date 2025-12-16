<?php

namespace App\Http\Controllers;

use App\Models\Shuttle;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class ShuttleController extends Controller
{
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