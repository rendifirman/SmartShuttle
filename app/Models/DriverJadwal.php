<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DriverJadwal extends Model
{
    use HasFactory;

    protected $table = 'driver_jadwals';
    protected $primaryKey = 'id_jadwal_driver';

    protected $fillable = [
        'id_jadwal',
        'rute_id',
        'id_driver',
        'rute',
        'tanggal',
        'armada',
        'waktu_keberangkatan',
        'waktu_kedatangan',
        'harga',
        'master_tarif_id',
        'total_kursi',
        'kursi_terisi',
        'status',
        'waktu_diambil'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_diambil' => 'datetime',
        'harga' => 'decimal:2',
        'total_kursi' => 'integer',
        'kursi_terisi' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Jadwal (Admin)
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id');
    }

    /**
     * Relasi ke Driver (User)
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_driver', 'id');
    }

    /**
     * Relasi ke Rute (master rute)
     */
    public function masterRute(): BelongsTo
    {
        return $this->belongsTo(Rute::class, 'rute_id', 'id');
    }

    /**
     * Relasi ke Master Tarif
     */
    public function masterTarif(): BelongsTo
    {
        return $this->belongsTo(MasterTarif::class, 'master_tarif_id', 'id');
    }

    /**
     * ========================
     * STATIC METHODS
     * ========================
     */

    /**
     * Membuat DriverJadwal dari Jadwal Admin (ketika admin langsung menetapkan driver)
     */
    public static function createFromJadwalAdmin(Jadwal $jadwal, $driverId)
    {
        // Ambil rute pertama dari jadwal
        $rute = $jadwal->rutes->first();
        $shuttle = $jadwal->shuttle;

        // Hitung total kursi dari shuttle
        $totalKursi = $shuttle ? ($shuttle->kapasitas_kursi ?? $shuttle->total_kursi ?? 0) : 0;

        // Hitung kursi yang sudah terisi
        $kursiTerisi = $totalKursi - $jadwal->kursi_tersedia;

        // Buat record di driver_jadwals
        return self::create([
            'id_jadwal' => $jadwal->id,
            'id_driver' => $driverId,
            'rute' => $rute ? ($rute->nama_rute . ' (' . $rute->kota_asal . ' → ' . $rute->kota_tujuan . ')') : 'Rute Tidak Diketahui',
            'tanggal' => $jadwal->tanggal_keberangkatan,
            'armada' => $shuttle ? $shuttle->nama_shuttle . ' (' . ($shuttle->plat_nomor ?? '-') . ')' : 'Armada Tidak Diketahui',
            'waktu_keberangkatan' => $jadwal->waktu_keberangkatan,
            'waktu_kedatangan' => $jadwal->waktu_kedatangan,
            'harga' => $jadwal->harga_total,
            'total_kursi' => $totalKursi,
            'kursi_terisi' => $kursiTerisi,
            'status' => 'aktif',
            'waktu_diambil' => Carbon::now(),
        ]);
    }

    /**
     * ========================
     * ACCESSORS (GETTERS)
     * ========================
     */

    /**
     * Accessor untuk format tanggal
     */
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->translatedFormat('d F Y') : '-';
    }

    /**
     * Accessor untuk format tanggal singkat
     */
    public function getTanggalSingkatAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->format('d/m/Y') : '-';
    }

    /**
     * Accessor untuk format waktu keberangkatan
     */
    public function getWaktuBerangkatFormattedAttribute()
    {
        return $this->waktu_keberangkatan ? date('H:i', strtotime($this->waktu_keberangkatan)) : '-';
    }

    /**
     * Accessor untuk format waktu kedatangan
     */
    public function getWaktuTibaFormattedAttribute()
    {
        return $this->waktu_kedatangan ? date('H:i', strtotime($this->waktu_kedatangan)) : '-';
    }

    /**
     * Accessor untuk format harga
     */
    public function getHargaFormattedAttribute()
    {
        return $this->harga ? 'Rp ' . number_format($this->harga, 0, ',', '.') : 'Rp 0';
    }

    /**
     * Accessor untuk kursi tersedia (alias sisa kursi)
     */
    public function getKursiTersediaAttribute()
    {
        return $this->total_kursi - $this->kursi_terisi;
    }

    /**
     * Accessor untuk sisa kursi (alias kursi tersedia)
     */
    public function getSisaKursiAttribute()
    {
        return $this->total_kursi - $this->kursi_terisi;
    }

    /**
     * Accessor untuk persentase kursi terisi
     */
    public function getPersentaseTerisiAttribute()
    {
        if ($this->total_kursi == 0) {
            return 0;
        }
        return round(($this->kursi_terisi / $this->total_kursi) * 100, 2);
    }

    /**
     * Accessor untuk status kursi
     */
    public function getStatusKursiAttribute()
    {
        $persentase = $this->persentase_terisi;

        if ($persentase >= 100) {
            return 'penuh';
        } elseif ($persentase >= 80) {
            return 'hampir penuh';
        } else {
            return 'tersedia';
        }
    }

    /**
     * Accessor untuk format waktu diambil
     */
    public function getWaktuDiambilFormattedAttribute()
    {
        return $this->waktu_diambil ? Carbon::parse($this->waktu_diambil)->format('d/m/Y H:i') : '-';
    }

    /**
     * Accessor untuk waktu diambil relatif
     */
    public function getWaktuDiambilRelatifAttribute()
    {
        return $this->waktu_diambil ? Carbon::parse($this->waktu_diambil)->diffForHumans() : '-';
    }

    /**
     * Accessor untuk badge status jadwal
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'aktif':
                return '<span class="badge bg-success">Aktif</span>';
            case 'selesai':
                return '<span class="badge bg-secondary">Selesai</span>';
            case 'dibatalkan':
                return '<span class="badge bg-danger">Dibatalkan</span>';
            default:
                return '<span class="badge bg-info">' . $this->status . '</span>';
        }
    }

    /**
     * Accessor untuk warna status
     */
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'aktif':
                return 'success';
            case 'selesai':
                return 'secondary';
            case 'dibatalkan':
                return 'danger';
            default:
                return 'info';
        }
    }

    /**
     * Accessor untuk rute string (untuk display di blade)
     */
    public function getRuteStringAttribute()
    {
        return $this->rute ?? 'Rute Tidak Diketahui';
    }

    /**
     * Accessor untuk shuttle virtual object dari armada field
     * Memberikan akses ke $jadwal->shuttle->nama_shuttle, dll
     */
    public function getShuttleAttribute()
    {
        return (object) [
            'nama_shuttle' => $this->armada ?? 'Armada Standar',
            'tipe_shuttle' => $this->armada ?? 'Shuttle',
            'kapasitas_kursi' => $this->total_kursi ?? 12,
            'fasilitas_array' => ['AC', 'WiFi', 'USB Charger'],
            'gambar_depan' => 'shuttle-front.jpg',
            'gambar_samping' => 'shuttle-side.jpg',
            'gambar_belakang' => 'shuttle-back.jpg',
            'gambar_interior' => 'shuttle-interior.jpg',
        ];
    }

    /**
     * Accessor untuk kota asal (parsing dari string rute)
     */
    public function getKotaAsalAttribute()
    {
        if (preg_match('/\(([^→]+)→/', $this->rute, $matches)) {
            return trim($matches[1]);
        }
        return '-';
    }

    /**
     * Accessor untuk kota tujuan (parsing dari string rute)
     */
    public function getKotaTujuanAttribute()
    {
        if (preg_match('/→([^)]+)\)/', $this->rute, $matches)) {
            return trim($matches[1]);
        }
        return '-';
    }

    /**
     * ========================
     * SCOPES (QUERY BUILDERS)
     * ========================
     */

    /**
     * Scope untuk jadwal aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk jadwal tersedia untuk customer
     */
    public function scopeTersediaUntukCustomer(Builder $query)
    {
        return $query->where('status', 'aktif')
            ->whereColumn('kursi_terisi', '<', 'total_kursi')
            ->where('tanggal', '>=', now()->toDateString());
    }

    /**
     * Scope untuk jadwal berdasarkan driver
     */
    public function scopeByDriver($query, $driverId)
    {
        return $query->where('id_driver', $driverId);
    }

    /**
     * Scope untuk jadwal bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year);
    }

    /**
     * Scope untuk pencarian jadwal
     */
    public function scopeSearch($query, $params)
    {
        if (isset($params['rute'])) {
            $query->where('rute', 'like', '%' . $params['rute'] . '%');
        }

        if (isset($params['tanggal'])) {
            $query->where('tanggal', $params['tanggal']);
        }

        if (isset($params['harga_min'])) {
            $query->where('harga', '>=', $params['harga_min']);
        }

        if (isset($params['harga_max'])) {
            $query->where('harga', '<=', $params['harga_max']);
        }

        if (isset($params['waktu_keberangkatan'])) {
            $query->whereTime('waktu_keberangkatan', '>=', $params['waktu_keberangkatan']);
        }

        return $query;
    }

    /**
     * ========================
     * METHODS
     * ========================
     */

    /**
     * Get detail rute sebagai array
     */
    public function getDetailRute()
    {
        try {
            // Ambil detail rute dari jadwal yang terkait
            if ($this->jadwal && $this->jadwal->rutes) {
                $rute = $this->jadwal->rutes->first();
                if ($rute) {
                    return [
                        'kota_asal' => $rute->kota_asal,
                        'kota_tujuan' => $rute->kota_tujuan,
                        'nama_rute' => $rute->nama_rute
                    ];
                }
            }

            // Fallback: Parse dari kolom rute string
            $ruteString = $this->rute;
            if (strpos($ruteString, '→') !== false) {
                $parts = explode('→', $ruteString);
                return [
                    'kota_asal' => trim($parts[0] ?? ''),
                    'kota_tujuan' => trim($parts[1] ?? ''),
                    'nama_rute' => $ruteString
                ];
            }

            // Fallback: Parsing format: "Rute Name (Kota Asal → Kota Tujuan)"
            if (preg_match('/\(([^→]+)→([^)]+)\)/', $this->rute, $matches)) {
                return [
                    'nama_rute' => trim($this->rute),
                    'kota_asal' => trim($matches[1]),
                    'kota_tujuan' => trim($matches[2])
                ];
            }

            return [
                'kota_asal' => $this->kota_asal,
                'kota_tujuan' => $this->kota_tujuan,
                'nama_rute' => $this->rute ?? ''
            ];
        } catch (\Exception $e) {
            return [
                'kota_asal' => '',
                'kota_tujuan' => '',
                'nama_rute' => $this->rute ?? ''
            ];
        }
    }

    /**
     * Cek apakah jadwal tersedia untuk customer
     */
    public function isAvailableForCustomer()
    {
        return $this->status === 'aktif' &&
               $this->tanggal >= now()->toDateString() &&
               $this->sisa_kursi > 0;
    }

    /**
     * Update kursi terisi
     */
    public function updateKursiTerisi($jumlah)
    {
        $this->kursi_terisi += $jumlah;

        if ($this->kursi_terisi >= $this->total_kursi) {
            $this->status = 'selesai';
        }

        $this->save();

        if ($this->jadwal) {
            $this->jadwal->kursi_tersedia = $this->total_kursi - $this->kursi_terisi;

            if ($this->jadwal->kursi_tersedia <= 0) {
                $this->jadwal->status = 'penuh';
            }

            $this->jadwal->save();
        }

        return $this;
    }

    /**
     * Cek kursi tersedia
     */
    public function cekKursiTersedia()
    {
        return $this->total_kursi - $this->kursi_terisi;
    }

    /**
     * Update status
     */
    public function updateStatus($status)
    {
        $validStatuses = ['aktif', 'selesai', 'dibatalkan'];

        if (in_array($status, $validStatuses)) {
            $this->status = $status;
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Format untuk response API
     */
    public function toApiResponse()
    {
        return [
            'id_jadwal_driver' => $this->id_jadwal_driver,
            'id_jadwal' => $this->id_jadwal,
            'id_driver' => $this->id_driver,
            'rute' => $this->rute,
            'kota_asal' => $this->kota_asal,
            'kota_tujuan' => $this->kota_tujuan,
            'tanggal' => $this->tanggal->format('Y-m-d'),
            'tanggal_formatted' => $this->tanggal_formatted,
            'tanggal_singkat' => $this->tanggal_singkat,
            'armada' => $this->armada,
            'waktu_keberangkatan' => $this->waktu_keberangkatan,
            'waktu_keberangkatan_formatted' => $this->waktu_berangkat_formatted,
            'waktu_kedatangan' => $this->waktu_kedatangan,
            'waktu_kedatangan_formatted' => $this->waktu_tiba_formatted,
            'harga' => (float) $this->harga,
            'harga_formatted' => $this->harga_formatted,
            'total_kursi' => $this->total_kursi,
            'kursi_terisi' => $this->kursi_terisi,
            'kursi_tersedia' => $this->kursi_tersedia,
            'sisa_kursi' => $this->sisa_kursi,
            'persentase_terisi' => $this->persentase_terisi,
            'status_kursi' => $this->status_kursi,
            'status' => $this->status,
            'status_color' => $this->status_color,
            'status_badge' => $this->status_badge,
            'waktu_diambil' => $this->waktu_diambil ? $this->waktu_diambil->format('Y-m-d H:i:s') : null,
            'waktu_diambil_formatted' => $this->waktu_diambil_formatted,
            'waktu_diambil_relatif' => $this->waktu_diambil_relatif,
            'available_for_customer' => $this->isAvailableForCustomer(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
