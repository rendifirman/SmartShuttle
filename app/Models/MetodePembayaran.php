<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran';
    
    protected $fillable = [
        'nama',
        'kode',
        'jenis',
        'deskripsi',
        'biaya_admin',
        'estimasi_waktu',
        'instruksi',
        'aktif'
    ];

    protected $casts = [
        'biaya_admin' => 'decimal:2',
        'estimasi_waktu' => 'integer',
        'instruksi' => 'array',
        'aktif' => 'boolean'
    ];

    // Scope untuk metode aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true)->orderBy('nama', 'asc');
    }

    // Scope untuk metode berdasarkan jenis
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis)->where('aktif', true);
    }

    // Scope untuk metode berdasarkan kode
    public function scopeKode($query, $kode)
    {
        return $query->where('kode', $kode)->where('aktif', true);
    }

    // Cek apakah metode tersedia
    public function getTersediaAttribute()
    {
        return $this->aktif;
    }

    // Format biaya admin
    public function getBiayaAdminFormattedAttribute()
    {
        return 'Rp ' . number_format($this->biaya_admin, 0, ',', '.');
    }

    // Format estimasi waktu
    public function getEstimasiWaktuFormattedAttribute()
    {
        return $this->estimasi_waktu . ' menit';
    }

    // Get instruksi sebagai array
    public function getInstruksiArrayAttribute()
    {
        return is_array($this->instruksi) ? $this->instruksi : [];
    }
}