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

    protected $casts = [
        'tersedia_toilet' => 'boolean',
        'tersedia_musholla' => 'boolean',
        'tersedia_atm' => 'boolean',
        'tersedia_wifi' => 'boolean',
         'tipe_outlet' => 'string', // tambahkan ini
    ];

    protected $appends = ['foto_url', 'fasilitas_array', 'kota'];

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

    // ========== METHOD HELPER UNTUK FASILITAS ==========
    public function getFasilitasArray()
    {
        $fasilitas = [];
        
        // 1. Tambahkan dari field boolean
        if ($this->tersedia_toilet) $fasilitas[] = 'Toilet';
        if ($this->tersedia_musholla) $fasilitas[] = 'Musholla';
        if ($this->tersedia_atm) $fasilitas[] = 'ATM';
        if ($this->tersedia_wifi) $fasilitas[] = 'WiFi';
        
        // 2. Tambahkan dari field string fasilitas (jika ada)
        if (!empty($this->fasilitas)) {
            $additional = array_map('trim', explode(',', $this->fasilitas));
            $fasilitas = array_merge($fasilitas, $additional);
        }
        
        // 3. Jika kosong, beri default
        if (empty($fasilitas)) {
            $fasilitas = ['Ruang Tunggu', 'Informasi Tiket'];
        }
        
        // 4. Hapus duplikat dan return
        return array_unique($fasilitas);
    }

    // ========== ACCESSORS ==========
    
    // Accessor untuk fasilitas array (untuk JSON)
    public function getFasilitasArrayAttribute()
    {
        return $this->getFasilitasArray();
    }

    // Accessor untuk mendapatkan URL gambar
    public function getFotoUrlAttribute()
    {
        if (!$this->foto_outlet) {
            return asset('images/placeholder-outlet.jpg');
        }

        // Cek jika sudah URL lengkap
        if (str_starts_with($this->foto_outlet, 'http://') ||
            str_starts_with($this->foto_outlet, 'https://')) {
            return $this->foto_outlet;
        }

        // Cek jika file ada di storage
        if (Storage::disk('public')->exists($this->foto_outlet)) {
            return Storage::url($this->foto_outlet);
        }

        // Cek di public/images/outlets/
        $filename = basename($this->foto_outlet);
        $publicPath = 'images/outlets/' . $filename;
        
        if (file_exists(public_path($publicPath))) {
            return asset($publicPath);
        }

        // Cek di public/images/
        if (file_exists(public_path('images/' . $this->foto_outlet))) {
            return asset('images/' . $this->foto_outlet);
        }

        // Cek langsung path
        if (file_exists(public_path($this->foto_outlet))) {
            return asset($this->foto_outlet);
        }

        return asset('images/placeholder-outlet.jpg');
    }

    // Accessor untuk kota dari branch
    public function getKotaAttribute()
    {
        return $this->branch ? $this->branch->kota : null;
    }

    // Accessor untuk nama cabang
    public function getNamaCabangAttribute()
    {
        return $this->branch ? $this->branch->nama_cabang : 'Tidak diketahui';
    }

    // Accessor untuk informasi lengkap
    public function getInfoLengkapAttribute()
    {
        return $this->nama_outlet . ' - ' . ($this->branch ? $this->branch->nama_cabang : '');
    }
}