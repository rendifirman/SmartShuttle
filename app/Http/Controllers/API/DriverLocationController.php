<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DriverLocation;
use App\Models\DriverJadwal;
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

        // Broadcast event for real-time admin notification
        broadcast(new \App\Events\DriverLocationUpdated(
            $driver->id,
            $validated['id_jadwal_driver'],
            $location
        ))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => $location
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
            $query->where('status', 'aktif')
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->updateLocation($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
