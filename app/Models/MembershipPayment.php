<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'discount',
        'total_amount',
        'payment_method',
        'payment_status',
        'bank_name',
        'account_number',
        'account_name',
        'bukti_pembayaran',
        'nama_pengirim',
        'tanggal_transfer',
        'jumlah_transfer',
        'catatan',
        'waktu_kadaluarsa',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'jumlah_transfer' => 'decimal:2',
        'waktu_kadaluarsa' => 'datetime',
        'paid_at' => 'datetime',
        'tanggal_transfer' => 'date'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isExpired()
    {
        return $this->waktu_kadaluarsa && $this->waktu_kadaluarsa->isPast();
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'success' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'expired' => 'secondary',
            default => 'secondary'
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'success' => 'Berhasil',
            'pending' => 'Menunggu Pembayaran',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
            default => 'Menunggu'
        };
    }

    public static function generateTransactionId()
    {
        return 'MEM' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
    }
}
