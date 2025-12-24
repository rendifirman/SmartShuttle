<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'deskripsi',
        'gambar',
        'tipe_promo'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'status' => 'boolean',
        'nilai_diskon' => 'decimal:2',
        'maksimal_diskon' => 'decimal:2',
        'minimal_pembelian' => 'decimal:2',
    ];

    /**
     * Scope untuk promo yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', true)
                    ->whereDate('tanggal_mulai', '<=', now())
                    ->whereDate('tanggal_berakhir', '>=', now())
                    ->where(function($q) {
                        $q->whereNull('kuota')
                          ->orWhereRaw('terpakai < kuota');
                    });
    }

    /**
     * Scope untuk promo berdasarkan tipe
     */
    public function scopeByType($query, $type)
    {
        return $query->where('tipe_promo', $type)
                    ->orWhere('tipe_promo', 'all');
    }

    /**
     * Cek apakah promo masih valid
     */
    public function isValid(): bool
    {
        return $this->status &&
               now()->between($this->tanggal_mulai, $this->tanggal_berakhir) &&
               ($this->kuota === null || $this->terpakai < $this->kuota);
    }

    /**
     * Hitung diskon berdasarkan total pembelian
     */
    public function calculateDiscount($total): float
    {
        if (!$this->isValid() || $total < $this->minimal_pembelian) {
            return 0;
        }

        $discount = 0;

        if ($this->jenis_diskon === 'persentase') {
            $discount = ($total * $this->nilai_diskon) / 100;
            if ($this->maksimal_diskon && $discount > $this->maksimal_diskon) {
                $discount = $this->maksimal_diskon;
            }
        } else {
            $discount = $this->nilai_diskon;
        }

        return $discount;
    }

    /**
     * Alias bahasa Indonesia untuk calculateDiscount
     */
    public function hitungDiskon($total): float
    {
        return $this->calculateDiscount($total);
    }

    /**
     * Tambah jumlah terpakai
     */
    public function incrementUsed(): bool
    {
        if ($this->kuota && $this->terpakai >= $this->kuota) {
            return false;
        }

        $this->increment('terpakai');
        return true;
    }
}
