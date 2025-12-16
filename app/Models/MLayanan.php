<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Hapus: use Illuminate\Database\Eloquent\SoftDeletes;

class MLayanan extends Model
{
    // Hapus: use SoftDeletes;
    
    protected $table = 'm_layanan';
    protected $primaryKey = 'id_layanan';
    
    protected $fillable = [
        'kode_layanan',
        'nama_layanan',
        'slug',
        'deskripsi_singkat',
        'deskripsi_panjang',
        'icon',
        'logo',
        'kategori_layanan',
        'status_aktif',
        'urutan_tampilan',
        'meta'
    ];
    
    protected $casts = [
        'status_aktif' => 'boolean',
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Scope untuk layanan aktif
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }
    
    // Scope untuk urutan tampilan
    public function scopeUrutan($query)
    {
        return $query->orderBy('urutan_tampilan', 'asc')
                    ->orderBy('nama_layanan', 'asc');
    }
    
    // Scope berdasarkan kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori_layanan', $kategori);
    }
}