<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class KursiTerpesan extends Model
{
    use HasFactory;

    protected $table = 'kursi_terpesan';

    protected $fillable = [
        'jadwal_id',
        'nomor_kursi',
        'detail_penumpang_id',
        'pemesanan_id',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ================ RELASI ================
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function detailPenumpang()
    {
        return $this->belongsTo(DetailPenumpang::class, 'detail_penumpang_id');
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    // ================ METHOD UTAMA ================

    /**
     * Method untuk generate layout kursi default 3x3 (9 kursi)
     */
    public static function generateLayoutKursi($totalKursi = 9)
    {
        $rows = ceil($totalKursi / 3);
        $layout = [];
        $kursiCounter = 1;

        for ($row = 1; $row <= $rows; $row++) {
            for ($col = 1; $col <= 3; $col++) {
                if ($kursiCounter <= $totalKursi) {
                    $seatNumber = $row . chr(64 + $col);
                    $layout[] = [
                        'nomor' => $seatNumber,
                        'posisi' => $col == 2 ? 'tengah' : ($col == 1 ? 'kiri' : 'kanan'),
                        'tipe' => 'reguler',
                        'harga_tambahan' => 0,
                        'status' => 'tersedia'
                    ];
                    $kursiCounter++;
                }
            }
        }

        return $layout;
    }

    /**
     * Method untuk mendapatkan layout dengan status terkini
     * INCLUDES REAL-TIME SEAT LOCKING
     */
    public static function getLayoutWithStatus($jadwalId, $shuttleId = null, $pemesananId = null)
    {
        // 1. Ambil atau generate layout FIX dari shuttle
        $shuttle = Shuttle::find($shuttleId);
        $layoutKursi = [];

        if ($shuttle && !empty($shuttle->layout_kursi)) {
            $layoutKursi = is_array($shuttle->layout_kursi)
                ? $shuttle->layout_kursi
                : json_decode($shuttle->layout_kursi, true);

            if (empty($layoutKursi) || !is_array($layoutKursi)) {
                $layoutKursi = self::generateLayoutKursi($shuttle->total_kursi ?? 9);
            }
        } else {
            $layoutKursi = self::generateLayoutKursi(9);
        }

        // 2. Reset semua status menjadi 'tersedia' terlebih dahulu
        foreach ($layoutKursi as &$kursi) {
            $kursi['status'] = 'tersedia';
            $kursi['class'] = 'available';
            $kursi['icon'] = 'fa-check';
        }

        // 3. **AMBIL SEMUA STATUS KURSI: terpesan, dipilih (locked)**
        $allKursiStatus = self::where('jadwal_id', $jadwalId)
            ->with('pemesanan')
            ->get();

        // 4. Update status kursi berdasarkan data real-time
        foreach ($layoutKursi as &$kursi) {
            $kursiStatus = $allKursiStatus->where('nomor_kursi', $kursi['nomor'])->first();

            if ($kursiStatus) {
                if ($kursiStatus->status === 'terpesan') {
                    // Kursi sudah dipesan final
                    $kursi['status'] = 'terpesan';
                    $kursi['class'] = 'sold';
                    $kursi['icon'] = 'fa-lock';
                } elseif ($kursiStatus->status === 'dipilih') {
                    // Kursi sedang dipilih (locked)
                    if ($pemesananId && $kursiStatus->pemesanan_id == $pemesananId) {
                        // Ini kursi saya yang sedang saya pilih
                        $kursi['status'] = 'selected';
                        $kursi['class'] = 'selected';
                        $kursi['icon'] = 'fa-user-check';
                    } else {
                        // Ini kursi yang sedang dipilih user lain
                        $kursi['status'] = 'dikunci';
                        $kursi['class'] = 'sold';
                        $kursi['icon'] = 'fa-user-clock';
                    }
                }
            }
        }

        return $layoutKursi;
    }

    /**
     * **METHOD BARU: Cek kursi dengan validasi pemesanan aktif dan lock**
     */
    public static function isKursiTersediaWithValidation($jadwalId, $nomorKursi, $excludePemesananId = null)
    {
        $query = self::where('jadwal_id', $jadwalId)
            ->where('nomor_kursi', $nomorKursi)
            ->where(function($query) {
                $query->where('status', 'terpesan')
                      ->orWhere(function($q) {
                          $q->where('status', 'dipilih')
                            ->where('updated_at', '>', now()->subMinutes(5));
                      });
            })
            ->whereHas('pemesanan', function($query) {
                $query->whereNotIn('status', ['dibatalkan', 'expired']);
            });

        // Jika ada pemesanan yang dikecualikan (untuk edit)
        if ($excludePemesananId) {
            $query->where('pemesanan_id', '!=', $excludePemesananId);
        }

        return !$query->exists();
    }

    /**
     * **METHOD BARU: Validasi multiple seats sekaligus dengan lock**
     */
    public static function validateMultipleSeatsWithPemesanan($jadwalId, $seatNumbers, $pemesananId = null)
    {
        // Use join instead of whereHas for better performance and reliability
        $terpesan = self::join('pemesanan', 'kursi_terpesan.pemesanan_id', '=', 'pemesanan.id')
            ->where('kursi_terpesan.jadwal_id', $jadwalId)
            ->whereIn('kursi_terpesan.nomor_kursi', $seatNumbers)
            ->where('kursi_terpesan.status', 'terpesan')
            ->whereNotIn('pemesanan.status', ['dibatalkan', 'expired'])
            ->pluck('kursi_terpesan.nomor_kursi')
            ->toArray();

        // Removed real-time locking check since we don't use it
        $tidakTersedia = $terpesan;

        return [
            'available' => array_diff($seatNumbers, $tidakTersedia),
            'booked' => $terpesan,
            'locked' => []
        ];
    }

    /**
     * Pesan kursi dengan TRANSACTION dan validasi ganda
     */
    public static function pesanKursi($jadwalId, $nomorKursi, $detailPenumpangId = null, $pemesananId = null)
    {
        DB::beginTransaction();

        try {
            // **VALIDASI GANDA: Cek dengan validasi pemesanan aktif**
            if (!self::isKursiTersediaWithValidation($jadwalId, $nomorKursi, $pemesananId)) {
                throw new \Exception("Kursi {$nomorKursi} sudah terpesan atau sedang dipilih");
            }

            // **HAPUS KUNCI JIKA ADA**
            self::where('jadwal_id', $jadwalId)
                ->where('nomor_kursi', $nomorKursi)
                ->where('status', 'dipilih')
                ->delete();

            $kursiTerpesan = self::create([
                'jadwal_id' => $jadwalId,
                'nomor_kursi' => $nomorKursi,
                'detail_penumpang_id' => $detailPenumpangId,
                'pemesanan_id' => $pemesananId,
                'status' => 'terpesan'
            ]);

            // Clear cache
            Cache::forget('kursi_jadwal_' . $jadwalId);

            DB::commit();

            return $kursiTerpesan;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error booking seat: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get semua kursi terpesan untuk jadwal
     */
    public static function getKursiTerpesanByJadwal($jadwalId)
    {
        return self::where('jadwal_id', $jadwalId)
            ->where('status', 'terpesan')
            ->whereHas('pemesanan', function($query) {
                $query->whereNotIn('status', ['dibatalkan', 'expired']);
            })
            ->get();
    }

    /**
     * Get semua kursi yang sedang dikunci (dipilih) untuk jadwal
     */
    public static function getKursiDikunciByJadwal($jadwalId)
    {
        return self::where('jadwal_id', $jadwalId)
            ->where('status', 'dipilih')
            ->where('updated_at', '>', now()->subMinutes(5))
            ->get();
    }

    /**
     * **METHOD BARU: Update kursi untuk pemesanan dengan lock**
     */
    public static function updateKursiForPemesanan($pemesananId, $jadwalId, $selectedSeats)
    {
        DB::beginTransaction();

        try {
            // 1. Hapus semua kunci yang dimiliki pemesanan ini
            self::where('pemesanan_id', $pemesananId)
                ->where('status', 'dipilih')
                ->delete();

            // 2. Hapus kursi lama untuk pemesanan ini (jika ada)
            self::where('pemesanan_id', $pemesananId)
                ->where('status', 'terpesan')
                ->delete();

            // 3. Pesan kursi baru dengan locking
            $detailPenumpang = DetailPenumpang::where('pemesanan_id', $pemesananId)->get();

            foreach ($detailPenumpang as $index => $penumpang) {
                $nomorKursi = $selectedSeats[$index] ?? null;

                if ($nomorKursi) {
                    // Gunakan lock untuk memastikan tidak ada race condition
                    self::lockAndPesanKursi(
                        $jadwalId,
                        $nomorKursi,
                        $penumpang->id,
                        $pemesananId
                    );

                    // Update nomor kursi di detail penumpang
                    $penumpang->update(['nomor_kursi' => $nomorKursi]);
                }
            }

            // 4. Clear cache
            Cache::forget('kursi_jadwal_' . $jadwalId);
            Cache::forget('pemesanan_' . $pemesananId . '_kursi');

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating seats for booking: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * **METHOD BARU: Cek apakah pemesanan sudah memiliki kursi**
     */
    public static function pemesananSudahPunyaKursi($pemesananId)
    {
        return self::where('pemesanan_id', $pemesananId)
            ->where('status', 'terpesan')
            ->exists();
    }

    /**
     * **METHOD BARU: Get kursi untuk pemesanan**
     */
    public static function getKursiForPemesanan($pemesananId)
    {
        return self::where('pemesanan_id', $pemesananId)
            ->whereIn('status', ['terpesan', 'dipilih', 'terisi'])
            ->pluck('nomor_kursi')
            ->toArray();
    }

    /**
     * **METHOD BARU: Clear kursi untuk pemesanan**
     */
    public static function clearKursiForPemesanan($pemesananId)
    {
        DB::beginTransaction();

        try {
            $kursiTerpesan = self::where('pemesanan_id', $pemesananId)->get();

            // Reset nomor kursi di detail penumpang
            DetailPenumpang::where('pemesanan_id', $pemesananId)
                ->update(['nomor_kursi' => null]);

            $deleted = self::where('pemesanan_id', $pemesananId)->delete();

            // Clear cache
            foreach ($kursiTerpesan as $kursi) {
                Cache::forget('kursi_jadwal_' . $kursi->jadwal_id);
            }
            Cache::forget('pemesanan_' . $pemesananId . '_kursi');

            DB::commit();

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error clearing seats: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * **METHOD BARU: Validasi kursi sebelum proses dengan lock**
     */
    public static function validateBeforeProses($jadwalId, $seatNumbers, $pemesananId = null)
    {
        $validation = self::validateMultipleSeatsWithPemesanan($jadwalId, $seatNumbers, $pemesananId);

        if (!empty($validation['booked'])) {
            throw new \Exception(
                "Kursi " . implode(', ', $validation['booked']) .
                " sudah terpesan oleh pemesanan lain yang aktif"
            );
        }

        if (!empty($validation['locked'])) {
            throw new \Exception(
                "Kursi " . implode(', ', $validation['locked']) .
                " sedang dipilih oleh user lain"
            );
        }

        return true;
    }

    /**
     * **METHOD BARU: Hapus kunci yang expired**
     */
    public static function clearExpiredLocks()
    {
        return self::where('status', 'dipilih')
            ->where('updated_at', '<', now()->subMinutes(5))
            ->delete();
    }

    // ================ METHOD LAINNYA ================

    public static function isKursiTersedia($jadwalId, $nomorKursi)
    {
        return self::isKursiTersediaWithValidation($jadwalId, $nomorKursi);
    }

    public static function getKursiTersedia($jadwalId)
    {
        $tidakTersedia = self::where('jadwal_id', $jadwalId)
            ->where(function($query) {
                $query->where('status', 'terpesan')
                      ->orWhere(function($q) {
                          $q->where('status', 'dipilih')
                            ->where('updated_at', '>', now()->subMinutes(5));
                      });
            })
            ->whereHas('pemesanan', function($query) {
                $query->whereNotIn('status', ['dibatalkan', 'expired']);
            })
            ->pluck('nomor_kursi')
            ->toArray();

        $jadwal = Jadwal::with('shuttle')->find($jadwalId);
        $totalKursi = $jadwal->shuttle->total_kursi ?? 9;
        $allSeats = self::generateLayoutKursi($totalKursi);

        $availableSeats = [];
        foreach ($allSeats as $seat) {
            if (!in_array($seat['nomor'], $tidakTersedia)) {
                $availableSeats[] = $seat['nomor'];
            }
        }

        return $availableSeats;
    }

    public static function batalkanKursi($jadwalId, $nomorKursi)
    {
        return self::where('jadwal_id', $jadwalId)
            ->where('nomor_kursi', $nomorKursi)
            ->delete();
    }

    public static function konfirmasiKursi($jadwalId, $nomorKursi)
    {
        return self::where('jadwal_id', $jadwalId)
            ->where('nomor_kursi', $nomorKursi)
            ->update(['status' => 'terisi']);
    }

    public static function clearKursiByPemesanan($pemesananId)
    {
        return self::where('pemesanan_id', $pemesananId)->delete();
    }

    public static function getKursiTerpesanByPemesanan($pemesananId)
    {
        return self::where('pemesanan_id', $pemesananId)->get();
    }

    /**
     * Mark all seats for a pemesanan as booked (terpesan) when payment is completed
     */
    public static function markSeatsAsBooked($pemesananId)
    {
        return self::where('pemesanan_id', $pemesananId)
            ->whereIn('status', ['dipilih', 'terisi'])
            ->update([
                'status' => 'terpesan',
                'updated_at' => now()
            ]);
    }
}
