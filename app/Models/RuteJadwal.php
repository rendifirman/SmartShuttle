<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuteJadwal extends Model
{
    protected $table = 'rute_jadwal';

    protected $fillable = [
        'id_rute',
        'id_shuttle',
        'id_driver',
        'tanggal',
        'jam_berangkat',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_berangkat' => 'string',
    ];

    const STATUS_OPEN = 'open';
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_DONE = 'done';

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }


    public function rute()
    {
        return $this->belongsTo(Rute::class);
    }
}