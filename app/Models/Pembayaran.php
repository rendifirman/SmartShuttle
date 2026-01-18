<?php
// app/Models/Pembayaran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pemesanan_id',
        'kode_pembayaran',
        'jumlah',
        'metode',
        'status',
        'no_virtual_account',
        'qr_code',
        'qris_url',
        'nama_bank',
        'instruksi_pembayaran',
        'waktu_kadaluarsa',
        'waktu_pembayaran',
        // Paylabs fields
        'paylabs_transaction_id',
        'paylabs_merchant_id',
        'paylabs_store_id',
        'paylabs_request_id',
        'paylabs_payment_code',
        'paylabs_response',
        'paylabs_raw_response',
        'paylabs_status',
        'nmid',
        'platform_trade_no',
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
        'success_time',
        'expired_time'
    ];

    protected $casts = [
        'waktu_kadaluarsa' => 'datetime',
        'waktu_pembayaran' => 'datetime',
        'jumlah' => 'decimal:2',
        'trans_fee_rate' => 'decimal:6',
        'trans_fee_amount' => 'decimal:2',
        'total_trans_fee' => 'decimal:2',
        'vat_fee' => 'decimal:2'
    ];

    // Relasi ke pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    // Relasi ke transaksi
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'pembayaran_id');
    }

    // Get product info as array
    public function getProductInfoAttribute()
    {
        $defaultProduct = [
            [
                'id' => '1',
                'name' => 'Smart Shuttle Ticket',
                'price' => (string) $this->jumlah,
                'type' => 'Ticket',
                'url' => url('/customer/detail-pemesanan/' . ($this->pemesanan->kode_booking ?? '')),
                'quantity' => $this->pemesanan->jumlah_penumpang ?? 1
            ]
        ];

        if ($this->pemesanan) {
            $rutePertama = $this->pemesanan->jadwal->rutes->first();
            $ruteTerakhir = $this->pemesanan->jadwal->rutes->last();

            $defaultProduct[0]['name'] = 'Ticket: ' . ($rutePertama->kota_asal ?? 'Origin') . ' to ' . ($ruteTerakhir->kota_tujuan ?? 'Destination');
        }

        return $defaultProduct;
    }

    // Get formatted amount
    public function getFormattedAmountAttribute()
    {
        return number_format($this->jumlah, 2, '.', '');
    }
}
