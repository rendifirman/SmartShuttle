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
        'keterangan',
        'status'
    ];
    
    protected $casts = [
        'tanggal_keberangkatan' => 'date',
        'waktu_keberangkatan' => 'datetime:H:i',
        'waktu_kedatangan' => 'datetime:H:i',
        'harga_total' => 'decimal:2',
        'kursi_tersedia' => 'integer'
    ];
    
    /**
     * Relasi ke Shuttle
     */

    public function rutes()
    {
        return $this->belongsToMany(Rute::class, 'rute_jadwals', 'jadwal_id', 'rute_id')
            ->withTimestamps()
            ->withPivot(['urutan']); // tambahkan jika ada kolom tambahan di pivot
    }

    public function shuttle()
    {
        return $this->belongsTo(Shuttle::class, 'shuttle_id');
    }
    
    /**
     * Relasi ke layanan melalui shuttle
     */
    public function layanan()
    {
        return $this->hasOneThrough(
            MLayanan::class,
            Shuttle::class,
            'id', // Foreign key pada tabel shuttles
            'id_layanan', // Foreign key pada tabel layanans
            'shuttle_id', // Local key pada tabel jadwals
            'layanan_id' // Local key pada tabel shuttles
        );
    }
    
    /**
     * Format harga ke Rupiah
     */
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga_total, 0, ',', '.');
    }
    
    /**
     * Format tanggal Indonesia
     */
    public function getFormattedTanggalAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal_keberangkatan)->translatedFormat('d F Y');
    }
    
    /**
     * Hitung durasi perjalanan
     */
    public function getDurasiAttribute()
    {
        $departure = \Carbon\Carbon::parse($this->waktu_keberangkatan);
        $arrival = \Carbon\Carbon::parse($this->waktu_kedatangan);
        
        // Jika waktu tiba lebih kecil dari waktu berangkat, berarti melewati tengah malam
        if ($arrival->lessThan($departure)) {
            $arrival->addDay();
        }
        
        $duration = $departure->diff($arrival);
        
        if ($duration->i == 0) {
            return $duration->h . ' jam';
        }
        
        return $duration->h . ' jam ' . $duration->i . ' menit';
    }
    
    /**
     * Cek apakah hampir penuh (kursi tersedia <= 20% kapasitas)
     */
    public function isAlmostFull()
    {
        if ($this->shuttle && $this->shuttle->kapasitas > 0) {
            $percentage = ($this->kursi_tersedia / $this->shuttle->kapasitas) * 100;
            return $percentage <= 20;
        }
        return false;
    }
    
    /**
     * Cek apakah penuh
     */
    public function isFull()
    {
        return $this->kursi_tersedia <= 0;
    }
    
    /**
     * Hitung kursi terisi
     */
    public function getKursiTerisiAttribute()
    {
        if ($this->shuttle && $this->shuttle->kapasitas) {
            return $this->shuttle->kapasitas - $this->kursi_tersedia;
        }
        return 0;
    }
    
    /**
     * Hitung persentase terisi
     */
    public function getFillPercentageAttribute()
    {
        if ($this->shuttle && $this->shuttle->kapasitas > 0) {
            return round(($this->kursi_terisi / $this->shuttle->kapasitas) * 100, 1);
        }
        return 0;
    }
}