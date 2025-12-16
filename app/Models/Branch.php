<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'kota',
        'alamat',
        'telepon',
        'email',
        'koordinat_gps',
        'jam_buka',
        'jam_tutup',
        'status'
    ];

    protected $casts = [
        'jam_buka' => 'datetime:H:i',
        'jam_tutup' => 'datetime:H:i',
    ];

    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }

    public function getJamOperasionalAttribute()
    {
        return $this->jam_buka && $this->jam_tutup 
            ? date('H:i', strtotime($this->jam_buka)) . ' - ' . date('H:i', strtotime($this->jam_tutup))
            : '24 Jam';
    }

    public function getJumlahOutletAttribute()
    {
        return $this->outlets()->where('status', 'aktif')->count();
    }
}