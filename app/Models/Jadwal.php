<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'shuttle_id',
        'tanggal_keberangkatan',
        'waktu_keberangkatan',
        'waktu_kedatangan',
        'harga_total',
        'kursi_tersedia',
        'status'
    ];

    // Relasi ke shuttle
    public function shuttle()
    {
        return $this->belongsTo(Shuttle::class);
    }

    // Relasi ke rutes (many-to-many)
    public function rutes()
    {
        return $this->belongsToMany(Rute::class, 'rute_jadwals', 'jadwal_id', 'rute_id')
                    ->withPivot('urutan', 'durasi_segment', 'harga_segment')
                    ->withTimestamps();
    }

    // Relasi ke rute_jadwals
    public function ruteJadwals()
    {
        return $this->hasMany(RuteJadwal::class, 'jadwal_id');
    }

    // Method untuk mendapatkan semua pemberhentian
    public function getAllPemberhentian()
    {
        $pemberhentianList = [];

        foreach ($this->rutes as $rute) {
            $stops = json_decode($rute->rute_pemberhentian, true);
            if (is_array($stops)) {
                foreach ($stops as $stop) {
                    $pemberhentianList[] = [
                        'kota' => $stop['kota'],
                        'outlets' => $stop['outlets'] ?? [],
                        'durasi_singgah' => $stop['durasi_singgah'] ?? 0
                    ];
                }
            }
        }

        return $pemberhentianList;
    }

    // Accessor untuk string rute
    public function getRuteStringAttribute()
    {
        if ($this->rutes->isNotEmpty()) {
            $rute = $this->rutes->first();
            return $rute->nama_rute ?? 'Rute Tidak Diketahui';
        }
        return 'Rute Tidak Diketahui';
    }

    // Scope untuk jadwal aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'tersedia')
                    ->where('kursi_tersedia', '>', 0)
                    ->whereDate('tanggal_keberangkatan', '>=', now());
    }

    // ================ AUDIT RELATIONSHIPS ================

    /**
     * User who created this schedule
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated this schedule
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * User who deleted this schedule
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
