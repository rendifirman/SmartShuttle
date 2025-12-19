<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * Get all branches (with optional filters)
     */
    public function index(Request $request)
    {
        try {
            $query = Branch::query();

            // Filter by status
            $status = $request->input('status', 'aktif');
            if (in_array($status, ['aktif', 'nonaktif'])) {
                $query->where('status', $status);
            }

            // Filter by kota
            if ($request->has('kota')) {
                $query->where('kota', $request->input('kota'));
            }

            // Filter by nama cabang (search)
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('nama_cabang', 'LIKE', "%{$search}%")
                      ->orWhere('kota', 'LIKE', "%{$search}%");
                });
            }

            // Include outlets if requested
            $withOutlets = filter_var($request->input('with_outlets', false), FILTER_VALIDATE_BOOLEAN);
            if ($withOutlets) {
                $query->with(['outlets' => function($q) {
                    $q->where('status', 'aktif')
                      ->select('id', 'branch_id', 'nama_outlet', 'alamat_lengkap', 'telepon', 'tipe_outlet', 'jam_operasional');
                }]);
            }

            // Pagination
            $limit = $request->input('limit', 20);
            $branches = $query->orderBy('kota')->orderBy('nama_cabang')
                            ->paginate($limit);

            return response()->json([
                'success' => true,
                'data' => $branches->items(),
                'meta' => [
                    'total' => $branches->total(),
                    'per_page' => $branches->perPage(),
                    'current_page' => $branches->currentPage(),
                    'last_page' => $branches->lastPage(),
                ],
                'message' => 'Daftar cabang berhasil diambil'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get single branch detail
     */
    public function show($id)
    {
        try {
            $branch = Branch::with(['outlets' => function($q) {
                $q->where('status', 'aktif')
                  ->orderBy('nama_outlet');
            }])->find($id);

            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cabang tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $branch,
                'message' => 'Detail cabang berhasil diambil'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get outlets by branch
     */
    public function outlets($branchId)
    {
        try {
            $branch = Branch::find($branchId);

            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cabang tidak ditemukan'
                ], 404);
            }

            $outlets = Outlet::where('branch_id', $branchId)
                           ->where('status', 'aktif')
                           ->orderBy('nama_outlet')
                           ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'branch' => $branch->only(['id', 'nama_cabang', 'kota']),
                    'outlets' => $outlets
                ],
                'message' => 'Daftar outlet cabang berhasil diambil'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get unique cities list
     */
    public function cities()
    {
        try {
            $cities = Branch::where('status', 'aktif')
                           ->select('kota')
                           ->distinct()
                           ->orderBy('kota')
                           ->pluck('kota');

            return response()->json([
                'success' => true,
                'data' => $cities,
                'message' => 'Daftar kota berhasil diambil'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Search branches near coordinates
     */
  /**
 * Search branches near coordinates (FIXED for PostgreSQL)
 */
/**
 * Search branches near coordinates (FIXED for PostgreSQL)
 */
public function nearby(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'numeric|min:1|max:100', // dalam km
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 10);

        // **FIX: Gunakan subquery untuk PostgreSQL**
        $branches = Branch::where('status', 'aktif')
            ->whereNotNull('koordinat_gps')
            ->selectRaw("*,
                (6371 * acos(cos(radians(?)) * cos(radians(
                    CAST(SPLIT_PART(koordinat_gps, ',', 1) AS DECIMAL(10,6))
                )) * cos(radians(
                    CAST(SPLIT_PART(koordinat_gps, ',', 2) AS DECIMAL(10,6))
                ) - radians(?)) + sin(radians(?)) * sin(radians(
                    CAST(SPLIT_PART(koordinat_gps, ',', 1) AS DECIMAL(10,6))
                )))) AS distance",
                [$latitude, $longitude, $latitude])
            ->get() // Get all then filter in PHP (karena HAVING issue)
            ->filter(function ($branch) use ($radius) {
                return isset($branch->distance) && $branch->distance < $radius;
            })
            ->sortBy('distance')
            ->values(); // Reset keys

        // Format response
        $formattedBranches = $branches->map(function ($branch) {
            return [
                'id' => $branch->id,
                'kode_cabang' => $branch->kode_cabang,
                'nama_cabang' => $branch->nama_cabang,
                'kota' => $branch->kota,
                'alamat' => $branch->alamat,
                'telepon' => $branch->telepon,
                'koordinat_gps' => $branch->koordinat_gps,
                'jam_operasional' => $branch->getJamOperasionalAttribute(),
                'distance_km' => round($branch->distance, 2),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedBranches,
            'meta' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius_km' => $radius,
                'total' => $formattedBranches->count()
            ],
            'message' => 'Cabang terdekat berhasil diambil'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server',
            'error' => env('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
}
}
