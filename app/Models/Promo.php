<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'promo';

    protected $fillable = [
        'kode_promo',
        'nama_promo',
        'jenis_diskon',
        'nilai_diskon',
        'maksimal_diskon',
        'minimal_pembelian',
        'min_tiket',
        'khusus_member',
        'tanggal_mulai',
        'tanggal_berakhir',
        'kuota',
        'terpakai',
        'status',
        'deskripsi',
        'pesan_error',
        'gambar',
        'tipe_promo',
        'kategori_promo'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'status' => 'boolean',
        'khusus_member' => 'boolean',
        'nilai_diskon' => 'decimal:2',
        'maksimal_diskon' => 'decimal:2',
        'minimal_pembelian' => 'decimal:2',
        'min_tiket' => 'integer',
    ];

    /**
     * Scope untuk promo yang aktif berdasarkan tanggal dan kuota
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
     * Scope untuk promo berdasarkan tipe layanan
     */
    public function scopeByServiceType($query, $type)
    {
        return $query->where('tipe_promo', $type)
                    ->orWhere('tipe_promo', 'all');
    }

    /**
     * Scope untuk promo berdasarkan kategori (keluarga, membership, umum)
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('kategori_promo', $category);
    }

    /**
     * Scope untuk promo yang bisa digunakan oleh member/non-member
     */
    public function scopeByMemberStatus($query, $isMember)
    {
        if ($isMember) {
            return $query->where(function($q) {
                $q->where('khusus_member', true)
                  ->orWhere('kategori_promo', 'umum')
                  ->orWhere('kategori_promo', 'keluarga');
            });
        }

        return $query->where('khusus_member', false)
                    ->where('kategori_promo', '!=', 'membership');
    }

    /**
     * Cek apakah promo masih valid secara umum
     */
    public function isValid(): bool
    {
        return $this->status &&
               now()->between($this->tanggal_mulai, $this->tanggal_berakhir) &&
               ($this->kuota === null || $this->terpakai < $this->kuota);
    }

    /**
     * Validasi promo berdasarkan kondisi pengguna
     */
    public function validateForUser($userData, $jumlahTiket = 1, $totalPembelian = 0): array
    {
        $isMember = $userData['membership_status'] === 'active' ?? false;

        // Validasi dasar
        if (!$this->isValid()) {
            return [
                'valid' => false,
                'message' => 'Promo tidak aktif atau sudah kadaluarsa'
            ];
        }

        // Validasi minimal pembelian
        if ($totalPembelian < $this->minimal_pembelian) {
            return [
                'valid' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($this->minimal_pembelian, 0, ',', '.')
            ];
        }

        // Validasi berdasarkan kategori promo
        switch ($this->kategori_promo) {
            case 'keluarga':
                if ($jumlahTiket < $this->min_tiket) {
                    return [
                        'valid' => false,
                        'message' => $this->pesan_error ?? "Promo keluarga hanya berlaku untuk minimal {$this->min_tiket} tiket"
                    ];
                }
                break;

            case 'membership':
                if (!$isMember) {
                    return [
                        'valid' => false,
                        'message' => $this->pesan_error ?? 'Promo membership hanya dapat digunakan oleh member'
                    ];
                }
                break;

            case 'umum':
                // Tidak ada validasi khusus
                break;
        }

        // Validasi khusus member
        if ($this->khusus_member && !$isMember) {
            return [
                'valid' => false,
                'message' => $this->pesan_error ?? 'Promo ini khusus untuk member'
            ];
        }

        // Hitung diskon jika valid
        $diskon = $this->calculateDiscount($totalPembelian);
        $totalSetelahDiskon = $totalPembelian - $diskon;

        return [
            'valid' => true,
            'message' => 'Promo valid',
            'diskon' => $diskon,
            'total_setelah_diskon' => $totalSetelahDiskon
        ];
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

    /**
     * Dapatkan promos yang eligible untuk user
     */
    public static function getEligiblePromos($userData, $jumlahTiket = 1, $totalPembelian = 0, $serviceType = 'shuttle')
    {
        $isMember = $userData['membership_status'] === 'active' ?? false;

        return self::active()
            ->byServiceType($serviceType)
            ->byMemberStatus($isMember)
            ->get()
            ->filter(function ($promo) use ($userData, $jumlahTiket, $totalPembelian) {
                $validation = $promo->validateForUser($userData, $jumlahTiket, $totalPembelian);
                return $validation['valid'];
            })
            ->values();
    }
}
