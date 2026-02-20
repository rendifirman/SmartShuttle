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
        'driver_id',
        'tanggal_keberangkatan',
        'waktu_keberangkatan',
        'waktu_kedatangan',
        'harga_total',
        'kursi_tersedia',
        'status',
        'status_admin',
        'is_global_schedule'
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

    // Accessor untuk rute_pertama
    public function getRutePertamaAttribute()
    {
        return $this->rutes()->first();
    }

    // Accessor untuk rute_terakhir
    public function getRuteTerakhirAttribute()
    {
        return $this->rutes()->orderBy('rute_jadwals.urutan', 'desc')->first();
    }

    // Relasi ke shuttle
    public function shuttle()
    {
        return $this->belongsTo(Shuttle::class, 'shuttle_id');
    }

    // ★★★ Relasi ke Driver (untuk AUTO_ACCEPT mode) ★★★
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
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

    /**
     * ★★★ SCOPE DAN METHOD UNTUK FITUR BARU ★★★
     */

    // Scope untuk jadwal global yang tersedia untuk driver MANUAL_CONFIRM
    public function scopeJadwalGlobal($query)
    {
        return $query->where('is_global_schedule', true)
                    ->whereNull('status_admin')
                    ->where('status', 'tersedia')
                    ->where('kursi_tersedia', '>', 0)
                    ->whereDate('tanggal_keberangkatan', '>=', now()->toDateString());
    }

    // Scope untuk jadwal yang di-assign ke driver tertentu (AUTO_ACCEPT mode)
    public function scopeJadwalAssigned($query)
    {
        return $query->whereNotNull('driver_id')
                    ->where('status', 'tersedia')
                    ->where('kursi_tersedia', '>', 0)
                    ->whereDate('tanggal_keberangkatan', '>=', now()->toDateString());
    }

    // Method untuk cek apakah jadwal adalah jadwal global
    public function isGlobalSchedule()
    {
        return $this->is_global_schedule === true;
    }

    // Method untuk cek apakah jadwal sudah di-assign ke driver
    public function isAssignedToDriver()
    {
        return !is_null($this->driver_id);
    }

    // Method untuk assign jadwal ke driver (AUTO_ACCEPT mode)
    public function assignToDriver($driverId)
    {
        $this->driver_id = $driverId;
        $this->is_global_schedule = false;
        $this->status_admin = 'diambil';
        $this->save();

        // Create DriverJadwal record
        return $this->storeDriverJadwal($driverId);
    }

    // Method untuk membuat jadwal global
    public function makeGlobal()
    {
        $this->is_global_schedule = true;
        $this->driver_id = null;
        $this->status_admin = null;
        $this->save();
    }

    // Helper method untuk membuat DriverJadwal record
    public function storeDriverJadwal($driverId)
    {
        $rute = $this->rutes->first();
        $shuttle = $this->shuttle;

        $totalKursi = $shuttle ? ($shuttle->kapasitas_kursi ?? $shuttle->total_kursi ?? 0) : 0;
        $kursiTerisi = $totalKursi - $this->kursi_tersedia;

        return DriverJadwal::create([
            'id_jadwal' => $this->id,
            'id_driver' => $driverId,
            'rute' => $rute ? ($rute->nama_rute . ' (' . $rute->kota_asal . ' → ' . $rute->kota_tujuan . ')') : 'Rute Tidak Diketahui',
            'tanggal' => $this->tanggal_keberangkatan,
            'armada' => $shuttle ? $shuttle->nama_shuttle . ' (' . ($shuttle->plat_nomor ?? '-') . ')' : 'Armada Tidak Diketahui',
            'waktu_keberangkatan' => $this->waktu_keberangkatan,
            'waktu_kedatangan' => $this->waktu_kedatangan,
            'harga' => $this->harga_total,
            'total_kursi' => $totalKursi,
            'kursi_terisi' => $kursiTerisi,
            'status' => 'aktif',
            'waktu_diambil' => \Carbon\Carbon::now(),
        ]);
    }
}
