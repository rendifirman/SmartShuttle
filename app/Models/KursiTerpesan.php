<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    // Relasi ke jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    // Relasi ke detail penumpang
    public function detailPenumpang()
    {
        return $this->belongsTo(DetailPenumpang::class, 'detail_penumpang_id');
    }

    // Relasi ke pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    // ================ METHOD UTAMA LAYOUT STABIL ================

    /**
     * Method untuk generate layout kursi default 3x3 (9 kursi)
     * Ini adalah layout FIX yang tidak akan berubah
     */
    public static function generateLayoutKursi($totalKursi = 9)
    {
        $rows = ceil($totalKursi / 3); // Selalu 3 kolom per baris
        $layout = [];
        $kursiCounter = 1;

        for ($row = 1; $row <= $rows; $row++) {
            for ($col = 1; $col <= 3; $col++) {
                if ($kursiCounter <= $totalKursi) {
                    $seatNumber = $row . chr(64 + $col); // Format: 1A, 1B, 1C, 2A, 2B, 2C, 3A, 3B, 3C
                    $layout[] = [
                        'nomor' => $seatNumber,
                        'posisi' => $col == 2 ? 'tengah' : ($col == 1 ? 'kiri' : 'kanan'),
                        'tipe' => 'reguler',
                        'harga_tambahan' => 0,
                        'status' => 'tersedia' // Default status
                    ];
                    $kursiCounter++;
                }
            }
        }

        return $layout;
    }

    /**
     * Method untuk mendapatkan layout dengan status terkini
     * INI YANG MEMBUAT LAYOUT TETAP STABIL
     */
    public static function getLayoutWithStatus($jadwalId, $shuttleId = null)
    {
        // 1. Ambil atau generate layout FIX dari shuttle
        $shuttle = Shuttle::find($shuttleId);

        if ($shuttle && $shuttle->layout_kursi) {
            // Pastikan layout dari shuttle sudah valid
            $layoutKursi = is_array($shuttle->layout_kursi)
                ? $shuttle->layout_kursi
                : json_decode($shuttle->layout_kursi, true);

            // Validasi: jika layout kosong atau bukan array, generate default
            if (empty($layoutKursi) || !is_array($layoutKursi)) {
                $layoutKursi = self::generateLayoutKursi($shuttle->total_kursi ?? 9);
                // Simpan layout yang baru ke database
                $shuttle->layout_kursi = json_encode($layoutKursi);
                $shuttle->save();
            }
        } else {
            // Fallback: generate layout default 9 kursi
            $layoutKursi = self::generateLayoutKursi(9);

            // Jika shuttle ditemukan tapi layout kosong, simpan ke database
            if ($shuttle) {
                $shuttle->layout_kursi = json_encode($layoutKursi);
                $shuttle->save();
            }
        }

        // 2. Reset semua status menjadi 'tersedia' terlebih dahulu
        foreach ($layoutKursi as &$kursi) {
            $kursi['status'] = 'tersedia';
        }

        // 3. Ambil kursi yang sudah terpesan dari database dengan LOCK untuk konsistensi
        $terpesan = self::where('jadwal_id', $jadwalId)
            ->whereIn('status', ['terpesan', 'terisi'])
            ->lockForUpdate()
            ->pluck('nomor_kursi')
            ->toArray();

        // 4. Update status kursi yang terpesan
        foreach ($layoutKursi as &$kursi) {
            if (in_array($kursi['nomor'], $terpesan)) {
                $kursi['status'] = 'terpesan';
            }
        }

        return $layoutKursi;
    }

    /**
     * Method untuk mendapatkan kursi yang tersedia (hanya nomor)
     */
    public static function getKursiTersedia($jadwalId)
    {
        $terpesan = self::where('jadwal_id', $jadwalId)
            ->whereIn('status', ['terpesan', 'terisi'])
            ->pluck('nomor_kursi')
            ->toArray();

        $jadwal = Jadwal::with('shuttle')->find($jadwalId);
        $totalKursi = $jadwal->shuttle->total_kursi ?? 9;
        $allSeats = self::generateLayoutKursi($totalKursi);

        $availableSeats = [];
        foreach ($allSeats as $seat) {
            if (!in_array($seat['nomor'], $terpesan)) {
                $availableSeats[] = $seat['nomor'];
            }
        }

        return $availableSeats;
    }

    /**
     * Cek apakah kursi tersedia dengan LOCK untuk mencegah race condition
     */
    public static function isKursiTersedia($jadwalId, $nomorKursi)
    {
        return !self::where('jadwal_id', $jadwalId)
            ->where('nomor_kursi', $nomorKursi)
            ->whereIn('status', ['terpesan', 'terisi'])
            ->lockForUpdate()
            ->exists();
    }

    /**
     * Pesan kursi dengan validasi ganda dan LOCK untuk mencegah double booking
     */
    public static function pesanKursi($jadwalId, $nomorKursi, $detailPenumpangId = null, $pemesananId = null)
    {
        DB::beginTransaction();

        try {
            // Validasi kursi tersedia dengan LOCK
            if (!self::isKursiTersedia($jadwalId, $nomorKursi)) {
                throw new \Exception("Kursi {$nomorKursi} sudah terpesan oleh customer lain");
            }

            $kursi = self::create([
                'jadwal_id' => $jadwalId,
                'nomor_kursi' => $nomorKursi,
                'detail_penumpang_id' => $detailPenumpangId,
                'pemesanan_id' => $pemesananId,
                'status' => 'terpesan'
            ]);

            // Update kursi tersedia di jadwal
            $jadwal = Jadwal::find($jadwalId);
            if ($jadwal) {
                $jadwal->decrement('kursi_tersedia');
            }

            DB::commit();

            return $kursi;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Batalkan pemesanan kursi dengan LOCK
     */
    public static function batalkanKursi($jadwalId, $nomorKursi)
    {
        DB::beginTransaction();

        try {
            $deleted = self::where('jadwal_id', $jadwalId)
                ->where('nomor_kursi', $nomorKursi)
                ->delete();

            // Update kursi tersedia di jadwal
            if ($deleted > 0) {
                $jadwal = Jadwal::find($jadwalId);
                if ($jadwal) {
                    $jadwal->increment('kursi_tersedia');
                }
            }

            DB::commit();

            return $deleted;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update status kursi menjadi terisi (setelah pembayaran)
     */
    public static function konfirmasiKursi($jadwalId, $nomorKursi)
    {
        return self::where('jadwal_id', $jadwalId)
            ->where('nomor_kursi', $nomorKursi)
            ->update(['status' => 'terisi']);
    }

    /**
     * Get semua kursi terpesan untuk jadwal
     */
    public static function getKursiTerpesanByJadwal($jadwalId)
    {
        return self::where('jadwal_id', $jadwalId)
            ->whereIn('status', ['terpesan', 'terisi'])
            ->get();
    }

    /**
     * Clear semua kursi terpesan untuk pemesanan (jika batal)
     */
    public static function clearKursiByPemesanan($pemesananId)
    {
        DB::beginTransaction();

        try {
            // Ambil jumlah kursi yang akan dihapus
            $kursiTerpesan = self::where('pemesanan_id', $pemesananId)->get();
            $jumlahKursi = $kursiTerpesan->count();

            // Hapus kursi terpesan
            $deleted = self::where('pemesanan_id', $pemesananId)->delete();

            // Update kursi tersedia di jadwal
            if ($deleted > 0) {
                foreach ($kursiTerpesan as $kursi) {
                    $jadwal = Jadwal::find($kursi->jadwal_id);
                    if ($jadwal) {
                        $jadwal->increment('kursi_tersedia');
                    }
                }
            }

            DB::commit();

            return $deleted;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cek apakah semua kursi dalam array tersedia
     */
    public static function areSeatsAvailable($jadwalId, $seatNumbers)
    {
        $terpesan = self::where('jadwal_id', $jadwalId)
            ->whereIn('nomor_kursi', $seatNumbers)
            ->whereIn('status', ['terpesan', 'terisi'])
            ->pluck('nomor_kursi')
            ->toArray();

        return [
            'available' => empty($terpesan),
            'terpesan_seats' => $terpesan
        ];
    }

    /**
     * Accessor untuk layout_kursi_array
     */
    public function getLayoutKursiArrayAttribute()
    {
        return $this->layout_kursi ? json_decode($this->layout_kursi, true) : [];
    }
}
