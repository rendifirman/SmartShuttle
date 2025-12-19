<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Shuttle;
use App\Models\MLayanan;
use App\Models\RuteJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Get all schedules with filters
     *
     * Query parameters:
     * - date: tanggal keberangkatan (YYYY-MM-DD)
     * - status: tersedia/penuh/berangkat/dibatalkan
     * - shuttle_id: filter by shuttle
     * - layanan_id: filter by layanan (via shuttle)
     * - departure_outlet: filter by outlet asal (via rute)
     * - destination_outlet: filter by outlet tujuan (via rute)
     * - min_seats: minimal kursi tersedia
     * - sort_by: column to sort
     * - sort_order: asc/desc
     * - limit: pagination limit
     */
   public function index(Request $request)
{
    try {
        $query = Jadwal::with([
            'shuttle' => function($q) {
                $q->select('id', 'nama_shuttle', 'kapasitas_kursi', 'tipe_shuttle', 'nomor_polisi');
            },
            'rutes' => function($q) {
                $q->select('id', 'nama_rute', 'kota_asal', 'kota_tujuan', 'durasi', 'harga_dasar');
            }
        ]);

        // Filter by tanggal
        if ($request->has('date')) {
            $date = $request->input('date');
            if ($this->isValidDate($date)) {
                $query->whereDate('tanggal_keberangkatan', $date);
            }
        }

        // Filter by status (default: tersedia)
        $status = $request->input('status', 'tersedia');
        if (in_array($status, ['tersedia', 'penuh', 'berangkat', 'dibatalkan'])) {
            $query->where('status', $status);
        }

        // Filter by layanan_id (via shuttle)
        if ($request->has('layanan_id')) {
            $layananId = $request->input('layanan_id');
            $query->whereHas('shuttle', function($q) use ($layananId) {
                $q->where('layanan_id', $layananId);
            });
        }

        // Filter by kota asal dan tujuan
        if ($request->has('kota_asal')) {
            $kotaAsal = $request->input('kota_asal');
            $query->whereHas('rutes', function($q) use ($kotaAsal) {
                $q->where('kota_asal', 'LIKE', "%{$kotaAsal}%");
            });
        }

        if ($request->has('kota_tujuan')) {
            $kotaTujuan = $request->input('kota_tujuan');
            $query->whereHas('rutes', function($q) use ($kotaTujuan) {
                $q->where('kota_tujuan', 'LIKE', "%{$kotaTujuan}%");
            });
        }

        // Filter minimal kursi
        if ($request->has('min_seats')) {
            $minSeats = (int) $request->input('min_seats');
            $query->where('kursi_tersedia', '>=', $minSeats);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'tanggal_keberangkatan');
        $sortOrder = $request->input('sort_order', 'asc');

        if (in_array($sortBy, ['tanggal_keberangkatan', 'waktu_keberangkatan', 'harga_total', 'kursi_tersedia'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('tanggal_keberangkatan')->orderBy('waktu_keberangkatan');
        }

        // Pagination
        $limit = $request->input('limit', 20);
        $schedules = $query->paginate($limit);

        // Transform data
        $schedules->getCollection()->transform(function ($schedule) {
            return $this->formatScheduleResponse($schedule);
        });

        return response()->json([
            'success' => true,
            'data' => $schedules->items(),
            'meta' => [
                'total' => $schedules->total(),
                'per_page' => $schedules->perPage(),
                'current_page' => $schedules->currentPage(),
                'last_page' => $schedules->lastPage(),
            ],
            'message' => 'Daftar jadwal berhasil diambil'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            'error' => env('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
}

    /**
     * Get active schedules (for homepage/search)
     *
     * Returns schedules with available seats from today onwards
     */
    public function active(Request $request)
    {
        try {
            $query = Jadwal::with([
                'shuttle' => function($q) {
                    $q->select('id', 'nama_shuttle', 'kapasitas_kursi', 'tipe_shuttle', 'layanan_id')
                      ->with('layanan:id_layanan,kode_layanan,nama_layanan');
                },
                'rutes'
            ])
            ->where('status', 'tersedia')
            ->where('kursi_tersedia', '>', 0)
            ->whereDate('tanggal_keberangkatan', '>=', Carbon::today());

            // Filter by date range
            if ($request->has('start_date')) {
                $startDate = $request->input('start_date');
                if ($this->isValidDate($startDate)) {
                    $query->whereDate('tanggal_keberangkatan', '>=', $startDate);
                }
            }

            if ($request->has('end_date')) {
                $endDate = $request->input('end_date');
                if ($this->isValidDate($endDate)) {
                    $query->whereDate('tanggal_keberangkatan', '<=', $endDate);
                }
            }

            // Filter by departure and destination cities
            if ($request->has('departure_city')) {
                $departureCity = $request->input('departure_city');
                $query->whereHas('rutes', function($q) use ($departureCity) {
                    $q->where('kota_asal', 'like', "%{$departureCity}%");
                });
            }

            if ($request->has('destination_city')) {
                $destinationCity = $request->input('destination_city');
                $query->whereHas('rutes', function($q) use ($destinationCity) {
                    $q->where('kota_tujuan', 'like', "%{$destinationCity}%");
                });
            }

            // Limit results
            $limit = $request->input('limit', 10);
            $schedules = $query->orderBy('tanggal_keberangkatan')
                              ->orderBy('waktu_keberangkatan')
                              ->limit($limit)
                              ->get();

            $formattedSchedules = $schedules->map(function ($schedule) {
                return $this->formatScheduleResponse($schedule, true);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedSchedules,
                'meta' => [
                    'total' => $schedules->count(),
                    'date_range' => [
                        'start' => $request->input('start_date', Carbon::today()->format('Y-m-d')),
                        'end' => $request->input('end_date', Carbon::today()->addDays(7)->format('Y-m-d'))
                    ]
                ],
                'message' => 'Jadwal aktif berhasil diambil'
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
     * Search available schedules (for booking)
     *
     * Parameters:
     * - departure_outlet: ID outlet asal
     * - destination_outlet: ID outlet tujuan
     * - date: tanggal keberangkatan
     * - passenger_count: jumlah penumpang
     */
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'departure_outlet' => 'required|exists:outlets,id',
                'destination_outlet' => 'required|exists:outlets,id',
                'date' => 'required|date|after_or_equal:today',
                'passenger_count' => 'required|integer|min:1|max:20'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $departureOutletId = $request->input('departure_outlet');
            $destinationOutletId = $request->input('destination_outlet');
            $date = $request->input('date');
            $passengerCount = $request->input('passenger_count');

            // Get rutes that connect these outlets
            $ruteIds = Rute::where(function($query) use ($departureOutletId, $destinationOutletId) {
                // This is a simplified search - you may need to adjust based on your rute structure
                $query->where(function($q) use ($departureOutletId) {
                    $q->where('kota_asal', 'like', "%{$departureOutletId}%")
                      ->orWhereJsonContains('rute_pemberhentian', ['outlets' => [$departureOutletId]]);
                })
                ->where(function($q) use ($destinationOutletId) {
                    $q->where('kota_tujuan', 'like', "%{$destinationOutletId}%")
                      ->orWhereJsonContains('rute_pemberhentian', ['outlets' => [$destinationOutletId]]);
                });
            })->pluck('id');

            if ($ruteIds->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'meta' => [
                        'search_params' => [
                            'departure_outlet' => $departureOutletId,
                            'destination_outlet' => $destinationOutletId,
                            'date' => $date,
                            'passenger_count' => $passengerCount
                        ],
                        'total' => 0
                    ],
                    'message' => 'Tidak ada rute yang tersedia untuk outlet yang dipilih'
                ]);
            }

            // Get schedules for these rutes
            $schedules = Jadwal::with([
                'shuttle' => function($q) {
                    $q->select('id', 'nama_shuttle', 'kapasitas_kursi', 'tipe_shuttle', 'layanan_id')
                      ->with('layanan:id_layanan,kode_layanan,nama_layanan');
                },
                'rutes'
            ])
            ->whereIn('id', function($query) use ($ruteIds) {
                $query->select('jadwal_id')
                      ->from('rute_jadwals')
                      ->whereIn('rute_id', $ruteIds);
            })
            ->whereDate('tanggal_keberangkatan', $date)
            ->where('status', 'tersedia')
            ->where('kursi_tersedia', '>=', $passengerCount)
            ->orderBy('waktu_keberangkatan')
            ->get();

            $formattedSchedules = $schedules->map(function ($schedule) use ($passengerCount) {
                $data = $this->formatScheduleResponse($schedule, true);
                $data['total_price'] = $schedule->harga_total * $passengerCount;
                return $data;
            });

            return response()->json([
                'success' => true,
                'data' => $formattedSchedules,
                'meta' => [
                    'search_params' => [
                        'departure_outlet' => $departureOutletId,
                        'destination_outlet' => $destinationOutletId,
                        'date' => $date,
                        'passenger_count' => $passengerCount
                    ],
                    'total' => $schedules->count()
                ],
                'message' => 'Pencarian jadwal berhasil'
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
     * Get schedule detail by ID
     */
    public function show($id)
    {
        try {
            $schedule = Jadwal::with([
                'shuttle' => function($q) {
                    $q->with(['layanan', 'driver:id,nama_lengkap,no_telepon']);
                },
                'rutes',
                'ruteJadwals.rute'
            ])->find($id);

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            // Get related schedules (same route on same day)
            $relatedSchedules = Jadwal::where('id', '!=', $id)
                ->where('shuttle_id', $schedule->shuttle_id)
                ->whereDate('tanggal_keberangkatan', $schedule->tanggal_keberangkatan)
                ->where('status', 'tersedia')
                ->orderBy('waktu_keberangkatan')
                ->limit(3)
                ->get();

            $response = $this->formatScheduleResponse($schedule, true);
            $response['related_schedules'] = $relatedSchedules->map(function ($related) {
                return [
                    'id' => $related->id,
                    'waktu_keberangkatan' => $related->waktu_keberangkatan,
                    'waktu_kedatangan' => $related->waktu_kedatangan,
                    'kursi_tersedia' => $related->kursi_tersedia,
                    'status' => $related->status
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $response,
                'message' => 'Detail jadwal berhasil diambil'
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
     * Get schedules by layanan (service)
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

            $schedules = Jadwal::with(['shuttle', 'rutes'])
                ->whereHas('shuttle', function($q) use ($layananId) {
                    $q->where('layanan_id', $layananId);
                })
                ->where('status', 'tersedia')
                ->whereDate('tanggal_keberangkatan', '>=', Carbon::today())
                ->orderBy('tanggal_keberangkatan')
                ->orderBy('waktu_keberangkatan')
                ->get();

            $formattedSchedules = $schedules->map(function ($schedule) {
                return $this->formatScheduleResponse($schedule);
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

    /**
     * Get schedules by route
     */
    public function byRoute($routeId)
    {
        try {
            $route = Rute::find($routeId);
            if (!$route) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rute tidak ditemukan'
                ], 404);
            }

            $schedules = Jadwal::with(['shuttle'])
                ->whereIn('id', function($query) use ($routeId) {
                    $query->select('jadwal_id')
                          ->from('rute_jadwals')
                          ->where('rute_id', $routeId);
                })
                ->where('status', 'tersedia')
                ->whereDate('tanggal_keberangkatan', '>=', Carbon::today())
                ->orderBy('tanggal_keberangkatan')
                ->orderBy('waktu_keberangkatan')
                ->get();

            $formattedSchedules = $schedules->map(function ($schedule) {
                return $this->formatScheduleResponse($schedule);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedSchedules,
                'meta' => [
                    'rute' => [
                        'id' => $route->id,
                        'nama_rute' => $route->nama_rute,
                        'kota_asal' => $route->kota_asal,
                        'kota_tujuan' => $route->kota_tujuan
                    ],
                    'total' => $schedules->count()
                ],
                'message' => 'Jadwal berdasarkan rute berhasil diambil'
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
     * Get today's schedules
     */
    public function today()
    {
        try {
            $schedules = Jadwal::with(['shuttle', 'rutes'])
                ->whereDate('tanggal_keberangkatan', Carbon::today())
                ->where('status', 'tersedia')
                ->orderBy('waktu_keberangkatan')
                ->get();

            // Group by waktu (pagi, siang, malam)
            $groupedSchedules = [
                'pagi' => [],
                'siang' => [],
                'malam' => []
            ];

            foreach ($schedules as $schedule) {
                $hour = (int) Carbon::parse($schedule->waktu_keberangkatan)->format('H');

                if ($hour >= 4 && $hour < 12) {
                    $group = 'pagi';
                } elseif ($hour >= 12 && $hour < 18) {
                    $group = 'siang';
                } else {
                    $group = 'malam';
                }

                $groupedSchedules[$group][] = $this->formatScheduleResponse($schedule);
            }

            return response()->json([
                'success' => true,
                'data' => $groupedSchedules,
                'meta' => [
                    'date' => Carbon::today()->format('Y-m-d'),
                    'total' => $schedules->count(),
                    'count_by_period' => [
                        'pagi' => count($groupedSchedules['pagi']),
                        'siang' => count($groupedSchedules['siang']),
                        'malam' => count($groupedSchedules['malam'])
                    ]
                ],
                'message' => 'Jadwal hari ini berhasil diambil'
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
     * Get upcoming schedules (next 7 days)
     */
    public function upcoming(Request $request)
    {
        try {
            $days = $request->input('days', 7);
            $limit = $request->input('limit', 10);

            $schedules = Jadwal::with(['shuttle.layanan', 'rutes'])
                ->whereDate('tanggal_keberangkatan', '>=', Carbon::today())
                ->whereDate('tanggal_keberangkatan', '<=', Carbon::today()->addDays($days))
                ->where('status', 'tersedia')
                ->where('kursi_tersedia', '>', 0)
                ->orderBy('tanggal_keberangkatan')
                ->orderBy('waktu_keberangkatan')
                ->limit($limit)
                ->get();

            // Group by date
            $groupedSchedules = [];
            foreach ($schedules as $schedule) {
                $date = $schedule->tanggal_keberangkatan;
                if (!isset($groupedSchedules[$date])) {
                    $groupedSchedules[$date] = [];
                }
                $groupedSchedules[$date][] = $this->formatScheduleResponse($schedule);
            }

            return response()->json([
                'success' => true,
                'data' => $groupedSchedules,
                'meta' => [
                    'date_range' => [
                        'start' => Carbon::today()->format('Y-m-d'),
                        'end' => Carbon::today()->addDays($days)->format('Y-m-d')
                    ],
                    'total' => $schedules->count(),
                    'days' => $days
                ],
                'message' => 'Jadwal mendatang berhasil diambil'
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
     * Check seat availability
     */
    public function checkAvailability($id)
    {
        try {
            $schedule = Jadwal::find($id);

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            $availability = [
                'available' => $schedule->kursi_tersedia > 0,
                'seats_available' => $schedule->kursi_tersedia,
                'total_capacity' => $schedule->shuttle->kapasitas_kursi ?? 12,
                'percentage_available' => round(($schedule->kursi_tersedia / ($schedule->shuttle->kapasitas_kursi ?? 12)) * 100, 2),
                'status' => $schedule->status,
                'can_book' => $schedule->status === 'tersedia' && $schedule->kursi_tersedia > 0
            ];

            return response()->json([
                'success' => true,
                'data' => $availability,
                'message' => 'Ketersediaan kursi berhasil diambil'
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
     * Helper: Format schedule response
     */
    private function formatScheduleResponse($schedule, $detailed = false)
    {
        $data = [
            'id' => $schedule->id,
            'tanggal_keberangkatan' => $schedule->tanggal_keberangkatan,
            'waktu_keberangkatan' => $schedule->waktu_keberangkatan,
            'waktu_kedatangan' => $schedule->waktu_kedatangan,
            'harga_total' => (float) $schedule->harga_total,
            'kursi_tersedia' => $schedule->kursi_tersedia,
            'status' => $schedule->status,
            'created_at' => $schedule->created_at,
            'updated_at' => $schedule->updated_at,
            'shuttle' => null,
            'rutes' => []
        ];

        // Add shuttle info
        if ($schedule->relationLoaded('shuttle') && $schedule->shuttle) {
            $data['shuttle'] = [
                'id' => $schedule->shuttle->id,
                'nama_shuttle' => $schedule->shuttle->nama_shuttle,
                'kapasitas_kursi' => $schedule->shuttle->kapasitas_kursi,
                'tipe_shuttle' => $schedule->shuttle->tipe_shuttle,
                'nomor_polisi' => $schedule->shuttle->nomor_polisi,
                'layanan' => $schedule->shuttle->relationLoaded('layanan') && $schedule->shuttle->layanan
                    ? [
                        'id' => $schedule->shuttle->layanan->id_layanan,
                        'nama' => $schedule->shuttle->layanan->nama_layanan,
                        'kode' => $schedule->shuttle->layanan->kode_layanan
                    ]
                    : null
            ];
        }

        // Add rutes info
        if ($schedule->relationLoaded('rutes') && $schedule->rutes->isNotEmpty()) {
            $data['rutes'] = $schedule->rutes->map(function ($rute) {
                return [
                    'id' => $rute->id,
                    'nama_rute' => $rute->nama_rute,
                    'kota_asal' => $rute->kota_asal,
                    'kota_tujuan' => $rute->kota_tujuan,
                    'durasi' => $rute->durasi,
                    'harga_dasar' => (float) $rute->harga_dasar
                ];
            });
        }

        // Add detailed info if requested
        if ($detailed && $schedule->relationLoaded('ruteJadwals')) {
            $data['rute_details'] = $schedule->ruteJadwals->map(function ($ruteJadwal) {
                return [
                    'urutan' => $ruteJadwal->urutan,
                    'durasi_segment' => $ruteJadwal->durasi_segment,
                    'harga_segment' => (float) $ruteJadwal->harga_segment,
                    'rute' => $ruteJadwal->relationLoaded('rute') ? [
                        'id' => $ruteJadwal->rute->id,
                        'nama_rute' => $ruteJadwal->rute->nama_rute
                    ] : null
                ];
            });
        }

        // Calculate duration
        $departure = Carbon::parse($schedule->tanggal_keberangkatan . ' ' . $schedule->waktu_keberangkatan);
        $arrival = Carbon::parse($schedule->tanggal_keberangkatan . ' ' . $schedule->waktu_kedatangan);

        // If arrival is earlier than departure, assume next day
        if ($arrival->lt($departure)) {
            $arrival->addDay();
        }

        $duration = $departure->diff($arrival);
        $data['durasi_jam'] = $duration->h + ($duration->i / 60);
        $data['durasi_format'] = sprintf('%d jam %d menit', $duration->h, $duration->i);

        return $data;
    }

    /**
     * Helper: Validate date format
     */
    private function isValidDate($date)
    {
        try {
            return Carbon::createFromFormat('Y-m-d', $date) !== false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
