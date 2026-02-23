<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    use HasFactory;

    protected $table = 'shipment_tracking';
    protected $primaryKey = 'id';

    protected $fillable = [
        'shipment_id',
        'rute_segment_id',
        'outlet_id',
        'status',
        'deskripsi',
        'catatan',
        'foto_bukti',
        'updated_by',
        'updated_by_role',
        'waktu_status',
    ];

    protected $casts = [
        'waktu_status' => 'datetime',
    ];

    // Relasi ke shipment
    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    // Relasi ke segment
    public function segment()
    {
        return $this->belongsTo(RuteSegment::class, 'rute_segment_id');
    }

    // Relasi ke outlet
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    // Relasi ke user yang update
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Format deskripsi status
     */
    public function getDeskripsiLengkapAttribute()
    {
        $deskripsi = $this->deskripsi;
        
        if ($this->outlet) {
            $deskripsi .= " di " . $this->outlet->nama_outlet . " (" . $this->outlet->kota . ")";
        }
        
        if ($this->segment) {
            $deskripsi .= " [Segment " . $this->segment->urutan_segment . ": " . $this->segment->nama_lokasi . "]";
        }
        
        return $deskripsi;
    }

    /**
     * Status label
     */
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'paket_diterima' => 'Paket Diterima',
            'paket_diproses' => 'Paket Diproses',
            'paket_dalam_perjalanan' => 'Dalam Perjalanan',
            'paket_sampai_outlet' => 'Sampai di Outlet',
            'paket_siap_diambil' => 'Siap Diambil',
            'paket_diambil_kurir' => 'Diambil Kurir',
            'paket_diantar' => 'Sedang Diantar',
            'paket_terkirim' => 'Terkirim',
            'paket_batal' => 'Dibatalkan',
        ];
        
        return $statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Buat tracking otomatis saat update status shipment
     */
    public static function buatTrackingOtomatis($shipmentId, $status, $userId = null, $catatan = null)
    {
        $shipment = Shipment::find($shipmentId);
        
        if (!$shipment) {
            return null;
        }
        
        // Tentukan deskripsi berdasarkan status
        $deskripsi = self::generateDeskripsi($status, $shipment);
        
        // Buat tracking record
        return self::create([
            'shipment_id' => $shipmentId,
            'status' => $status,
            'deskripsi' => $deskripsi,
            'catatan' => $catatan,
            'updated_by' => $userId,
            'updated_by_role' => $userId ? 'admin' : 'system',
            'waktu_status' => now(),
        ]);
    }

    /**
     * Generate deskripsi otomatis
     */
    private static function generateDeskripsi($status, $shipment)
    {
        $deskripsiMap = [
            'paket_diterima' => 'Paket diterima di outlet asal',
            'paket_diproses' => 'Paket sedang diproses untuk pengiriman',
            'paket_dalam_perjalanan' => 'Paket sedang dalam perjalanan menuju outlet tujuan',
            'paket_sampai_outlet' => 'Paket telah sampai di outlet tujuan',
            'paket_siap_diambil' => 'Paket siap untuk diambil oleh penerima',
            'paket_diambil_kurir' => 'Paket telah diambil oleh kurir untuk pengantaran',
            'paket_diantar' => 'Paket sedang diantar ke alamat penerima',
            'paket_terkirim' => 'Paket telah berhasil diterima oleh penerima',
            'paket_batal' => 'Pengiriman paket dibatalkan',
        ];
        
        return $deskripsiMap[$status] ?? 'Status diperbarui';
    }
}