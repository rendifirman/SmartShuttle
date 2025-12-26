<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikels';
    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'gambar',
        'kategori',
        'penulis',
        'dilihat',
        'status',
        'tanggal_publikasi',
        'meta_keywords',
        'meta_description'
    ];

    protected $casts = [
        'tanggal_publikasi' => 'date',
        'status' => 'boolean'
    ];

    /**
     * Scope untuk artikel aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope untuk artikel terbaru
     */
    public function scopeTerbaru($query)
    {
        return $query->orderBy('tanggal_publikasi', 'desc');
    }

    /**
     * Mendapatkan URL gambar lengkap
     */
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return asset('storage/' . $this->gambar);
        }
        return asset('images/default-article.jpg');
    }

    /**
     * Mendapatkan excerpt dari konten
     */
    public function getExcerptAttribute($length = 150)
    {
        $stripped = strip_tags($this->konten);
        return Str::limit($stripped, $length);
    }

    /**
     * Format tanggal publikasi
     */
    public function getTanggalFormatAttribute()
    {
        return $this->tanggal_publikasi->translatedFormat('d F Y');
    }

    /**
     * Hitung waktu baca
     */
    public function getWaktuBacaAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->konten));
        $minutes = ceil($wordCount / 200); // Asumsi 200 kata per menit
        return $minutes . ' min read';
    }

    /**
     * Auto generate slug sebelum create/update
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artikel) {
            if (empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });

        static::updating(function ($artikel) {
            if ($artikel->isDirty('judul')) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });
    }
}
