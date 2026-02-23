<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminJadwal extends Model
{
    use HasFactory;

    protected $table = 'admin_jadwals';
    
    protected $fillable = [
        'shuttle_id',
        'rute_id',
        'tanggal_berangkat',
        'jam_berangkat',
        'harga',
        'seat_total',
        'seat_available',
        'status_jadwal',
        'created_by',
    ];

    protected $casts = [
        'tanggal_berangkat' => 'date',
        'harga' => 'decimal:2',
        'seat_total' => 'integer',
        'seat_available' => 'integer',
    ];

    /**
     * Relasi ke shuttle
     */
    public function shuttle()
    {
        return $this->belongsTo(Shuttle::class, 'shuttle_id');
    }

    /**
     * Relasi ke rute
     */
    public function rute()
    {
        return $this->belongsTo(Rute::class, 'rute_id');
    }

    /**
     * Relasi ke admin yang membuat
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke driver yang mengambil jadwal (melalui driver_schedules)
     */
    public function driverSchedules()
    {
        return $this->hasMany(DriverSchedule::class, 'jadwal_id');
    }

    /**
     * Scope untuk jadwal yang available
     */
    public function scopeAvailable($query)
    {
        return $query->where('status_jadwal', 'available');
    }

    /**
     * Scope untuk jadwal yang sudah diambil
     */
    public function scopeTaken($query)
    {
        return $query->where('status_jadwal', 'taken');
    }

    /**
     * Cek apakah jadwal masih available
     */
    public function isAvailable()
    {
        return $this->status_jadwal === 'available' && $this->seat_available > 0;
    }

    /**
     * Update kursi tersedia setelah pemesanan
     */
    public function updateSeats($jumlah)
    {
        $this->seat_available -= $jumlah;
        if ($this->seat_available <= 0) {
            $this->seat_available = 0;
        }
        $this->save();
    }
}