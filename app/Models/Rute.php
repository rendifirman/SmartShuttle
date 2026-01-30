<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rute extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rutes';

    protected $fillable = [
        'layanan_id',
        'kode_rute',
        'nama_rute',
        'kota_asal',
        'kota_tujuan',
        'durasi',
        'jarak',
        'harga_dasar',
        'rute_pemberhentian',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    
    protected $appends = ['formatted_harga'];

    protected $casts = [
        'harga_dasar' => 'decimal:2',
        'jarak' => 'decimal:2',
    ];

    // Relasi ke layanan
    public function layanan()
    {
        return $this->belongsTo(MLayanan::class, 'layanan_id', 'id_layanan');
    }

    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'rute_jadwals', 'rute_id', 'jadwal_id')
            ->withTimestamps()
            ->withPivot('urutan', 'durasi_segment', 'harga_segment');
    }

    public function getPemberhentianArrayAttribute()
    {
        return $this->rute_pemberhentian ? json_decode($this->rute_pemberhentian, true) : [];
    }

    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga_dasar, 0, ',', '.');
    }

    // Format durasi untuk display
    public function getFormattedDurasiAttribute()
    {
        $parts = explode(':', $this->durasi);
        if (count($parts) >= 2) {
            $jam = intval($parts[0]);
            $menit = intval($parts[1]);

            $result = '';
            if ($jam > 0) {
                $result .= $jam . ' Jam';
            }
            if ($menit > 0) {
                if ($jam > 0) $result .= ' ';
                $result .= $menit . ' Menit';
            }
            return $result;
        }
        return $this->durasi;
    }

    // Scope untuk rute berdasarkan layanan
    public function scopeByLayanan($query, $layananId)
    {
        return $query->where('layanan_id', $layananId);
    }

    // Scope untuk rute aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk search
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('kode_rute', 'like', '%' . $search . '%')
              ->orWhere('nama_rute', 'like', '%' . $search . '%')
              ->orWhere('kota_asal', 'like', '%' . $search . '%')
              ->orWhere('kota_tujuan', 'like', '%' . $search . '%');
        });
    }

    // Relasi ke user yang membuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user yang update
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relasi ke user yang delete
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
