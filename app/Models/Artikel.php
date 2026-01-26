<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
        /**
     * Relationship dengan kategori (jika ada model KategoriArtikel)
     */
    public function kategoriRelasi()
    {
        if (class_exists('App\Models\KategoriArtikel')) {
            return $this->belongsTo(KategoriArtikel::class, 'kategori', 'nama');
        }
        return null;
    }

    /**
     * Scope untuk artikel dengan kategori tertentu
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Cek apakah artikel bisa ditampilkan
     */
    public function getBisaDitampilkanAttribute()
    {
        return $this->status && 
               $this->tanggal_publikasi && 
               $this->tanggal_publikasi <= now();
    }
    
    use HasFactory;

    protected $table = 'artikels';
    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'gambar',       // rename dari 'thumbnail' ke 'gambar'
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
     * Alias untuk kompatibilitas
     */
    public function getThumbnailAttribute()
    {
        return $this->gambar;
    }

    public function setThumbnailAttribute($value)
    {
        $this->attributes['gambar'] = $value;
    }

    /**
     * Scope untuk artikel aktif (tampil di beranda customer)
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true)
                     ->whereNotNull('tanggal_publikasi')
                     ->where('tanggal_publikasi', '<=', now());
    }

    /**
     * Scope untuk artikel terbaru
     */
    public function scopeTerbaru($query)
    {
        return $query->orderBy('tanggal_publikasi', 'desc');
    }

    /**
     * Scope untuk artikel publik (untuk admin)
     */
    public function scopePublik($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope untuk artikel draft (untuk admin)
     */
    public function scopeDraft($query)
    {
        return $query->where('status', false);
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
     * Mendapatkan URL thumbnail (untuk admin)
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->gambar) {
            return asset('storage/' . $this->gambar);
        }
        return asset('images/default-thumbnail.jpg');
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
        $minutes = ceil($wordCount / 200);
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
                $artikel->slug = Str::slug($artikel->judul) . '-' . time();
            }
        });

        static::updating(function ($artikel) {
            if ($artikel->isDirty('judul')) {
                $artikel->slug = Str::slug($artikel->judul) . '-' . time();
            }
        });
    }
}