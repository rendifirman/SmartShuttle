<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Shipment extends Model
{
    use HasFactory;

    protected $table = 'shipments';
    
    // ... existing code ...

    /**
     * NEW: Generate kode resi dengan format baru
     */
    public static function generateKodeResi()
    {
        $prefix = 'SS';
        $date = date('Ymd');
        
        // Cari urutan hari ini
        $lastToday = self::where('kode_resi', 'like', $prefix . '-' . $date . '-%')
            ->orderBy('kode_resi', 'desc')
            ->first();
        
        if ($lastToday) {
            $lastNumber = intval(substr($lastToday->kode_resi, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . '-' . $date . '-' . $newNumber;
    }

    /**
     * NEW: Hitung harga berdasarkan berat dan jarak (LOGIC BARU)
     */
    public static function hitungHarga($berat, $jarak)
    {
        // Harga berat: 5kg pertama = 7000, berikutnya 2000/kg
        if ($berat <= 5) {
            $hargaBerat = 7000;
        } else {
            $hargaBerat = 7000 + (($berat - 5) * 2000);
        }
        
        // Harga jarak: 2000 per 10km
        $kelipatan = ceil($jarak / 10);
        $hargaJarak = $kelipatan * 2000;
        
        $hargaTotal = $hargaBerat + $hargaJarak;
        
        return [
            'harga_berat' => $hargaBerat,
            'harga_jarak' => $hargaJarak,
            'harga_total' => $hargaTotal,
            'perhitungan_berat' => $berat <= 5 ? 
                "5 kg pertama = Rp 7.000" : 
                "5 kg pertama (Rp 7.000) + " . ($berat - 5) . " kg × Rp 2.000",
            'perhitungan_jarak' => "{$kelipatan} × 10 km × Rp 2.000"
        ];
    }

    /**
     * NEW: Cari rute untuk pengiriman baru
     */
    public static function cariRuteUntukPengiriman($outletAsalId, $outletTujuanId)
    {
        return RuteSegment::cariRute($outletAsalId, $outletTujuanId);
    }

    /**
     * NEW: Get timeline untuk tracking display
     */
    public function getTimelineAttribute()
    {
        $timeline = [];
        
        // Status dasar
        $timeline[] = [
            'status' => 'paket_dibuat',
            'label' => 'Paket Dibuat',
            'waktu' => $this->created_at,
            'icon' => 'fa-file-alt',
            'color' => 'info'
        ];
        
        // Tracking histories
        foreach ($this->trackingHistories as $tracking) {
            $timeline[] = [
                'status' => $tracking->status,
                'label' => $tracking->status_label,
                'waktu' => $tracking->waktu_status,
                'icon' => $this->getTimelineIcon($tracking->status),
                'color' => $this->getTimelineColor($tracking->status),
                'location' => $tracking->outlet->nama_outlet ?? '',
                'kota' => $tracking->outlet->branch->kota ?? ''
            ];
        }
        
        // Urutkan berdasarkan waktu
        usort($timeline, function($a, $b) {
            return $a['waktu'] <=> $b['waktu'];
        });
        
        return $timeline;
    }

    private function getTimelineIcon($status)
    {
        $icons = [
            'paket_diterima' => 'fa-store',
            'paket_diproses' => 'fa-cogs',
            'paket_dalam_perjalanan' => 'fa-truck-moving',
            'paket_sampai_outlet' => 'fa-warehouse',
            'paket_siap_diambil' => 'fa-box-open',
            'paket_terkirim' => 'fa-check-circle',
            'paket_batal' => 'fa-times-circle'
        ];
        
        return $icons[$status] ?? 'fa-circle';
    }
    
    private function getTimelineColor($status)
    {
        $colors = [
            'paket_diterima' => 'primary',
            'paket_diproses' => 'info',
            'paket_dalam_perjalanan' => 'warning',
            'paket_sampai_outlet' => 'success',
            'paket_siap_diambil' => 'info',
            'paket_terkirim' => 'success',
            'paket_batal' => 'danger'
        ];
        
        return $colors[$status] ?? 'secondary';
    }
}