<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MProfilePerusahaan extends Model
{
    use SoftDeletes;
    
    protected $table = 'm_profile_perusahaan';
    protected $primaryKey = 'id_profile';
    
    protected $fillable = [
        'nama_perusahaan',
        'nama_dagang',
        'logo_perusahaan',
        'deskripsi_singkat',
        'visi',
        'misi',
        'alamat_kantor_pusat',
        'telepon',
        'email',
        'website',
        'background_website',
        'jam_operasional',
        'npwp',
        'nib',
        'siup',
        'tdp',
        'nomor_sertifikat_transportasi',
        'kode_izin_penyelenggaraan',
        'tanggal_berdiri',
        'nama_pendiri',
        'penanggung_jawab_utama',
        'struktur_organisasi_file',
        'struktur_organisasi_text',
        'brand_smartshuttle',
        'brand_smartsent',
        'brand_smartrent',
        'deskripsi_unit_bisnis',
        'sop_layanan_customer_file',
        'sop_layanan_customer_text',
        'kebijakan_refund_file',
        'kebijakan_refund_text',
        'kebijakan_privasi_file',
        'kebijakan_privasi_text',
        'syarat_ketentuan_file',
        'syarat_ketentuan_text',
        'link_kebijakan_refund',
        'link_kebijakan_privasi',
        'link_syarat_ketentuan',
        'status',
        'created_by',
        'updated_by'
    ];
    
    protected $dates = ['tanggal_berdiri', 'deleted_at'];
    
    protected $casts = [
        'tanggal_berdiri' => 'date'
    ];
    
    // Scope untuk data aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    // Accessor untuk logo URL
    public function getLogoUrlAttribute()
    {
        return $this->logo_perusahaan ? asset('storage/' . $this->logo_perusahaan) : null;
    }
    
    // Accessor untuk background URL
    public function getBackgroundUrlAttribute()
    {
        return $this->background_website ? asset('storage/' . $this->background_website) : null;
    }
}