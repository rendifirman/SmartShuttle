<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KebijakanPrivasi extends Model
{
    protected $table = 'kebijakan_privasi';
    
    protected $fillable = [
        'kp_kode',
        'kp_judul',
        'kp_konten_html',
        'kp_versi',
        'kp_tanggal_efektif',
        'kp_status_aktif'
    ];
    
    protected $casts = [
        'kp_tanggal_efektif' => 'date',
        'kp_status_aktif' => 'boolean'
    ];
    
    // Ambil yang aktif
    public static function getAktif()
    {
        return self::where('kp_status_aktif', true)
                   ->orderBy('kp_tanggal_efektif', 'desc')
                   ->first();
    }
}