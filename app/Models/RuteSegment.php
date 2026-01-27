<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuteSegment extends Model
{
    use HasFactory;

    protected $table = 'rute_segments';
    
    // Tambahkan fillable jika perlu
    protected $fillable = [
        'rute_id',
        'urutan_segment',
        'outlet_id',
        'kota',
        'nama_lokasi',
        'jarak_segment',
        'jarak_kumulatif',
        'estimasi_waktu',
        'is_pickup_point',
        'is_drop_point',
        'status_aktif'
    ];

    // Relasi ke rute
    public function rute()
    {
        return $this->belongsTo(Rute::class, 'rute_id');
    }

    // Relasi ke outlet
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    /**
     * Scope untuk segment aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Scope untuk titik pickup
     */
    public function scopePickupPoint($query)
    {
        return $query->where('is_pickup_point', true);
    }

    /**
     * Scope untuk titik drop
     */
    public function scopeDropPoint($query)
    {
        return $query->where('is_drop_point', true);
    }

    /**
     * Hitung jarak antara dua outlet dalam rute yang sama
     */
    public static function hitungJarak($outletAsalId, $outletTujuanId)
    {
        // Cari segment untuk outlet asal
        $segmentAsal = self::where('outlet_id', $outletAsalId)
            ->where('is_pickup_point', true)
            ->aktif()
            ->first();
        
        // Cari segment untuk outlet tujuan
        $segmentTujuan = self::where('outlet_id', $outletTujuanId)
            ->where('is_drop_point', true)
            ->aktif()
            ->first();
        
        if (!$segmentAsal || !$segmentTujuan) {
            return 0;
        }
        
        // Pastikan dalam rute yang sama
        if ($segmentAsal->rute_id !== $segmentTujuan->rute_id) {
            return 0;
        }
        
        // Pastikan urutan benar (asal harus sebelum tujuan)
        if ($segmentAsal->urutan_segment >= $segmentTujuan->urutan_segment) {
            return 0;
        }
        
        // Hitung jarak dari kumulatif
        return $segmentTujuan->jarak_kumulatif - $segmentAsal->jarak_kumulatif;
    }

    /**
     * Dapatkan outlet tujuan yang valid berdasarkan outlet asal
     * LOGIC PENTING: Hanya outlet yang berada SETELAH outlet asal dalam urutan route
     */
    public static function getOutletTujuanValid($outletAsalId)
    {
        try {
            // 1. Cari outlet asal terlebih dahulu
            $outletAsal = Outlet::find($outletAsalId);
            if (!$outletAsal) {
                \Log::warning('Outlet asal tidak ditemukan: ' . $outletAsalId);
                return collect();
            }

            // 2. Cari semua segment yang mengandung outlet asal sebagai pickup point
            $segmentsAsal = self::where('outlet_id', $outletAsalId)
                ->where('is_pickup_point', true)
                ->where('status_aktif', true)
                ->with('rute')
                ->get();
            
            if ($segmentsAsal->isEmpty()) {
                \Log::info('Tidak ada pickup point untuk outlet asal: ' . $outletAsalId);
                return collect();
            }

            $outletTujuanList = collect();

            // 3. Untuk setiap rute yang mengandung outlet asal
            foreach ($segmentsAsal as $segmentAsal) {
                $ruteId = $segmentAsal->rute_id;
                $urutanAsal = $segmentAsal->urutan_segment;
                $jarakAsalKumulatif = $segmentAsal->jarak_kumulatif;

                // 4. Cari ALL segment drop point SETELAH outlet asal dalam rute yang sama
                $segmentsTujuan = self::where('rute_id', $ruteId)
                    ->where('urutan_segment', '>', $urutanAsal)  // HARUS lebih tinggi urutan
                    ->where('is_drop_point', true)
                    ->where('status_aktif', true)
                    ->with(['outlet.branch', 'rute'])
                    ->orderBy('urutan_segment', 'asc')  // Urutkan dari terdekat
                    ->get();

                // 5. Tambahkan setiap destination outlet ke list
                foreach ($segmentsTujuan as $segment) {
                    if ($segment->outlet) {
                        $jarak = $segment->jarak_kumulatif - $jarakAsalKumulatif;

                        // Hanya tambahkan jika ada outlet data
                        $outletTujuanList->push([
                            'id' => $segment->outlet->id,
                            'nama_outlet' => $segment->outlet->nama_outlet,
                            'kota' => $segment->outlet->branch->kota ?? 'Unknown',
                            'alamat' => $segment->outlet->alamat_lengkap,
                            'jarak_dari_asal' => round($jarak, 2),
                            'rute_id' => $ruteId,
                            'rute_nama' => $segment->rute->nama_rute ?? 'Rute Unknown',
                            'segment_id_tujuan' => $segment->id
                        ]);
                    }
                }
            }

            // 6. Hapus duplikat berdasarkan outlet ID (jika ada multiple routes)
            $unique = $outletTujuanList->unique('id')->values();

            \Log::info('Found valid destinations for outlet asal ' . $outletAsalId . ': ' . $unique->count() . ' outlets');

            return $unique;

        } catch (\Exception $e) {
            \Log::error('Error in getOutletTujuanValid: ' . $e->getMessage(), [
                'outlet_asal_id' => $outletAsalId,
                'trace' => $e->getTraceAsString()
            ]);
            return collect();
        }
    }

    /**
     * Validasi apakah dua outlet bisa dihubungkan via rute
     */
    public static function validasiRute($outletAsalId, $outletTujuanId)
    {
        $jarak = self::hitungJarak($outletAsalId, $outletTujuanId);
        return $jarak > 0;
    }
}