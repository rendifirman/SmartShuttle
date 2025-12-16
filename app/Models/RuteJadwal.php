<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuteJadwal extends Model
{
    use HasFactory;

    protected $table = 'rute_jadwals';
    
    protected $fillable = [
        'jadwal_id',
        'rute_id',
        'urutan',
        'durasi_segment',
        'harga_segment'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }


    public function rute()
    {
        return $this->belongsTo(Rute::class);
    }
}