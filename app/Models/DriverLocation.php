<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    protected $table = 'driver_locations';

    protected $fillable = [
        'id_driver',
        'id_jadwal_driver',
        'location_name',
        'location_detail',
        'latitude',
        'longitude',
        'stop_index',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'stop_index' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User (Driver)
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
}
