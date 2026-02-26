<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmartRent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'smart_rent_transactions';

    protected $fillable = [
        'kode_booking',
        'customer_id',
        'nama_pelanggan',
        'telepon',
        'email',
        'alamat',
        'no_identitas',
        'jenis_identitas',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi',
        'kota_asal',
        'kota_tujuan',
        'armada_id',
        'layanan',
        'jumlah_mobil',
        'metode_pembayaran',
        'total_bayar',
        'status',
        'penumpang',
        'catatan',
        'petugas',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'penumpang' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function armada()
    {
        return $this->belongsTo(SmartRentArmada::class, 'armada_id');
    }

    public function shuttle()
    {
        return $this->belongsTo(Shuttle::class, 'armada_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
