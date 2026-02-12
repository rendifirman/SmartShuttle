<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shuttle extends Model
{
    use HasFactory;

    protected $fillable = [
        'layanan_id', // TAMBAHKAN INI
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
        'total_kursi' => 'integer',
        'tahun' => 'integer',
        'nilai_asset' => 'decimal:2',
        'kelengkapan' => 'array',
        'tanggal_masuk' => 'date',
        'masa_stnk' => 'date',
        'masa_kir' => 'date',
        'masa_asuransi' => 'date'
    ];

    // ================ RELASI BARU ================

    /**
     * Relasi ke Layanan (MLayanan)
     */
    public function layanan()
    {
        return $this->belongsTo(MLayanan::class, 'layanan_id', 'id_layanan');
    }

    /**
     * Relasi ke Driver (jika ada)
     */

    /**
     * Relasi ke jadwal
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    /**
     * Relasi ke kursi terpesan melalui jadwal
     */
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
     */
    public function getLayoutKursiArrayAttribute()
    {
        if ($this->layout_kursi) {
            $layout = is_array($this->layout_kursi)
                ? $this->layout_kursi
                : json_decode($this->layout_kursi, true);

            if (is_array($layout) && !empty($layout)) {
                return $layout;
            }
        }

        return KursiTerpesan::generateLayoutKursi($this->total_kursi ?? 9);
    }

    /**
     * Mutator untuk layout_kursi
     */
    public function setLayoutKursiAttribute($value)
    {
        if (is_array($value)) {
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

            if (empty($validatedLayout)) {
                $validatedLayout = KursiTerpesan::generateLayoutKursi($this->total_kursi ?? 9);
            }

            $this->attributes['layout_kursi'] = json_encode($validatedLayout);
        } else {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $this->attributes['layout_kursi'] = $value;
            } else {
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

    // GANTI method getLayoutWithStatus DI Shuttle.php:

/**
 * Method untuk mendapatkan layout dengan status untuk jadwal tertentu
 */
public function getLayoutWithStatus($jadwalId = null)
{
    $layout = $this->layout_kursi_array;

    if ($jadwalId) {
        // HANYA ambil kursi yang sudah dipesan secara permanen (terpesan)
        $terpesan = KursiTerpesan::where('jadwal_id', $jadwalId)
            ->where('status', 'terpesan')
            ->whereHas('pemesanan', function($query) {
                // Hanya pemesanan aktif (tidak dibatalkan/expired)
                $query->whereNotIn('status', ['dibatalkan', 'expired']);
            })
            ->pluck('nomor_kursi')
            ->toArray();

        foreach ($layout as &$kursi) {
            if (in_array($kursi['nomor'], $terpesan)) {
                // KURSI SUDAH DIPESAN OLEH USER LAIN
                $kursi['status'] = 'terpesan';
                $kursi['class'] = 'sold';
                $kursi['icon'] = 'fa-lock';
                $kursi['dapat_dipilih'] = false; // TAMBAHKAN FLAG INI
            } else {
                // KURSI MASIH TERSEDIA
                $kursi['status'] = 'tersedia';
                $kursi['class'] = 'available';
                $kursi['icon'] = 'fa-check';
                $kursi['dapat_dipilih'] = true; // TAMBAHKAN FLAG INI
            }
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
     * Scope untuk shuttle berdasarkan layanan
     */
    public function scopeByLayanan($query, $layananId)
    {
        return $query->where('layanan_id', $layananId);
    }

    /**
     * Get fasilitas sebagai array
     */
    public function getFasilitasArrayAttribute()
    {
        if (empty($this->fasilitas)) {
            return [];
        }

        if (is_array($this->fasilitas)) {
            return $this->fasilitas;
        }

        if (is_string($this->fasilitas)) {
            $fasilitas = explode(',', $this->fasilitas);
            return array_map('trim', $fasilitas);
        }

        return [];
    }

    /**
     * Get dynamic seat layout
     */
    public function getDynamicSeatLayout()
    {
        $totalSeats = $this->total_kursi ?? 9;
        $rows = ceil($totalSeats / 3);
        $layout = [];

        for ($row = 1; $row <= $rows; $row++) {
            for ($col = 1; $col <= 3; $col++) {
                $currentSeatCount = count($layout);
                if ($currentSeatCount >= $totalSeats) {
                    break 2;
                }

                $colLetter = chr(64 + $col);
                $seatNumber = $row . $colLetter;

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

    /**
     * Method untuk mendapatkan layout dengan grid yang tepat
     */
    public function getSeatGrid()
    {
        $seats = $this->getDynamicSeatLayout();
        $grid = [];
        $currentRow = [];

        foreach ($seats as $index => $seat) {
            $currentRow[] = $seat;

            if (count($currentRow) === 3 || $index === count($seats) - 1) {
                $grid[] = $currentRow;
                $currentRow = [];
            }
        }

        return $grid;
    }

    /**
     * Get info layanan (helper method)
     */
    public function getLayananInfoAttribute()
    {
        if ($this->relationLoaded('layanan') && $this->layanan) {
            return [
                'id' => $this->layanan->id_layanan,
                'nama' => $this->layanan->nama_layanan,
                'kode' => $this->layanan->kode_layanan,
                'kategori' => $this->layanan->kategori_layanan
            ];
        }

        return null;
    }

    /**
     * Accessor: provide `plat_nomor` value.
     * If `plat_nomor` isn't set, fall back to stored `nomor_polisi`.
     */
    public function getPlatNomorAttribute()
    {
        // Direct attribute if present
        if (array_key_exists('plat_nomor', $this->attributes) && !empty($this->attributes['plat_nomor'])) {
            return $this->attributes['plat_nomor'];
        }

        // Fallback to nomor_polisi attribute/column
        if (array_key_exists('nomor_polisi', $this->attributes) && !empty($this->attributes['nomor_polisi'])) {
            return $this->attributes['nomor_polisi'];
        }

        // Last fallback: dynamic property
        return $this->nomor_polisi ?? null;
    }

    // ================ AUDIT RELATIONSHIPS ================

    /**
     * User who created this shuttle
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated this shuttle
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * User who deleted this shuttle
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
