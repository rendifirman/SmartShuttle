<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MLayanan extends Model
{
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
    
    /**
     * Scope untuk layanan aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }
    
    /**
     * Scope untuk urutan tampilan
     */
    public function scopeUrutan($query)
    {
        return $query->orderBy('urutan_tampilan', 'asc')
                    ->orderBy('nama_layanan', 'asc');
    }
    
    /**
     * Scope berdasarkan kategori
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori_layanan', $kategori);
    }
    
    /**
     * Get status as text (helper for views)
     */
    public function getStatusTextAttribute()
    {
        return $this->status_aktif ? 'Aktif' : 'Tidak Aktif';
    }
    
    /**
     * Get status badge class (helper for views)
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status_aktif ? 'badge-success' : 'badge-secondary';
    }
    
    /**
     * Relasi ke Shuttle
     */
    public function shuttles()
    {
        return $this->hasMany(Shuttle::class, 'layanan_id', 'id_layanan');
    }
    
    /**
     * Relasi ke Jadwal melalui Shuttle
     */
    public function jadwals()
    {
        return $this->hasManyThrough(
            Jadwal::class,
            Shuttle::class,
            'layanan_id', // Foreign key on shuttles table
            'shuttle_id', // Foreign key on jadwals table
            'id_layanan', // Local key on m_layanan table
            'id' // Local key on shuttles table
        );
    }
}