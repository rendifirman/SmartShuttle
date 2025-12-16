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
        'status'
    ];
    
    protected $casts = [
        'created_at' => 'datetime:d F Y H:i',
    ];
}