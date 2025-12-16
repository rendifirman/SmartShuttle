<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenumpang extends Model
{
    use HasFactory;

    protected $table = 'detail_penumpang';
    
    protected $fillable = [
        'pemesanan_id',
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'telepon',
        'nomor_kursi'
    ];

    // Relasi ke pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    // Relasi ke kursi terpesan
    public function kursiTerpesan()
    {
        return $this->hasOne(KursiTerpesan::class, 'detail_penumpang_id');
    }

    // Format jenis kelamin
    public function getJenisKelaminTextAttribute()
    {
        return $this->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
    }

    // Cek apakah punya nomor kursi
    public function getPunyaKursiAttribute()
    {
        return !empty($this->nomor_kursi);
    }
}