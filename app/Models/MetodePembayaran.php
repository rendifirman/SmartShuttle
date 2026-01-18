<?php
// app/Models/MetodePembayaran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran';

    protected $fillable = [
        'nama',
        'kode',
        'jenis',
        'deskripsi',
        'biaya_admin',
        'estimasi_waktu',
        'instruksi',
        'aktif',
        'is_paylabs',
        'paylabs_channel_code',
        'paylabs_channel_name',
        'gambar',
        'urutan',
        'product_info',
        'fee_type'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'is_paylabs' => 'boolean',
        'biaya_admin' => 'decimal:2',
        'estimasi_waktu' => 'integer',
        'urutan' => 'integer',
        'instruksi' => 'array',
        'product_info' => 'array'
    ];

    // Scope untuk metode aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Get formatted admin fee
    public function getFormattedBiayaAdminAttribute()
    {
        return number_format($this->biaya_admin, 0, ',', '.');
    }

    // Get instruksi as array
    public function getInstruksiArrayAttribute()
    {
        if (is_string($this->instruksi)) {
            return json_decode($this->instruksi, true) ?? [];
        }
        return $this->instruksi ?? [];
    }

    // Relationship to payments
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'metode', 'kode');
    }
}
