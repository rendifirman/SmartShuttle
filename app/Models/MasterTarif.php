<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTarif extends Model
{
    use HasFactory;

    protected $table = 'master_tarif';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_tarif',
        'nama_tarif',
        'jenis_tarif',
        'sk_tarif',
        'harga_dasar',
        'harga_minimum',
        'harga_maksimum',
        'diskon_persentase',
        'diskon_nominal',
        'biaya_tambahan',
        'keterangan_biaya_tambahan',
        'keterangan',
        'catatan',
        'tanggal_berlaku',
        'tanggal_kadaluarsa',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
        'tanggal_kadaluarsa' => 'date',
        'harga_dasar' => 'decimal:2',
        'harga_minimum' => 'decimal:2',
        'harga_maksimum' => 'decimal:2',
        'diskon_persentase' => 'decimal:2',
        'diskon_nominal' => 'decimal:2',
    ];

    /**
     * Relasi ke Rute (many-to-many)
     */
    public function rutes()
    {
        return $this->belongsToMany(Rute::class, 'rute_master_tarif', 'master_tarif_id', 'rute_id');
    }

    /**
     * Relasi ke Rute (backward compatibility - single tarif)
     */
    public function ruteSingle()
    {
        return $this->hasMany(Rute::class, 'master_tarif_id');
    }

    /**
     * Relasi ke DriverJadwal
     */
    public function driverJadwals()
    {
        return $this->hasMany(DriverJadwal::class, 'master_tarif_id');
    }

    /**
     * Scope untuk tarif aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')
                     ->where(function($q) {
                         $q->whereNull('tanggal_berlaku')
                           ->orWhere('tanggal_berlaku', '<=', now());
                     })
                     ->where(function($q) {
                         $q->whereNull('tanggal_kadaluarsa')
                           ->orWhere('tanggal_kadaluarsa', '>=', now());
                     });
    }

    /**
     * Scope untuk tarif berdasarkan jenis
     */
    public function scopeByJenisTarif($query, $jenis)
    {
        return $query->where('jenis_tarif', $jenis);
    }

    /**
     * Hitung total tarif dengan diskon dan biaya tambahan
     */
    public function hitungTarif($hargaAwal)
    {
        $harga = $hargaAwal;

        // Terapkan diskon persentase
        if ($this->diskon_persentase > 0) {
            $harga -= ($harga * $this->diskon_persentase / 100);
        }

        // Terapkan diskon nominal
        if ($this->diskon_nominal > 0) {
            $harga -= $this->diskon_nominal;
        }

        // Tambahkan biaya tambahan
        if ($this->biaya_tambahan > 0) {
            $harga += $this->biaya_tambahan;
        }

        // Pastikan tidak kurang dari harga minimum
        if ($harga < $this->harga_minimum) {
            $harga = $this->harga_minimum;
        }

        // Pastikan tidak lebih dari harga maksimum
        if ($this->harga_maksimum && $harga > $this->harga_maksimum) {
            $harga = $this->harga_maksimum;
        }

        return max(0, $harga);
    }

    /**
     * Format tarif untuk ditampilkan
     */
    public function formatTarif()
    {
        return [
            'id' => $this->id,
            'kode' => $this->kode_tarif,
            'nama' => $this->nama_tarif,
            'jenis' => $this->jenis_tarif,
            'sk' => $this->sk_tarif,
            'harga_dasar' => $this->harga_dasar,
            'harga_minimum' => $this->harga_minimum,
            'harga_maksimum' => $this->harga_maksimum,
            'diskon_persentase' => $this->diskon_persentase,
            'diskon_nominal' => $this->diskon_nominal,
            'biaya_tambahan' => $this->biaya_tambahan,
            'keterangan_biaya_tambahan' => $this->keterangan_biaya_tambahan,
            'status' => $this->status,
            'berlaku_dari' => $this->tanggal_berlaku?->format('d-m-Y'),
            'berlaku_sampai' => $this->tanggal_kadaluarsa?->format('d-m-Y'),
        ];
    }
}
