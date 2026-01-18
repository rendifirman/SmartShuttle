<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesanKontak extends Model
{
    protected $table = 'pesan_kontak';
    
    protected $fillable = [
        'nama_pengirim',
        'email_pengirim',
        'nomor_telepon',
        'pesan',
        'status', // ← TAMBAHKAN KOMA DI SINI!
        'kategori',
        'subjek',
        'dibaca_oleh',
        'dibaca_pada',
        'balasan',
        'dibalas_pada',
        'dibalas_oleh',
        'prioritas'
    ];
    
    protected $casts = [
        'created_at' => 'datetime:d F Y H:i',
        'dibaca_pada' => 'datetime',
        'dibalas_pada' => 'datetime'
    ];
}