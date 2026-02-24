<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DriverLocation;
use App\Models\DriverJadwal;
use App\Models\DriverJourneyState;
use App\Events\DriverJourneyStarted;
use App\Events\DriverJourneyCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverLocationController extends Controller
{
    /**
     * Update driver location - called when driver updates location during trip
     */
    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'id_jadwal_driver' => 'required|integer',
            'location_name' => 'required|string|max:255',
            'location_detail' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'stop_index' => 'required|integer|min:0',
            'status' => 'required|in:in_transit,arrived,completed',
        ]);

        $driver = Auth::guard('driver')->user();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not authenticated'
            ], 401);
        }

        // Verify the trip belongs to this driver
        $trip = DriverJadwal::where('id_jadwal_driver', $validated['id_jadwal_driver'])
            ->where('id_driver', $driver->id)
            ->first();

        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Trip not found or does not belong to this driver'
            ], 404);
        }

        // Save the location update
        $location = DriverLocation::create([
            'id_driver' => $driver->id,
            'id_jadwal_driver' => $validated['id_jadwal_driver'],
            'location_name' => $validated['location_name'],
            'location_detail' => $validated['location_detail'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'stop_index' => $validated['stop_index'],
            'status' => $validated['status'],
        ]);

        // ★★★ Update or create journey state ★★★
        $journeyState = DriverJourneyState::updateOrCreate(
            [
                'id_driver' => $driver->id,
                'id_jadwal_driver' => $validated['id_jadwal_driver'],
            ],
            [
                'current_stop_index' => $validated['stop_index'],
                'status' => $validated['status'] === 'completed' ? 'completed' : 'in_progress',
                'started_at' => now(),
                'last_stop_name' => $validated['location_name'],
                'completed_at' => $validated['status'] === 'completed' ? now() : null,
            ]
        );

        // Broadcast event for real-time admin notification
        broadcast(new \App\Events\DriverLocationUpdated(
            $driver->id,
            $validated['id_jadwal_driver'],
            $location
        ))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => $location,
            'journey_state' => $journeyState
        ], 200);
    }

    /**
     * Get latest location for a specific driver trip
     */
    public function getLatestLocation($driverId, $tripId)
    {
        $location = DriverLocation::where('id_driver', $driverId)
            ->where('id_jadwal_driver', $tripId)
            ->latest('created_at')
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'No location data found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $location
        ], 200);
    }

    /**
     * Get all locations for a specific driver trip (for tracking history)
     */
    public function getTripLocations($driverId, $tripId)
    {
        $locations = DriverLocation::where('id_driver', $driverId)
            ->where('id_jadwal_driver', $tripId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $locations,
            'count' => $locations->count()
        ], 200);
    }

    /**
     * Get current active driver locations for admin dashboard
     */
    public function getActiveDriverLocations()
    {
        // Get all drivers who are currently on active trips
        $locations = DriverLocation::whereHas('driverJadwal', function($query) {
            // Include both 'aktif' and 'dalam_perjalanan' as active statuses
            $query->whereIn('status', ['aktif', 'dalam_perjalanan'])
                ->where('tanggal', \Carbon\Carbon::today());
        })
            ->with(['driver', 'driverJadwal'])
            ->latest('created_at')
            ->get();

        // Group by driver and trip
        $groupedLocations = $locations->groupBy('id_driver');

        return response()->json([
            'success' => true,
            'data' => $groupedLocations,
            'count' => $locations->count()
        ], 200);
    }

    /**
     * Complete/Finish a trip - mark journey as finished
     */
    public function completeTrip(Request $request)
    {
        $validated = $request->validate([
            'id_jadwal_driver' => 'required|integer',
        ]);

        $driver = Auth::guard('driver')->user();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not authenticated'
            ], 401);
        }

        // Verify the trip belongs to this driver
        $trip = DriverJadwal::where('id_jadwal_driver', $validated['id_jadwal_driver'])
            ->where('id_driver', $driver->id)
            ->first();

        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Trip not found or does not belong to this driver'
            ], 404);
        }

        // Update trip status to completed
        $trip->update([
            'status' => 'selesai'
        ]);

        // Save final location
        $location = DriverLocation::create([
            'id_driver' => $driver->id,
            'id_jadwal_driver' => $validated['id_jadwal_driver'],
            'location_name' => 'Destination Reached',
            'location_detail' => 'Trip completed',
            'latitude' => null,
            'longitude' => null,
            'stop_index' => -1,
            'status' => 'completed',
        ]);

        // Broadcast journey completed event
        try {
            broadcast(new DriverJourneyCompleted($driver->id, $validated['id_jadwal_driver']))->toOthers();
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast DriverJourneyCompleted', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Trip completed successfully',
            'data' => [
                'trip' => $trip,
                'location' => $location
            ]
        ], 200);
    }

    /**
     * Start a journey - called when driver clicks "Mulai Perjalanan"
     */
    public function startJourney(Request $request)
    {
        $validated = $request->validate([
            'id_jadwal_driver' => 'required|integer',
            'total_stops' => 'nullable|integer|min:1',
        ]);

        $driver = Auth::guard('driver')->user();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not authenticated'
            ], 401);
        }

        // Verify the trip belongs to this driver
        $trip = DriverJadwal::where('id_jadwal_driver', $validated['id_jadwal_driver'])
            ->where('id_driver', $driver->id)
            ->first();

        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Trip not found or does not belong to this driver'
            ], 404);
        }

        // Create or update journey state
        $journeyState = DriverJourneyState::startJourney(
            $driver->id,
            $validated['id_jadwal_driver'],
            $validated['total_stops'] ?? 0
        );

        // Update the DriverJadwal status to 'dalam_perjalanan' so the schedule reflects a started journey
        try {
            $trip->update([
                'status' => 'dalam_perjalanan'
            ]);
        } catch (\Exception $e) {
            // Log but don't fail the request; journey state is primary
            \Log::error('Failed to update DriverJadwal status on journey start', [
                'id_jadwal_driver' => $validated['id_jadwal_driver'],
                'error' => $e->getMessage()
            ]);
        }

        // Broadcast journey started event for real-time updates
        try {
            broadcast(new DriverJourneyStarted($driver->id, $validated['id_jadwal_driver'], $journeyState))->toOthers();
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast DriverJourneyStarted', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Journey started successfully',
            'data' => $journeyState
        ], 200);
    }

    /**
     * Get journey state for authenticated driver and given trip
     */
    public function getJourneyState($tripId)
    {
        $driver = Auth::guard('driver')->user();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not authenticated'
            ], 401);
        }

        $state = \App\Models\DriverJourneyState::where('id_driver', $driver->id)
            ->where('id_jadwal_driver', $tripId)
            ->first();

        if (!$state) {
            return response()->json([
                'success' => true,
                'data' => [
                    'status' => 'not_started',
                    'current_stop_index' => 0,
                    'total_stops' => 0
                ]
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $state
        ], 200);
    }

    /**
     * Get complete trip details including stop_points and kursi terpesan
     * Ensures data is always available even for completed trips
     */
    public function getTripDetail($tripId)
    {
        $driver = Auth::guard('driver')->user();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not authenticated'
            ], 401);
        }

        // Fetch the trip with all relationships
        $trip = DriverJadwal::with([
            'jadwal',
            'jadwal.rutes',
            'jadwal.rutes.stop_points',
            'jadwal.rutes.stop_points.outlets',
            'masterRute'
        ])
            ->where('id_jadwal_driver', $tripId)
            ->where('id_driver', $driver->id)
            ->first();

        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Trip not found or does not belong to this driver'
            ], 404);
        }

        // Extract route information
        $route = $trip->jadwal && $trip->jadwal->rutes ? $trip->jadwal->rutes->first() : null;
        $stopPoints = $route && $route->stop_points ? $route->stop_points->toArray() : [];

        // ★★★ PERBAIKAN: Ambil data penumpang dari admin Jadwal penumpang source ★★★
        // Gunakan Pemesanan + DetailPenumpang (sama seperti admin/jadwal/{id}/penumpang)
        $jadwalId = $trip->id_jadwal;
        $pemesanan = \App\Models\Jadwal::find($jadwalId)
            ? \App\Models\Jadwal::findOrFail($jadwalId)->pemesanan()
                ->with(['user', 'detailPenumpang', 'pembayaran', 'kursiTerpesan'])
                ->get()
            : collect([]);

        // Transform Pemesanan + DetailPenumpang ke format penumpang
        $passengers = [];
        $occupiedCount = 0;

        foreach ($pemesanan as $booking) {
            foreach ($booking->detailPenumpang as $detail) {
                $occupiedCount++;

                // Cari seat dari kursiTerpesan
                $seat = $booking->kursiTerpesan()
                    ->where('detail_penumpang_id', $detail->id)
                    ->first();

                $passengers[] = [
                    'id' => $detail->id,
                    'name' => $detail->nama_lengkap,
                    'phone' => $detail->telepon ?? $booking->telepon_pemesan,
                    'seat' => $seat ? $seat->nomor_kursi : 'N/A',
                    'nik' => $detail->nik,
                    'status' => $seat ? $seat->status : 'pending',
                    'jenis_kelamin' => $detail->jenis_kelamin,
                ];
            }
        }

        // Build comprehensive response
        $tripDetail = [
            'id_jadwal_driver' => $trip->id_jadwal_driver,
            'id_driver' => $trip->id_driver,
            'id_jadwal' => $trip->id_jadwal,
            'from' => $trip->masterRute ? $trip->masterRute->kota_asal : 'N/A',
            'to' => $trip->masterRute ? $trip->masterRute->kota_tujuan : 'N/A',
            'date' => $trip->tanggal ? $trip->tanggal->toDateString() : $trip->tanggal,
            'time' => $trip->waktu_keberangkatan,
            'eta' => $trip->waktu_kedatangan,
            'status' => $trip->status,
            'estimated_duration' => $trip->estimated_duration,
            'distance' => $trip->jarak,
            'total_seats' => $trip->total_kursi ?? 0,
            'occupied_seats' => $occupiedCount,
            'stop_points' => $stopPoints,
            'passengers' => $passengers, // ★★★ Data dari admin Jadwal penumpang (Pemesanan + DetailPenumpang) ★★★
            'created_at' => $trip->created_at,
            'updated_at' => $trip->updated_at,
        ];

        // Get journey state
        $journeyState = DriverJourneyState::where('id_driver', $driver->id)
            ->where('id_jadwal_driver', $tripId)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $tripDetail,
            'journey_state' => $journeyState ? $journeyState->toArray() : [
                'status' => 'not_started',
                'current_stop_index' => 0,
                'total_stops' => 0
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->updateLocation($request);
    }

    /**
     * ★★★ API ENDPOINT: Fetch passengers from admin jadwal penumpang data source ★★★
     * Data source: Pemesanan -> DetailPenumpang (from Jadwal)
     * Ini menggunakan data yang sama dengan admin/jadwal/{id}/penumpang
     */
    public function getTripPassengersFromAdmin($tripId)
    {
        try {
            $driver = Auth::guard('driver')->user();

            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'message' => 'Driver not authenticated'
                ], 401);
            }

            // Get DriverJadwal with Jadwal relationship
            $driverJadwal = \App\Models\DriverJadwal::with(['jadwal'])
                ->where('id_jadwal_driver', $tripId)
                ->where('id_driver', $driver->id)
                ->first();

            if (!$driverJadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trip not found'
                ], 404);
            }

            // Get Jadwal ID from DriverJadwal
            $jadwalId = $driverJadwal->id_jadwal;

            if (!$jadwalId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak terkait dengan trip ini'
                ], 404);
            }

            // ★★★ Fetch Pemesanan + DetailPenumpang (SAMA SEPERTI admin/jadwal/{id}/penumpang) ★★★
            $pemesanan = \App\Models\Jadwal::findOrFail($jadwalId)
                ->pemesanan()
                ->with(['user', 'detailPenumpang', 'pembayaran', 'kursiTerpesan'])
                ->get();

            // Transform data ke format penumpang
            $passengers = [];
            $totalPassengers = 0;

            foreach ($pemesanan as $booking) {
                foreach ($booking->detailPenumpang as $detail) {
                    $totalPassengers++;

                    // Cari seat dari kursiTerpesan
                    $seat = $booking->kursiTerpesan()
                        ->where('detail_penumpang_id', $detail->id)
                        ->first();

                    $passengers[] = [
                        'id' => $detail->id,
                        'name' => $detail->nama_lengkap,
                        'phone' => $detail->telepon ?? $booking->telepon_pemesan,
                        'seat' => $seat ? $seat->nomor_kursi : 'N/A',
                        'nik' => $detail->nik,
                        'status' => $seat ? $seat->status : 'pending',
                        'jenis_kelamin' => $detail->jenis_kelamin,
                        'booking_code' => $booking->kode_booking,
                        'booking_id' => $booking->id,
                    ];
                }
            }

            // Get trip data untuk total seats
            $trip = $driverJadwal;
            $totalSeats = $trip->total_kursi ?? 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'trip_id' => $tripId,
                    'jadwal_id' => $jadwalId,
                    'total_passengers' => $totalPassengers,
                    'occupied_seats' => $totalPassengers,
                    'total_seats' => $totalSeats,
                    'available_seats' => $totalSeats - $totalPassengers,
                    'passengers' => $passengers,
                    'timestamp' => now()->toIso8601String()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting passengers from admin jadwal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
