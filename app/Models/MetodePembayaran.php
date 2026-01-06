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
        'aktif',
        'nomor_rekening',
        'nama_rekening',
        'gambar',
        'urutan',
        'is_paylabs',
        'paylabs_channel_code',
        'paylabs_channel_name'
    ];

    protected $casts = [
        'biaya_admin' => 'decimal:2',
        'estimasi_waktu' => 'integer',
        'instruksi' => 'array', // Perhatikan: di database tipe JSON
        'aktif' => 'boolean',
        'is_paylabs' => 'boolean'
    ];

    // Scope untuk metode aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true)->orderBy('urutan', 'asc');
    }

    // Format biaya admin
    public function getBiayaAdminFormattedAttribute()
    {
        return 'Rp ' . number_format($this->biaya_admin, 0, ',', '.');
    }

    // Get instruksi sebagai array
    public function getInstruksiArrayAttribute()
    {
        // Jika instruksi sudah array, return langsung
        if (is_array($this->instruksi)) {
            return $this->instruksi;
        }

        // Jika string (JSON), decode
        if (is_string($this->instruksi)) {
            return json_decode($this->instruksi, true) ?? [];
        }

        // Default
        return [];
    }
}
