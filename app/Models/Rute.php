<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $table = 'rutes';
    protected $fillable = [
        'layanan_id',
        'master_harga_id',
        'master_tarif_id',
        'cabang_asal_id',
        'cabang_tujuan_id',
        'kode_rute',
        'nama_rute',
        'kota_asal',
        'kota_tujuan',
        'durasi',
        'jarak',
        'harga_dasar',
        'rute_pemberhentian',
        'segment_details',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'rute_pemberhentian' => 'array',
        'segment_details' => 'array'
    ];

    /**
     * Normalise rute_pemberhentian when retrieving - handle double-encoded JSON.
     */
    public function getRutePemberhentianAttribute($value)
    {
        // If already an array, return as-is
        if (is_array($value)) {
            return $value;
        }

        // If it's null or empty, return empty array
        if (is_null($value) || $value === '') {
            return [];
        }

        // Try to decode JSON string
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        // Fallback: return empty array to avoid unexpected string values
        return [];
    }

    /**
     * Ensure rute_pemberhentian is stored as JSON string in DB.
     */
    public function setRutePemberhentianAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['rute_pemberhentian'] = json_encode($value);
            return;
        }

        if (is_string($value)) {
            // If it's a JSON string, try to decode then re-encode to normalize
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $this->attributes['rute_pemberhentian'] = json_encode($decoded);
                return;
            }
            // Otherwise store an empty array JSON to avoid raw strings
            $this->attributes['rute_pemberhentian'] = json_encode([]);
            return;
        }

        // For any other type, store empty array JSON
        $this->attributes['rute_pemberhentian'] = json_encode([]);
    }

    /**
     * Relasi ke segments rute (untuk SmartSend)
     * Segments menentukan outlet mana yang bisa pickup/drop di rute ini
     */
    public function segments()
    {
        return $this->hasMany(RuteSegment::class, 'rute_id', 'id')
                    ->orderBy('urutan_segment', 'asc');
    }

    /**
     * Relasi ke master harga
     */
    public function masterHarga()
    {
        return $this->belongsTo(MasterHarga::class);
    }

    /**
     * Relasi ke master tarif (backward compatibility - single tarif)
     */
    public function masterTarif()
    {
        return $this->belongsTo(MasterTarif::class);
    }

    /**
     * Relasi many-to-many ke master tarif (multiple tariffs)
     */
    public function masterTarifs()
    {
        return $this->belongsToMany(MasterTarif::class, 'rute_master_tarif', 'rute_id', 'master_tarif_id');
    }

    /**
     * Relasi ke layanan
     */
    public function layanan()
    {
        return $this->belongsTo(MLayanan::class, 'layanan_id');
    }

    /**
     * Relasi ke cabang asal
     */
    public function cabangAsal()
    {
        return $this->belongsTo(Branch::class, 'cabang_asal_id');
    }

    /**
     * Relasi ke cabang tujuan
     */
    public function cabangTujuan()
    {
        return $this->belongsTo(Branch::class, 'cabang_tujuan_id');
    }

    /**
     * Relasi ke shipments
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Scope untuk rute aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Cari rute berdasarkan outlet asal dan tujuan
     */
    public function scopeByOutlets($query, $outletAsal, $outletTujuan)
    {
        return $query->where(function($q) use ($outletAsal) {
            $q->whereJsonContains('rute_pemberhentian', [['outlets' => [$outletAsal]]])
              ->orWhere('kota_asal', $outletAsal);
        })
        ->where(function($q) use ($outletTujuan) {
            $q->whereJsonContains('rute_pemberhentian', [['outlets' => [$outletTujuan]]])
              ->orWhere('kota_tujuan', $outletTujuan);
        });
    }

    /**
     * Hitung jarak antara dua outlet dalam rute
     */
    public function hitungJarakOutlet($outletAsalNama, $outletTujuanNama)
    {
        $pemberhentian = $this->rute_pemberhentian ?? [];

        // Ensure pemberhentian is an array
        if (!is_array($pemberhentian)) {
            $pemberhentian = [];
        }

        $foundAsal = false;
        $foundTujuan = false;
        $jarakKumulatif = 0;
        $jarakAsalKeTujuan = 0;

        foreach ($pemberhentian as $stop) {
            // Ensure stop is an array
            if (!is_array($stop)) {
                continue;
            }
            $outletsInStop = $stop['outlets'] ?? [];

            // Cek apakah outlet asal ada di stop ini
            if (in_array($outletAsalNama, $outletsInStop)) {
                $foundAsal = true;
            }

            // Jika sudah menemukan asal, mulai hitung jarak
            if ($foundAsal && !$foundTujuan) {
                $jarakAsalKeTujuan += $stop['jarak_segment'] ?? 0;
            }

            // Cek apakah outlet tujuan ada di stop ini
            if (in_array($outletTujuanNama, $outletsInStop)) {
                $foundTujuan = true;
                break; // Berhenti setelah menemukan tujuan
            }
        }

        // Jika tidak ditemukan dalam rute pemberhentian, cek kota asal/tujuan utama
        if (!$foundAsal && $this->kota_asal === $outletAsalNama) {
            $foundAsal = true;
        }

        if (!$foundTujuan && $this->kota_tujuan === $outletTujuanNama) {
            $foundTujuan = true;
            // Tambahkan jarak total jika tujuan adalah kota tujuan utama
            $jarakAsalKeTujuan = $this->jarak_total ?? 0;
        }

        return ($foundAsal && $foundTujuan) ? $jarakAsalKeTujuan : 0;
    }

    /**
     * Dapatkan outlet tujuan yang valid berdasarkan outlet asal
     */
    public static function getOutletTujuanValid($outletAsalId)
    {
        try {
            $outletAsal = Outlet::with('branch')->find($outletAsalId);
            if (!$outletAsal) {
                return collect();
            }

            $namaOutletAsal = $outletAsal->nama_outlet;
            $kotaOutletAsal = $outletAsal->branch->kota ?? null;

            $semuaRute = self::aktif()->get();
            $outletTujuanList = collect();

            foreach ($semuaRute as $rute) {
                $pemberhentian = $rute->rute_pemberhentian ?? [];

                // Pastikan pemberhentian adalah array
                if (!is_array($pemberhentian)) {
                    continue;
                }

                $foundAsal = false;

                foreach ($pemberhentian as $stop) {
                    if (!is_array($stop)) {
                        continue;
                    }

                    $outletsInStop = $stop['outlets'] ?? [];

                    // Pastikan outlets adalah array
                    if (!is_array($outletsInStop)) {
                        $outletsInStop = [];
                    }

                    // Cek apakah outlet asal ada di stop ini
                    if (in_array($namaOutletAsal, $outletsInStop)) {
                        $foundAsal = true;
                    }

                    // Jika ditemukan asal, kumpulkan outlet setelahnya
                    if ($foundAsal) {
                        foreach ($outletsInStop as $outletNama) {
                            if ($outletNama !== $namaOutletAsal && !empty($outletNama)) {
                                // Cari outlet berdasarkan nama
                                $outletTujuan = Outlet::where('nama_outlet', $outletNama)
                                    ->where('status', 'aktif')
                                    ->with('branch')
                                    ->first();

                                if ($outletTujuan &&
                                    !$outletTujuanList->contains('id', $outletTujuan->id)) {

                                    $jarak = $rute->hitungJarakOutlet($namaOutletAsal, $outletNama);

                                    $outletTujuanList->push([
                                        'id' => $outletTujuan->id,
                                        'nama_outlet' => $outletTujuan->nama_outlet,
                                        'kota' => $outletTujuan->branch->kota ?? 'Unknown',
                                        'alamat' => $outletTujuan->alamat_lengkap,
                                        'jarak_dari_asal' => $jarak > 0 ? round($jarak, 2) : rand(50, 300),
                                        'rute_nama' => $rute->nama_rute
                                    ]);
                                }
                            }
                        }
                    }
                }

                // Cek jika outlet asal adalah kota asal utama
                if (!$foundAsal && $rute->kota_asal === $kotaOutletAsal) {
                    $foundAsal = true;
                    foreach ($pemberhentian as $stop) {
                        if (!is_array($stop)) {
                            continue;
                        }

                        foreach ($stop['outlets'] ?? [] as $outletNama) {
                            if (empty($outletNama)) {
                                continue;
                            }

                            $outletTujuan = Outlet::where('nama_outlet', $outletNama)
                                ->where('status', 'aktif')
                                ->with('branch')
                                ->first();

                            if ($outletTujuan &&
                                !$outletTujuanList->contains('id', $outletTujuan->id)) {

                                $jarak = (float) ($rute->jarak ?? rand(50, 300));

                                $outletTujuanList->push([
                                    'id' => $outletTujuan->id,
                                    'nama_outlet' => $outletTujuan->nama_outlet,
                                    'kota' => $outletTujuan->branch->kota ?? 'Unknown',
                                    'alamat' => $outletTujuan->alamat_lengkap,
                                    'jarak_dari_asal' => round($jarak, 2),
                                    'rute_nama' => $rute->nama_rute
                                ]);
                            }
                        }
                    }
                }
            }

            // Hapus duplikat berdasarkan ID outlet
            return $outletTujuanList->unique('id')->values();

        } catch (\Exception $e) {
            \Log::error('Error in Rute::getOutletTujuanValid: ' . $e->getMessage(), [
                'outlet_asal_id' => $outletAsalId
            ]);
            return collect();
        }
    }

    /**
     * Cari rute yang menghubungkan dua outlet
     */
    public static function cariRuteUntukOutlet($outletAsalId, $outletTujuanId)
    {
        $outletAsal = Outlet::find($outletAsalId);
        $outletTujuan = Outlet::find($outletTujuanId);

        if (!$outletAsal || !$outletTujuan) {
            return null;
        }

        $namaOutletAsal = $outletAsal->nama_outlet;
        $namaOutletTujuan = $outletTujuan->nama_outlet;

        $rutes = self::aktif()->get();

        foreach ($rutes as $rute) {
            $jarak = $rute->hitungJarakOutlet($namaOutletAsal, $namaOutletTujuan);
            if ($jarak > 0) {
                return [
                    'rute' => $rute,
                    'jarak' => $jarak
                ];
            }
        }

        return null;
    }

    /**
     * Relasi ke rute_jadwals (pivot table untuk many-to-many dengan jadwal)
     */
    public function ruteJadwals()
    {
        return $this->hasMany(RuteJadwal::class, 'rute_id');
    }

    /**
     * Relasi many-to-many ke jadwals melalui rute_jadwals
     */
    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'rute_jadwals', 'rute_id', 'jadwal_id')
                    ->withPivot('urutan', 'durasi_segment', 'harga_segment')
                    ->withTimestamps();
    }

    /**
     * Ambil master tarif aktif yang berlaku untuk rute ini.
     * Jika ada beberapa, kembalikan yang bertipe 'reguler' jika ada, atau yang pertama.
     */
    public function getActiveMasterTarif()
    {
        $tarifs = $this->masterTarifs()->where('status', 'aktif')
                        ->where(function($q) {
                            $q->whereNull('tanggal_berlaku')
                              ->orWhere('tanggal_berlaku', '<=', now());
                        })
                        ->where(function($q) {
                            $q->whereNull('tanggal_kadaluarsa')
                              ->orWhere('tanggal_kadaluarsa', '>=', now());
                        })
                        ->get();

        if ($tarifs->isEmpty()) return null;

        // Preferensi tarif tipe 'reguler' jika ada
        $prefer = $tarifs->firstWhere('jenis_tarif', 'reguler');
        return $prefer ?? $tarifs->first();
    }

    /**
     * Accessor untuk formatted_durasi
     */
    public function getFormattedDurasiAttribute()
    {
        // Parse durasi utama ke menit
        $baseMinutes = $this->parseDurationToMinutes($this->durasi);

        // Tambahkan durasi singgah dari rute pemberhentian
        $pemberhentian = $this->rute_pemberhentian ?? [];
        $singgahMinutes = 0;
        if (is_array($pemberhentian)) {
            foreach ($pemberhentian as $stop) {
                if (isset($stop['durasi_singgah'])) {
                    $singgahMinutes += (int) $stop['durasi_singgah'];
                }
            }
        }

        $totalMinutes = $baseMinutes + $singgahMinutes;

        return $this->formatMinutesToDuration($totalMinutes);
    }

    /**
     * Parse duration string to minutes
     */
    private function parseDurationToMinutes($duration)
    {
        if (!$duration) return 0;

        $minutes = 0;

        // Parse HH:MM format (e.g., "03:30")
        if (preg_match('/^(\d{1,2}):(\d{2})$/', $duration, $matches)) {
            $hours = (int) $matches[1];
            $mins = (int) $matches[2];
            $minutes = $hours * 60 + $mins;
        }
        // Parse "X jam Y menit" or similar
        elseif (preg_match('/(\d+)\s*jam/i', $duration, $matches)) {
            $minutes += $matches[1] * 60;
            if (preg_match('/(\d+)\s*menit/i', $duration, $matches)) {
                $minutes += $matches[1];
            }
        }
        elseif (preg_match('/(\d+)\s*menit/i', $duration, $matches)) {
            $minutes += $matches[1];
        }
        // If no match, try to parse as number (assuming minutes)
        elseif (is_numeric($duration)) {
            $minutes = (int) $duration;
        }

        return $minutes;
    }

    /**
     * Format minutes to duration string
     */
    private function formatMinutesToDuration($minutes)
    {
        if ($minutes <= 0) return '0 menit';

        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        $parts = [];
        if ($hours > 0) {
            $parts[] = $hours . ' jam';
        }
        if ($mins > 0) {
            $parts[] = $mins . ' menit';
        }

        return implode(' ', $parts);
    }
}
