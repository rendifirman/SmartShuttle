<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class MasterHarga extends Model
{
    use HasFactory;

    protected $table = 'master_harga';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'kode_harga',
        'nama_harga',
        'jenis_harga',
        'harga_dasar',
        'harga_per_satuan',
        'satuan',
        'minimal_nilai',
        'maksimal_nilai',
        'berat_pertama',
        'harga_berat_pertama',
        'harga_berat_berikutnya',
        'kelipatan_jarak',
        'harga_per_kelipatan',
        'tanggal_berlaku',
        'tanggal_kadaluarsa',
        'status_aktif'
    ];

    /**
     * Scope untuk data aktif - hanya jika kolomnya ada
     */
    public function scopeAktif($query)
    {
        // Cek apakah kolom yang dibutuhkan ada
        if (Schema::hasColumn($this->getTable(), 'status_aktif')) {
            $query = $query->where('status_aktif', 1);
        }
        
        if (Schema::hasColumn($this->getTable(), 'tanggal_berlaku')) {
            $query = $query->where('tanggal_berlaku', '<=', now());
        }
        
        if (Schema::hasColumn($this->getTable(), 'tanggal_kadaluarsa')) {
            $query = $query->where(function($q) {
                $q->whereNull('tanggal_kadaluarsa')
                  ->orWhere('tanggal_kadaluarsa', '>=', now());
            });
        }
        
        return $query;
    }

    /**
     * Scope untuk jenis - gunakan kolom 'jenis_harga' yang sesuai dengan migration
     */
    public function scopeJenis($query, $jenis)
    {
        // Gunakan kolom jenis_harga sesuai dengan migration
        if (Schema::hasColumn($this->getTable(), 'jenis_harga')) {
            return $query->where('jenis_harga', $jenis);
        } elseif (Schema::hasColumn($this->getTable(), 'jenis')) {
            return $query->where('jenis', $jenis);
        }
        
        // Jika tidak ada kolom filter, return query asli
        return $query;
    }

    /**
     * Method aman untuk mengambil data dengan fallback
     */
    public static function getAktif()
    {
        try {
            return self::aktif()->first();
        } catch (\Exception $e) {
            \Log::warning('Error mengambil master harga aktif: ' . $e->getMessage());
            return self::first(); // Ambil data pertama tanpa filter
        }
    }
}