<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriArtikel extends Model
{
    use HasFactory;

    protected $table = 'kategori_artikels';
    protected $fillable = ['nama', 'slug', 'deskripsi', 'status'];
    
    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Relationship dengan artikel
     */
    public function artikels()
    {
        return $this->hasMany(Artikel::class, 'kategori', 'nama');
    }

    /**
     * Scope aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get route key name
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}