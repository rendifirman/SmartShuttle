<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';

    protected $fillable = [
        'shuttle_id',
        'tanggal_keberangkatan',
        'waktu_keberangkatan',
        'waktu_kedatangan',
        'harga_total',
        'kursi_tersedia',
        'status',
        'status_admin'
        // Jangan sertakan created_by, updated_by, deleted_by jika tidak ingin diisi
    ];

    protected $casts = [
        'tanggal_keberangkatan' => 'date',
    ];

    // Accessor untuk id_jadwal (agar kompatibel dengan view yang menggunakan $jadwal->id_jadwal)
    public function getIdJadwalAttribute()
    {
        return $this->id;
    }

    // Relasi ke shuttle
    public function shuttle()
    {
        return $this->belongsTo(Shuttle::class, 'shuttle_id');
    }

    // Relasi ke rutes (many-to-many)
    public function rutes()
    {
        return $this->belongsToMany(Rute::class, 'rute_jadwals', 'jadwal_id', 'rute_id')
                    ->withPivot('urutan', 'durasi_segment', 'harga_segment')
                    ->withTimestamps();
    }

    // ★★★ RELASI BARU: Relasi ke DriverJadwal (one-to-one) ★★★
    public function driverJadwal()
    {
        return $this->hasOne(DriverJadwal::class, 'id_jadwal');
    }

    // ★★★ Relasi ke Pemesanan (one-to-many) ★★★
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'jadwal_id');
    }

    // ★★★ Scope untuk jadwal yang tersedia (belum diambil driver) ★★★
    public function scopeTersedia($query)
    {
        return $query->where(function($q) {
            $q->whereNull('status_admin')
              ->orWhere('status_admin', '!=', 'diambil');
        })
        ->where('status', 'tersedia')
        ->where('kursi_tersedia', '>', 0)
        ->whereDate('tanggal_keberangkatan', '>=', now()->toDateString());
    }

    // ★★★ Method untuk cek apakah sudah diambil ★★★
    public function sudahDiambil()
    {
        return !empty($this->status_admin) && $this->status_admin === 'diambil';
    }

    // ★★★ Method untuk diambil driver ★★★
    public function diambilOlehDriver($driverId)
    {
        // Update status admin
        $this->status_admin = 'diambil';
        $this->save();

        // Buat jadwal driver
        return DriverJadwal::createFromJadwalAdmin($this, $driverId);
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
            $stops = $rute->rute_pemberhentian;
            if (is_array($stops)) {
                foreach ($stops as $stop) {
                    $pemberhentianList[] = [
                        'kota' => $stop['kota'] ?? '',
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
}
