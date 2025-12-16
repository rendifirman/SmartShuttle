<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $table = 'rutes';
    
    protected $fillable = [
        'kode_rute',
        'nama_rute',
        'kota_asal',
        'kota_tujuan',
        'durasi',
        'jarak',
        'harga_dasar',
        'rute_pemberhentian',
        'status'
    ];

  public function jadwals()
{
    return $this->belongsToMany(Jadwal::class, 'rute_jadwals', 'rute_id', 'jadwal_id')
                ->withPivot(['urutan', 'durasi_segment', 'harga_segment'])
                ->withTimestamps();
}
    
    public function getPemberhentianArrayAttribute()
    {
        return $this->rute_pemberhentian ? json_decode($this->rute_pemberhentian, true) : [];
    }
}