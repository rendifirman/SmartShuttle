<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $table = 'rutes';

    protected $fillable = [
        'layanan_id', // ← TAMBAHKAN INI
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

    // Relasi ke layanan
    public function layanan()
    {
        return $this->belongsTo(MLayanan::class, 'layanan_id', 'id_layanan');
    }

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

    // Scope untuk rute berdasarkan layanan
    public function scopeByLayanan($query, $layananId)
    {
        return $query->where('layanan_id', $layananId);
    }

    // Scope untuk rute aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
