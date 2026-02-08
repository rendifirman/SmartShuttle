<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MVehicle extends Model
{
    use HasFactory;
    
    protected $table = 'm_vehicles';
    
    protected $fillable = [
        'nama_kendaraan',
        'tipe_kendaraan',
        'merk',
        'tahun',
        'nomor_plat',
        'warna',
        'kapasitas_penumpang',
        'kapasitas_bagasi',
        'transmisi',
        'ac',
        'harga_sewa_per_hari',
        'harga_sewa_per_minggu',
        'harga_sewa_per_bulan',
        'gambar_kendaraan',
        'deskripsi',
        'status',
        'lokasi'
    ];
    
    protected $casts = [
        'ac' => 'boolean',
        'harga_sewa_per_hari' => 'decimal:2',
        'harga_sewa_per_minggu' => 'decimal:2',
        'harga_sewa_per_bulan' => 'decimal:2',
    ];
}