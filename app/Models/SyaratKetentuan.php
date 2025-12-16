<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyaratKetentuan extends Model
{
    protected $table = 'syarat_ketentuan';
    
    protected $fillable = [
        'sk_kode',
        'sk_judul',
        'sk_konten_html',
        'sk_versi',
        'sk_tanggal_efektif',
        'sk_status_aktif',
        'sk_tipe'
    ];
    
    protected $casts = [
        'sk_tanggal_efektif' => 'date',
        'sk_status_aktif' => 'boolean'
    ];
    
    // Ambil yang aktif untuk pengguna
    public static function getUntukPengguna()
    {
        return self::where('sk_tipe', 'pengguna')
                   ->where('sk_status_aktif', true)
                   ->orderBy('sk_tanggal_efektif', 'desc')
                   ->first();
    }
    
    // Ambil semua yang aktif
    public static function getAllAktif()
    {
        return self::where('sk_status_aktif', true)
                   ->orderBy('sk_tipe')
                   ->orderBy('sk_tanggal_efektif', 'desc')
                   ->get();
    }
    
    // Ambil berdasarkan tipe
    public static function getByTipe($tipe)
    {
        return self::where('sk_tipe', $tipe)
                   ->where('sk_status_aktif', true)
                   ->orderBy('sk_tanggal_efektif', 'desc')
                   ->first();
    }
}