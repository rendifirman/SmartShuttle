<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

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
        'status'
    ];

    protected $appends = ['formatted_harga'];

    // Relasi ke layanan
    public function layanan()
    {
        return $this->belongsTo(MLayanan::class, 'layanan_id', 'id_layanan');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'rute_id');
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
}
