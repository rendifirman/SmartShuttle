<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    
    protected $fillable = [
        'kode_booking',
        'customer_id',
        'jadwal_id',
        'jumlah_penumpang',
        'harga_total',
        'diskon',
        'total_bayar',
        'nama_pemesan',
        'telepon_pemesan',
        'email_pemesan',
        'catatan',
        'status',
        'waktu_kadaluarsa',
        'metode_pembayaran',
        'bukti_pembayaran',
        'tanggal_pembayaran',
        'waktu_pembayaran',
        'status_pembayaran'
    ];

    protected $casts = [
        'waktu_kadaluarsa' => 'datetime',
        'tanggal_pembayaran' => 'date',
        'waktu_pembayaran' => 'datetime',
        'harga_total' => 'integer',
        'diskon' => 'integer',
        'total_bayar' => 'integer',
        'jumlah_penumpang' => 'integer'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Relasi ke jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    // Relasi ke detail penumpang
    public function detailPenumpang()
    {
        return $this->hasMany(DetailPenumpang::class, 'pemesanan_id');
    }

    // Relasi ke kursi terpesan
    public function kursiTerpesan()
    {
        return $this->hasMany(KursiTerpesan::class, 'pemesanan_id');
    }

    // Relasi ke transaksi
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'pemesanan_id');
    }

    // Scope untuk pemesanan aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'menunggu_pembayaran')
                    ->where('waktu_kadaluarsa', '>', now());
    }

    // Scope untuk riwayat
    public function scopeRiwayat($query, $userId)
    {
        return $query->where('customer_id', $userId)
                    ->orderBy('created_at', 'desc');
    }

    // Cek apakah pemesanan sudah kadaluarsa
    public function getIsKadaluarsaAttribute()
    {
        return $this->waktu_kadaluarsa && $this->waktu_kadaluarsa < now();
    }

    // Generate kode booking
    public static function generateKodeBooking()
    {
        $prefix = 'SS';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        
        $kode = $prefix . $date . $random;
        
        // Cek unik
        while (self::where('kode_booking', $kode)->exists()) {
            $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
            $kode = $prefix . $date . $random;
        }
        
        return $kode;
    }

    // Format status
    public function getStatusTextAttribute()
    {
        $statuses = [
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'kadaluarsa' => 'Kadaluarsa'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    // Format total bayar
    public function getTotalBayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_bayar, 0, ',', '.');
    }
    
    public function outletAsal()
    {
        return $this->belongsTo(Outlet::class, 'outlet_asal_id', 'id_outlet');
    }
    
    public function outletTujuan()
    {
        return $this->belongsTo(Outlet::class, 'outlet_tujuan_id', 'id_outlet');
    }

    // Tambahan: Format tanggal untuk e-ticket
    public function getTanggalFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->jadwal->tanggal_keberangkatan)->isoFormat('dddd, D MMMM YYYY');
    }

    // Tambahan: Format waktu untuk e-ticket
    public function getWaktuFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->jadwal->waktu_keberangkatan)->format('H:i') . ' WIB';
    }
}