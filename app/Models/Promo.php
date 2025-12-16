<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promo';
    
    protected $fillable = [
        'kode_promo',
        'nama_promo',
        'jenis_diskon',
        'nilai_diskon',
        'maksimal_diskon',
        'minimal_pembelian',
        'tanggal_mulai',
        'tanggal_berakhir',
        'kuota',
        'terpakai',
        'status',
        'deskripsi'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    // Accessor untuk cek apakah promo aktif
    public function getIsAktifAttribute()
    {
        $today = Carbon::today();
        
        return $this->status && 
               $today->between($this->tanggal_mulai, $this->tanggal_berakhir) &&
               (!$this->kuota || $this->terpakai < $this->kuota);
    }

    // Method untuk menghitung diskon
    public function hitungDiskon($harga)
    {
        if (!$this->is_aktif || $harga < $this->minimal_pembelian) {
            return 0;
        }

        if ($this->jenis_diskon == 'persentase') {
            $diskon = $harga * ($this->nilai_diskon / 100);
            
            // Jika ada maksimal diskon
            if ($this->maksimal_diskon && $diskon > $this->maksimal_diskon) {
                return $this->maksimal_diskon;
            }
            
            return $diskon;
        } else {
            // Diskon nominal
            return $this->nilai_diskon;
        }
    }

    // Method untuk menggunakan promo
    public function gunakanPromo()
    {
        if ($this->kuota) {
            $this->terpakai += 1;
            $this->save();
        }
    }
}