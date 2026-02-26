<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class MMasterKontak extends Model
{
    use HasFactory;

    protected $table = 'm_master_kontak';

    protected $fillable = [
        'nama_perusahaan',
        'deskripsi_singkat',
        'email_utama',
        'email_dukungan',
        'telepon_utama',
        'telepon_dukungan',
        'alamat_kantor_pusat',
        'logo',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'jam_operasional',
        'link_kebijakan_privasi',
        'link_syarat_ketentuan',
        'status'
    ];

    protected $casts = [
        'jam_operasional' => 'array'
    ];

    /**
     * Static method untuk mendapatkan data kontak
     * Prioritas: Database -> Cache -> JSON -> Default
     */
    public static function getDataKontak()
    {
        // Prioritas pertama: Database
        $kontak = self::where('status', 'active')->first();
        if ($kontak) {
            // Cache hasilnya
            Cache::put('kontak_perusahaan', $kontak->toArray(), now()->addDays(30));
            return $kontak;
        }

        // Jika tidak ada di database, cek cache
        if (Cache::has('kontak_perusahaan')) {
            $cachedData = Cache::get('kontak_perusahaan');
            return (object) $cachedData;
        }

        // Jika tidak ada di cache, cek file JSON
        $jsonPath = storage_path('app/kontak_perusahaan.json');
        if (File::exists($jsonPath)) {
            try {
                $jsonData = json_decode(File::get($jsonPath), true);
                if (isset($jsonData['kontak'])) {
                    // Cache hasilnya
                    Cache::put('kontak_perusahaan', $jsonData['kontak'], now()->addDays(30));
                    return (object) $jsonData['kontak'];
                }
            } catch (\Exception $e) {
                // Jika error baca file, lanjut ke default
            }
        }

        // Data default jika semua kosong
        return (object) [
            'id' => 1,
            'nama_perusahaan' => 'Smart Shuttle',
            'deskripsi_singkat' => 'Layanan transportasi cerdas yang siap mengantarkan Anda menjelajahi keindahan Jawa Barat dengan harga terbaik dan kenyamanan maksimal.',
            'alamat_kantor_pusat' => 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434',
            'email_utama' => 'mdcitrasolusi@gmail.com',
            'telepon_utama' => '+62 858-1122-4321',
            'email_dukungan' => 'support@smartshuttle.com',
            'telepon_dukungan' => '+62 858-1122-4321',
            'facebook_url' => '#',
            'instagram_url' => 'citrasolusi.id',
            'twitter_url' => '#',
            'link_kebijakan_privasi' => '#',
            'link_syarat_ketentuan' => '#',
            'jam_operasional' => json_encode([
                ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                ['hari' => 'Sabtu', 'jam' => '08:00 - 15:00'],
                ['hari' => 'Minggu', 'jam' => 'Tutup']
            ]),
            'status' => 'active'
        ];
    }

    /**
     * Simpan data ke JSON (untuk bypass database)
     */
    public static function saveToJson($data)
    {
        try {
            $jsonPath = storage_path('app/kontak_perusahaan.json');

            $dataToSave = [
                'kontak' => $data,
                'updated_at' => now()->toDateTimeString(),
                'updated_by' => auth()->id() ?? 'admin'
            ];

            // Simpan ke file
            File::put($jsonPath, json_encode($dataToSave, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // Update cache
            Cache::put('kontak_perusahaan', $data, now()->addDays(30));

            // Update session jika ada
            if (session()->has('kontak_perusahaan')) {
                session(['kontak_perusahaan' => $data]);
            }

            return true;

        } catch (\Exception $e) {
            \Log::error('Error saving kontak to JSON: ' . $e->getMessage());
            return false;
        }
    }
}
