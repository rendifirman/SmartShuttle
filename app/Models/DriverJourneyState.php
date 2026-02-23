<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverJourneyState extends Model
{
    use SoftDeletes;

    protected $table = 'driver_journey_states';

    protected $fillable = [
        'id_driver',
        'id_jadwal_driver',
        'current_stop_index',
        'status',
        'started_at',
        'completed_at',
        'last_stop_name',
        'total_stops',
    ];

    protected $casts = [
        'current_stop_index' => 'integer',
        'total_stops' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Driver (User)
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'id_driver');
    }

    /**
     * Relasi ke DriverJadwal
     */
    public function driverJadwal()
    {
        return $this->belongsTo(DriverJadwal::class, 'id_jadwal_driver', 'id_jadwal_driver');
    }

    /**
     * Get all location updates for this journey
     */
    public function locationUpdates()
    {
        return $this->hasMany(DriverLocation::class, 'id_jadwal_driver', 'id_jadwal_driver');
    }

    /**
     * Scope to get active journeys
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope to get journeys by driver
     */
    public function scopeByDriver($query, $driverId)
    {
        return $query->where('id_driver', $driverId);
    }

    /**
     * Scope to get journeys by trip
     */
    public function scopeByTrip($query, $tripId)
    {
        return $query->where('id_jadwal_driver', $tripId);
    }

    /**
     * Start journey
     */
    public static function startJourney($driverId, $tripId, $totalStops = 0)
    {
        return self::updateOrCreate(
            [
                'id_driver' => $driverId,
                'id_jadwal_driver' => $tripId,
            ],
            [
                'current_stop_index' => 0,
                'status' => 'in_progress',
                'started_at' => now(),
                'total_stops' => $totalStops,
            ]
        );
    }

    /**
     * Update current stop
     */
    public function updateCurrentStop($stopIndex, $stopName = null)
    {
        $this->update([
            'current_stop_index' => $stopIndex,
            'last_stop_name' => $stopName,
        ]);
        return $this;
    }

    /**
     * Complete journey
     */
    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        return $this;
    }
}
