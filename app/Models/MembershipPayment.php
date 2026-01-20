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
        'paid_at',
        // Paylabs fields
        'paylabs_request_id',
        'paylabs_transaction_id',
        'paylabs_response',
        'paylabs_raw_response',
        'qr_code',
        'qris_url',
        'no_virtual_account',
        'platform_trade_no',
        'nmid',
        'tid',
        'rrn',
        'payer_name',
        'payer_phone',
        'issuer_id',
        'trans_fee_rate',
        'trans_fee_amount',
        'total_trans_fee',
        'vat_fee',
        'account_no',
        'create_time',
        'expired_time',
        'success_time',
        'checkout_url',
        'deeplink',
        'fee_type'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'jumlah_transfer' => 'decimal:2',
        'waktu_kadaluarsa' => 'datetime',
        'paid_at' => 'datetime',
        'tanggal_transfer' => 'date',
        // Paylabs fields
        'paylabs_response' => 'array',
        'paylabs_raw_response' => 'array',
        'create_time' => 'datetime',
        'expired_time' => 'datetime',
        'trans_fee_rate' => 'decimal:2',
        'trans_fee_amount' => 'decimal:2',
        'total_trans_fee' => 'decimal:2',
        'vat_fee' => 'decimal:2'
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
