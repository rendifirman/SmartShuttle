<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmartRentArmada extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'smartrent_armadas';

    protected $fillable = [
        'shuttle_id',
        'nama',
        'tipe',
        'kapasitas',
        'nomor_polisi',
        'tahun',
        'bahan_bakar',
        'deskripsi',
        'gambar',
        'harga_dasar',
        'harga_dengan_sopir',
        'fasilitas',
        'status',
    ];

    protected $casts = [
        'fasilitas' => 'array',
        'harga_dasar' => 'decimal:2',
        'harga_dengan_sopir' => 'decimal:2',
    ];

    /**
     * Relationship dengan Shuttle jika ada
     */
    public function shuttle()
    {
        return $this->belongsTo(Shuttle::class);
    }

    /**
     * Relationship dengan SmartRent transactions
     */
    public function smartRentTransactions()
    {
        return $this->hasMany(SmartRent::class, 'armada_id');
    }

    /**
     * Scope untuk armada aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk filter berdasarkan tipe
     */
    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Get formatted price
     */
    public function getFormattedHargaDasarAttribute()
    {
        return 'Rp ' . number_format($this->harga_dasar, 0, ',', '.');
    }

    /**
     * Get formatted driver price
     */
    public function getFormattedHargaDenganSopirAttribute()
    {
        if (!$this->harga_dengan_sopir) {
            return null;
        }
        return 'Rp ' . number_format($this->harga_dengan_sopir, 0, ',', '.');
    }

    /**
     * Check if vehicle is available
     */
    public function isAvailable()
    {
        return $this->status === 'aktif';
    }
}
