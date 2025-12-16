<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MMasterKontak extends Model
{
    protected $table = 'master_kontak';
    
    protected $fillable = [
        'nama_perusahaan',
        'deskripsi_singkat',
        'email_utama',
        'email_dukungan',
        'telepon_utama',
        'telepon_dukungan',
        'alamat_kantor_pusat',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'jam_operasional',
        'link_kebijakan_privasi',
        'link_syarat_ketentuan',
        'status'
    ];
    
    protected $casts = [
        'jam_operasional' => 'array',
    ];
    
    // Method untuk mengambil data kontak aktif
    public static function getDataKontak()
    {
        return self::where('status', 'active')->first();
    }
}