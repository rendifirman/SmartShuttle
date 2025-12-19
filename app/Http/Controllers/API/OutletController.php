<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OutletController extends Controller
{
    /**
     * Get all outlets
     */
    public function index(Request $request)
    {
        try {
            $query = Outlet::with(['branch:id,nama_cabang,kota'])
                          ->where('status', 'aktif');

            // Filter by branch_id
            if ($request->has('branch_id')) {
                $query->where('branch_id', $request->input('branch_id'));
            }

            // Filter by kota (via branch)
            if ($request->has('kota')) {
                $query->whereHas('branch', function($q) use ($request) {
                    $q->where('kota', $request->input('kota'));
                });
            }

            // Filter by tipe_outlet
            if ($request->has('tipe')) {
                $query->where('tipe_outlet', $request->input('tipe'));
            }

            // Search
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('nama_outlet', 'LIKE', "%{$search}%")
                      ->orWhere('alamat_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('zona_pelayanan', 'LIKE', "%{$search}%");
                });
            }

            // Filter by fasilitas
            if ($request->has('fasilitas')) {
                $fasilitas = explode(',', $request->input('fasilitas'));
                foreach ($fasilitas as $fasilitasItem) {
                    $query->where('fasilitas', 'LIKE', "%{$fasilitasItem}%");
                }
            }

            // Filter by boolean facilities
            if ($request->has('toilet')) {
                $query->where('tersedia_toilet', filter_var($request->input('toilet'), FILTER_VALIDATE_BOOLEAN));
            }
            if ($request->has('wifi')) {
                $query->where('tersedia_wifi', filter_var($request->input('wifi'), FILTER_VALIDATE_BOOLEAN));
            }

            // Pagination
            $limit = $request->input('limit', 20);
            $outlets = $query->orderBy('nama_outlet')
                           ->paginate($limit);

            // Transform data untuk API response
            $outlets->getCollection()->transform(function ($outlet) {
                return [
                    'id' => $outlet->id,
                    'nama_outlet' => $outlet->nama_outlet,
                    'branch' => $outlet->branch ? [
                        'id' => $outlet->branch->id,
                        'nama_cabang' => $outlet->branch->nama_cabang,
                        'kota' => $outlet->branch->kota
                    ] : null,
                    'alamat' => $outlet->alamat_lengkap,
                    'telepon' => $outlet->telepon,
                    'email' => $outlet->email,
                    'fasilitas' => $outlet->fasilitas ? array_map('trim', explode(',', $outlet->fasilitas)) : [],
                    'fasilitas_tambahan' => [
                        'toilet' => (bool) $outlet->tersedia_toilet,
                        'musholla' => (bool) $outlet->tersedia_musholla,
                        'atm' => (bool) $outlet->tersedia_atm,
                        'wifi' => (bool) $outlet->tersedia_wifi,
                    ],
                    'jam_operasional' => $outlet->jam_operasional,
                    'tipe_outlet' => $outlet->tipe_outlet,
                    'zona_pelayanan' => $outlet->zona_pelayanan,
                    'kapasitas_parkir' => $outlet->kapasitas_parkir,
                    'foto_url' => $outlet->foto_url,
                    'created_at' => $outlet->created_at,
                    'updated_at' => $outlet->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $outlets->items(),
                'meta' => [
                    'total' => $outlets->total(),
                    'per_page' => $outlets->perPage(),
                    'current_page' => $outlets->currentPage(),
                    'last_page' => $outlets->lastPage(),
                ],
                'message' => 'Daftar outlet berhasil diambil'
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
     * Get single outlet detail
     */
    public function show($id)
    {
        try {
            $outlet = Outlet::with(['branch'])->find($id);

            if (!$outlet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Outlet tidak ditemukan'
                ], 404);
            }

            $data = [
                'id' => $outlet->id,
                'nama_outlet' => $outlet->nama_outlet,
                'branch' => $outlet->branch ? [
                    'id' => $outlet->branch->id,
                    'kode_cabang' => $outlet->branch->kode_cabang,
                    'nama_cabang' => $outlet->branch->nama_cabang,
                    'kota' => $outlet->branch->kota,
                    'alamat' => $outlet->branch->alamat,
                    'jam_operasional' => $outlet->branch->getJamOperasionalAttribute(),
                ] : null,
                'alamat_lengkap' => $outlet->alamat_lengkap,
                'telepon' => $outlet->telepon,
                'email' => $outlet->email,
                'fasilitas' => $outlet->fasilitas ? array_map('trim', explode(',', $outlet->fasilitas)) : [],
                'fasilitas_tambahan' => [
                    'toilet' => (bool) $outlet->tersedia_toilet,
                    'musholla' => (bool) $outlet->tersedia_musholla,
                    'atm' => (bool) $outlet->tersedia_atm,
                    'wifi' => (bool) $outlet->tersedia_wifi,
                ],
                'jam_operasional' => $outlet->jam_operasional,
                'tipe_outlet' => $outlet->tipe_outlet,
                'zona_pelayanan' => $outlet->zona_pelayanan,
                'kapasitas_parkir' => $outlet->kapasitas_parkir,
                'foto_url' => $outlet->foto_url,
                'status' => $outlet->status,
                'created_at' => $outlet->created_at,
                'updated_at' => $outlet->updated_at,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Detail outlet berhasil diambil'
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
     * Get nearby outlets by coordinates
     */
    public function nearby(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'radius' => 'numeric|min:1|max:50',
                'kota' => 'nullable|string',
                'with_branch' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get branches nearby first
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $radius = $request->input('radius', 10);
            $kota = $request->input('kota');

            // Start with outlets query
            $query = Outlet::where('status', 'aktif');

            // Filter by kota if provided
            if ($kota) {
                $query->whereHas('branch', function($q) use ($kota) {
                    $q->where('kota', $kota);
                });
            }

            // Include branch info if requested
            $withBranch = filter_var($request->input('with_branch', false), FILTER_VALIDATE_BOOLEAN);
            if ($withBranch) {
                $query->with(['branch']);
            }

            // Get outlets with their branch coordinates
            $outlets = $query->get();

            // Filter outlets by distance from their branch
            $nearbyOutlets = $outlets->filter(function ($outlet) use ($latitude, $longitude, $radius) {
                // Use branch coordinates
                if ($outlet->branch && $outlet->branch->koordinat_gps) {
                    $coords = explode(',', $outlet->branch->koordinat_gps);
                    if (count($coords) === 2) {
                        $branchLat = floatval(trim($coords[0]));
                        $branchLng = floatval(trim($coords[1]));

                        $distance = $this->calculateDistance($latitude, $longitude, $branchLat, $branchLng);
                        return $distance <= $radius;
                    }
                }
                return false;
            })->values();

            // Transform data
            $nearbyOutlets = $nearbyOutlets->map(function ($outlet) use ($latitude, $longitude) {
                $data = [
                    'id' => $outlet->id,
                    'nama_outlet' => $outlet->nama_outlet,
                    'alamat' => $outlet->alamat_lengkap,
                    'telepon' => $outlet->telepon,
                    'jam_operasional' => $outlet->jam_operasional,
                    'tipe_outlet' => $outlet->tipe_outlet,
                    'foto_url' => $outlet->foto_url,
                ];

                // Include branch if available
                if ($outlet->branch) {
                    $data['branch'] = [
                        'id' => $outlet->branch->id,
                        'nama_cabang' => $outlet->branch->nama_cabang,
                        'kota' => $outlet->branch->kota,
                    ];

                    // Calculate distance
                    $coords = explode(',', $outlet->branch->koordinat_gps);
                    if (count($coords) === 2) {
                        $branchLat = floatval(trim($coords[0]));
                        $branchLng = floatval(trim($coords[1]));
                        $data['distance_km'] = round($this->calculateDistance($latitude, $longitude, $branchLat, $branchLng), 2);
                    }
                }

                return $data;
            })->sortBy('distance_km');

            return response()->json([
                'success' => true,
                'data' => $nearbyOutlets,
                'meta' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'radius_km' => $radius,
                    'total' => $nearbyOutlets->count()
                ],
                'message' => 'Outlet terdekat berhasil diambil'
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
     * Get unique outlet types
     */
    public function types()
    {
        try {
            $types = Outlet::where('status', 'aktif')
                          ->select('tipe_outlet')
                          ->distinct()
                          ->whereNotNull('tipe_outlet')
                          ->orderBy('tipe_outlet')
                          ->pluck('tipe_outlet');

            return response()->json([
                'success' => true,
                'data' => $types,
                'message' => 'Daftar tipe outlet berhasil diambil'
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
     * Calculate distance between two coordinates in km (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return $distance;
    }
}
