<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MLayanan;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LayananController extends Controller
{
    /**
     * Get all services (layanan)
     *
     * Query parameters:
     * - kategori: transport/logistics/rental
     * - status: true/false (default: true)
     * - search: search by nama_layanan
     * - limit: pagination limit
     * - sort_by: column to sort
     * - sort_order: asc/desc
     */
    public function index(Request $request)
    {
        try {
            $query = MLayanan::query();

            // Filter by kategori
            if ($request->has('kategori')) {
                $kategori = $request->input('kategori');
                if (in_array($kategori, ['transport', 'logistics', 'rental'])) {
                    $query->where('kategori_layanan', $kategori);
                }
            }

            // Filter by status_aktif
            if ($request->has('status')) {
                $status = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);
                $query->where('status_aktif', $status);
            } else {
                // Default: hanya tampilkan yang aktif
                $query->where('status_aktif', true);
            }

            // Search by nama_layanan or deskripsi_singkat
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('nama_layanan', 'ILIKE', "%{$search}%")
                      ->orWhere('deskripsi_singkat', 'ILIKE', "%{$search}%")
                      ->orWhere('deskripsi_panjang', 'ILIKE', "%{$search}%");
                });
            }

            // Sorting
            $sortBy = $request->input('sort_by', 'urutan_tampilan');
            $sortOrder = $request->input('sort_order', 'asc');
            $allowedSortColumns = ['nama_layanan', 'kode_layanan', 'urutan_tampilan', 'created_at'];

            if (in_array($sortBy, $allowedSortColumns)) {
                $query->orderBy($sortBy, $sortOrder);
            } else {
                $query->orderBy('urutan_tampilan')->orderBy('nama_layanan');
            }

            // Pagination
            $limit = $request->input('limit', 20);
            $services = $query->paginate($limit);

            // Transform data untuk API response
            $services->getCollection()->transform(function ($service) {
                return $this->formatLayananResponse($service);
            });

            return response()->json([
                'success' => true,
                'data' => $services->items(),
                'meta' => [
                    'total' => $services->total(),
                    'per_page' => $services->perPage(),
                    'current_page' => $services->currentPage(),
                    'last_page' => $services->lastPage(),
                ],
                'message' => 'Daftar layanan berhasil diambil'
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
     * Get single service detail
     */
    public function show($id)
    {
        try {
            $service = MLayanan::find($id);

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Layanan tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatLayananResponse($service, true),
                'message' => 'Detail layanan berhasil diambil'
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
     * Get services by slug
     */
    public function bySlug($slug)
    {
        try {
            $service = MLayanan::where('slug', $slug)->first();

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Layanan tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatLayananResponse($service, true),
                'message' => 'Detail layanan berhasil diambil'
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
     * Get services by kategori
     */
    public function byKategori($kategori)
    {
        try {
            if (!in_array($kategori, ['transport', 'logistics', 'rental'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak valid. Pilih: transport, logistics, rental'
                ], 422);
            }

            $services = MLayanan::where('kategori_layanan', $kategori)
                               ->where('status_aktif', true)
                               ->orderBy('urutan_tampilan')
                               ->orderBy('nama_layanan')
                               ->get();

            $formattedServices = $services->map(function ($service) {
                return $this->formatLayananResponse($service);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedServices,
                'meta' => [
                    'kategori' => $kategori,
                    'total' => $services->count()
                ],
                'message' => 'Daftar layanan berdasarkan kategori berhasil diambil'
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
     * Get unique kategori list
     */
    public function kategoriList()
    {
        try {
            $kategori = MLayanan::select('kategori_layanan')
                               ->distinct()
                               ->orderBy('kategori_layanan')
                               ->pluck('kategori_layanan');

            // Tambah deskripsi untuk setiap kategori
            $kategoriWithDesc = collect($kategori)->map(function ($kategori) {
                $descriptions = [
                    'transport' => 'Layanan transportasi penumpang',
                    'logistics' => 'Layanan pengiriman barang/paket',
                    'rental' => 'Layanan penyewaan kendaraan'
                ];

                return [
                    'kode' => $kategori,
                    'nama' => ucfirst($kategori),
                    'deskripsi' => $descriptions[$kategori] ?? 'Layanan ' . $kategori
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $kategoriWithDesc,
                'message' => 'Daftar kategori layanan berhasil diambil'
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
     * Get active services for homepage (beranda)
     */
    public function forHomepage()
    {
        try {
            $services = MLayanan::where('status_aktif', true)
                               ->orderBy('urutan_tampilan')
                               ->orderBy('nama_layanan')
                               ->limit(6) // Max 6 untuk homepage
                               ->get();

            $formattedServices = $services->map(function ($service) {
                return [
                    'id_layanan' => $service->id_layanan,
                    'kode_layanan' => $service->kode_layanan,
                    'nama_layanan' => $service->nama_layanan,
                    'slug' => $service->slug,
                    'deskripsi_singkat' => $service->deskripsi_singkat,
                    'kategori_layanan' => $service->kategori_layanan,
                    'logo_url' => $this->getLogoUrl($service->logo),
                    'icon_url' => $this->getIconUrl($service->icon),
                    'urutan_tampilan' => $service->urutan_tampilan,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedServices,
                'message' => 'Layanan untuk homepage berhasil diambil'
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
     * Helper: Format service response
     */
    private function formatLayananResponse($service, $detail = false)
    {
        $data = [
            'id_layanan' => $service->id_layanan,
            'kode_layanan' => $service->kode_layanan,
            'nama_layanan' => $service->nama_layanan,
            'slug' => $service->slug,
            'deskripsi_singkat' => $service->deskripsi_singkat,
            'kategori_layanan' => $service->kategori_layanan,
            'logo_url' => $this->getLogoUrl($service->logo),
            'icon_url' => $this->getIconUrl($service->icon),
            'status_aktif' => (bool) $service->status_aktif,
            'urutan_tampilan' => $service->urutan_tampilan,
            'created_at' => $service->created_at,
            'updated_at' => $service->updated_at,
        ];

        // Tambah detail lengkap jika diperlukan
        if ($detail) {
            $data['deskripsi_panjang'] = $service->deskripsi_panjang;
            $data['meta'] = $service->meta ?? [];
        }

        return $data;
    }

    /**
     * Helper: Get full logo URL
     */
    private function getLogoUrl($logoPath)
    {
        if (!$logoPath) {
            return null;
        }

        // Cek jika sudah URL lengkap
        if (str_starts_with($logoPath, 'http://') ||
            str_starts_with($logoPath, 'https://')) {
            return $logoPath;
        }

        // Cek jika file ada di storage
        if (Storage::disk('public')->exists($logoPath)) {
            return Storage::url($logoPath);
        }

        // Cek jika file ada di public
        if (file_exists(public_path($logoPath))) {
            return asset($logoPath);
        }

        return null;
    }

    /**
     * Helper: Get full icon URL
     */
    private function getIconUrl($iconPath)
    {
        if (!$iconPath) {
            return null;
        }

        // Cek jika sudah URL lengkap
        if (str_starts_with($iconPath, 'http://') ||
            str_starts_with($iconPath, 'https://')) {
            return $iconPath;
        }

        // Cek jika file ada di storage
        if (Storage::disk('public')->exists($iconPath)) {
            return Storage::url($iconPath);
        }

        // Cek jika file ada di public
        if (file_exists(public_path($iconPath))) {
            return asset($iconPath);
        }

        return null;
    }
    // Di dalam method byLayanan, update query:
/**
 * Get schedules by layanan
 */
public function byLayanan($layananId)
{
    try {
        $layanan = MLayanan::find($layananId);
        if (!$layanan) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan tidak ditemukan'
            ], 404);
        }

        // Gunakan relasi yang benar
        $schedules = Jadwal::with([
            'shuttle.layanan',
            'rutes'
        ])
        ->whereHas('shuttle', function($q) use ($layananId) {
            $q->where('layanan_id', $layananId);
        })
        ->where('status', 'tersedia')
        ->whereDate('tanggal_keberangkatan', '>=', Carbon::today())
        ->orderBy('tanggal_keberangkatan')
        ->orderBy('waktu_keberangkatan')
        ->get();

        // Format response
        $formattedSchedules = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'tanggal_keberangkatan' => $schedule->tanggal_keberangkatan,
                'waktu_keberangkatan' => $schedule->waktu_keberangkatan,
                'waktu_kedatangan' => $schedule->waktu_kedatangan,
                'harga_total' => (float) $schedule->harga_total,
                'kursi_tersedia' => $schedule->kursi_tersedia,
                'status' => $schedule->status,
                'shuttle' => $schedule->shuttle ? [
                    'id' => $schedule->shuttle->id,
                    'nama_shuttle' => $schedule->shuttle->nama_shuttle,
                    'kapasitas_kursi' => $schedule->shuttle->kapasitas_kursi,
                    'tipe_shuttle' => $schedule->shuttle->tipe_shuttle
                ] : null,
                'rute' => $schedule->rutes->isNotEmpty() ? [
                    'id' => $schedule->rutes->first()->id,
                    'nama_rute' => $schedule->rutes->first()->nama_rute,
                    'kota_asal' => $schedule->rutes->first()->kota_asal,
                    'kota_tujuan' => $schedule->rutes->first()->kota_tujuan
                ] : null
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedSchedules,
            'meta' => [
                'layanan' => [
                    'id' => $layanan->id_layanan,
                    'nama' => $layanan->nama_layanan,
                    'kategori' => $layanan->kategori_layanan
                ],
                'total' => $schedules->count()
            ],
            'message' => 'Jadwal berdasarkan layanan berhasil diambil'
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
