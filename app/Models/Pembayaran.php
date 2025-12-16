<?php

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
        'nama_bank',
        'instruksi_pembayaran',
        'waktu_kadaluarsa',
        'waktu_pembayaran'
    ];

    protected $casts = [
        'waktu_kadaluarsa' => 'datetime',
        'waktu_pembayaran' => 'datetime',
        'jumlah' => 'decimal:2'
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

    // Scope untuk pembayaran aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'menunggu')
                    ->where('waktu_kadaluarsa', '>', now());
    }

    // Generate kode pembayaran
    public static function generateKodePembayaran()
    {
        $prefix = 'PAY';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        
        $kode = $prefix . $date . $random;
        
        // Cek unik
        while (self::where('kode_pembayaran', $kode)->exists()) {
            $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
            $kode = $prefix . $date . $random;
        }
        
        return $kode;
    }

    // Cek apakah pembayaran sudah kadaluarsa
    public function getIsKadaluarsaAttribute()
    {
        return $this->waktu_kadaluarsa && $this->waktu_kadaluarsa < now();
    }

    // Format status
    public function getStatusTextAttribute()
    {
        $statuses = [
            'menunggu' => 'Menunggu Pembayaran',
            'diproses' => 'Pembayaran Diproses',
            'berhasil' => 'Pembayaran Berhasil',
            'gagal' => 'Pembayaran Gagal',
            'kadaluarsa' => 'Pembayaran Kadaluarsa'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }
}