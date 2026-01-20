<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'nama_outlet',
        'alamat_lengkap',
        'telepon',
        'email',
        'fasilitas',
        'jam_operasional',
        'foto_outlet',
        'tipe_outlet',
        'kapasitas_parkir',
        'tersedia_toilet',
        'tersedia_musholla',
        'tersedia_atm',
        'tersedia_wifi',
        'zona_pelayanan',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $table = 'outlets';

    // Boot method untuk auto-fill audit fields
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->deleted_by = Auth::id();
                $model->save();
            }
        });
    }

    // Relasi ke branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Accessor untuk mendapatkan fasilitas sebagai array
    public function getFasilitasArrayAttribute()
    {
        return $this->fasilitas ? explode(',', $this->fasilitas) : [];
    }

    // Accessor untuk mendapatkan URL gambar - FIXED
    public function getFotoUrlAttribute()
    {
        if (!$this->foto_outlet) {
            return asset('images/placeholder-outlet.jpg');
        }

        // Cek jika sudah URL lengkap (http/https)
        if (str_starts_with($this->foto_outlet, 'http://') ||
            str_starts_with($this->foto_outlet, 'https://')) {
            return $this->foto_outlet;
        }

        // Cek jika file ada di storage
        if (Storage::disk('public')->exists($this->foto_outlet)) {
            return Storage::url($this->foto_outlet);
        }

        // Cek jika file ada di public (tanpa 'images/')
        if (file_exists(public_path($this->foto_outlet))) {
            return asset($this->foto_outlet);
        }

        // Cek jika file ada di public/images
        if (file_exists(public_path('images/' . $this->foto_outlet))) {
            return asset('images/' . $this->foto_outlet);
        }

        // Fallback ke placeholder
        return asset('images/placeholder-outlet.jpg');
    }

    // Accessor untuk gambar (backward compatibility)
    public function getGambarAttribute()
    {
        return $this->foto_url;
    }

    // Accessor untuk kota dari branch
    public function getKotaAttribute()
    {
        return $this->branch ? $this->branch->kota : null;
    }

    // Accessor untuk informasi lengkap
    public function getInfoLengkapAttribute()
    {
        return $this->nama_outlet . ' - ' . ($this->branch ? $this->branch->nama_cabang : '');
    }
}
