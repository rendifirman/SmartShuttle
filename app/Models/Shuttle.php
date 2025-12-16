<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shuttle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_shuttle',
        'tipe_shuttle',
        'kapasitas_kursi',
        'total_kursi',
        'layout_kursi',
        'fasilitas',
        'nomor_polisi',
        'gambar_depan',
        'gambar_samping',
        'gambar_belakang',
        'gambar_interior',
        'status'
    ];

    protected $casts = [
        'layout_kursi' => 'array',
        'kapasitas_kursi' => 'integer',
        'total_kursi' => 'integer'
    ];

    // Relasi ke jadwal
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    // Relasi ke kursi terpesan
    public function kursiTerpesan()
    {
        return $this->hasManyThrough(
            KursiTerpesan::class,
            Jadwal::class,
            'shuttle_id',
            'jadwal_id'
        );
    }

    // ================ METHOD UTAMA LAYOUT STABIL ================

    /**
     * Accessor untuk layout_kursi_array
     * MENJAMIN LAYOUT TIDAK PERNAH NULL/KOSONG
     */
    public function getLayoutKursiArrayAttribute()
    {
        // Cek jika layout_kursi ada di database
        if ($this->layout_kursi) {
            $layout = is_array($this->layout_kursi)
                ? $this->layout_kursi
                : json_decode($this->layout_kursi, true);

            // Validasi: jika layout valid, return
            if (is_array($layout) && !empty($layout)) {
                return $layout;
            }
        }

        // FALLBACK: Generate layout default berdasarkan total_kursi
        return KursiTerpesan::generateLayoutKursi($this->total_kursi ?? 9);
    }

    /**
     * Mutator untuk layout_kursi
     * Memastikan layout selalu disimpan sebagai JSON valid
     */
    public function setLayoutKursiAttribute($value)
    {
        if (is_array($value)) {
            // Validasi array sebelum disimpan
            $validatedLayout = [];
            foreach ($value as $kursi) {
                if (isset($kursi['nomor']) && isset($kursi['posisi'])) {
                    $validatedLayout[] = [
                        'nomor' => $kursi['nomor'],
                        'posisi' => $kursi['posisi'],
                        'tipe' => $kursi['tipe'] ?? 'reguler',
                        'harga_tambahan' => $kursi['harga_tambahan'] ?? 0
                    ];
                }
            }

            // Jika validasi gagal, generate default
            if (empty($validatedLayout)) {
                $validatedLayout = KursiTerpesan::generateLayoutKursi($this->total_kursi ?? 9);
            }

            $this->attributes['layout_kursi'] = json_encode($validatedLayout);
        } else {
            // Jika bukan array, coba decode JSON
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $this->attributes['layout_kursi'] = $value;
            } else {
                // Jika bukan JSON valid, generate default
                $this->attributes['layout_kursi'] = json_encode(
                    KursiTerpesan::generateLayoutKursi($this->total_kursi ?? 9)
                );
            }
        }
    }

    /**
     * Method untuk menginisialisasi layout jika kosong
     */
    public function initLayoutIfEmpty()
    {
        if (empty($this->layout_kursi) || $this->layout_kursi === '[]' || $this->layout_kursi === 'null') {
            $layout = KursiTerpesan::generateLayoutKursi($this->total_kursi ?? 9);
            $this->layout_kursi = $layout;
            $this->save();
        }
        return $this;
    }

    /**
     * Method untuk mendapatkan layout dengan status untuk jadwal tertentu
     */
    public function getLayoutWithStatus($jadwalId = null)
    {
        $layout = $this->layout_kursi_array;

        if ($jadwalId) {
            // Update status berdasarkan kursi terpesan
            $terpesan = KursiTerpesan::where('jadwal_id', $jadwalId)
                ->whereIn('status', ['terpesan', 'terisi'])
                ->pluck('nomor_kursi')
                ->toArray();

            foreach ($layout as &$kursi) {
                $kursi['status'] = in_array($kursi['nomor'], $terpesan) ? 'terpesan' : 'tersedia';
            }
        }

        return $layout;
    }

    /**
     * Method untuk update total kursi dan layout
     */
    public function updateLayout($totalKursi = null)
    {
        if ($totalKursi) {
            $this->total_kursi = $totalKursi;
            $this->kapasitas_kursi = $totalKursi;
        }

        // Generate layout baru berdasarkan total kursi
        $this->layout_kursi = KursiTerpesan::generateLayoutKursi($this->total_kursi);
        $this->save();

        return $this;
    }

    /**
     * Scope untuk shuttle aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Get fasilitas sebagai array
     */
    public function getFasilitasArrayAttribute()
    {
        if (empty($this->fasilitas)) {
            return [];
        }

        $fasilitas = explode(',', $this->fasilitas);
        return array_map('trim', $fasilitas);
    }

    // Tambahkan method ini di Models/Shuttle.php
public function getDynamicSeatLayout()
{
    $totalSeats = $this->total_kursi ?? 9;
    $rows = ceil($totalSeats / 3); // 3 kursi per baris
    $layout = [];

    for ($row = 1; $row <= $rows; $row++) {
        // Kolom A, B, C untuk setiap baris
        for ($col = 1; $col <= 3; $col++) {
            // Hitung total kursi yang sudah dibuat
            $currentSeatCount = count($layout);
            if ($currentSeatCount >= $totalSeats) {
                break 2; // Keluar dari kedua loop
            }

            $colLetter = chr(64 + $col); // A, B, C
            $seatNumber = $row . $colLetter; // 1A, 1B, 1C, 2A, dst

            $layout[] = [
                'nomor' => $seatNumber,
                'posisi' => $col == 2 ? 'tengah' : ($col == 1 ? 'kiri' : 'kanan'),
                'tipe' => 'reguler',
                'harga_tambahan' => 0,
                'status' => 'tersedia'
            ];
        }
    }

    return $layout;
}

// Method untuk mendapatkan layout dengan grid yang tepat
public function getSeatGrid()
{
    $seats = $this->getDynamicSeatLayout();
    $grid = [];
    $currentRow = [];

    foreach ($seats as $index => $seat) {
        $currentRow[] = $seat;

        // Setiap 3 kursi membentuk baris baru
        if (count($currentRow) === 3 || $index === count($seats) - 1) {
            $grid[] = $currentRow;
            $currentRow = [];
        }
    }

    return $grid;
}
}
