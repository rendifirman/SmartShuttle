<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    
    protected $fillable = [
        'pembayaran_id',
        'pemesanan_id',
        'kode_transaksi',
        'jumlah',
        'biaya_admin',
        'total',
        'catatan',
        'bukti_pembayaran',
        'waktu_transaksi'
    ];

    protected $casts = [
        'waktu_transaksi' => 'datetime',
        'jumlah' => 'decimal:2',
        'biaya_admin' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }

    // Relasi ke pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    // Generate kode transaksi
    public static function generateKodeTransaksi()
    {
        $prefix = 'TRX';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        
        $kode = $prefix . $date . $random;
        
        // Cek unik
        while (self::where('kode_transaksi', $kode)->exists()) {
            $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
            $kode = $prefix . $date . $random;
        }
        
        return $kode;
    }
}