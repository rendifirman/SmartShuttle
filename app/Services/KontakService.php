<?php
// app/Services/KontakService.php
namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\MMasterKontak;

class KontakService
{
    protected $jsonPath;

    public function __construct()
    {
        $this->jsonPath = storage_path('app/kontak_perusahaan.json');
    }

    /**
     * Get kontak data - tanpa database
     */
    public function getKontak()
    {
        return (object) $this->getKontakData();
    }

    /**
     * Save kontak data to database and JSON file
     */
    public function saveKontak($data)
    {
        try {
            // Prepare data for database
            $dbData = $data;
            $dbData['status'] = 'active';

            // Save to database - find first active record or create new one
            $existingKontak = MMasterKontak::where('status', 'active')->first();

            if ($existingKontak) {
                // Update existing record
                $existingKontak->update($dbData);
                $kontak = $existingKontak;
            } else {
                // Create new record
                $dbData['status'] = 'active';
                $kontak = MMasterKontak::create($dbData);
            }

            // ---- sync back to profile perusahaan so admin profile and contact stay in sync ----
            try {
                $profile = \App\Models\MProfilePerusahaan::first();
                if (!$profile) {
                    $profile = new \App\Models\MProfilePerusahaan();
                    $profile->created_by = Auth::id() ?? 'admin';
                }

                $profile->fill([
                    'nama_perusahaan' => $dbData['nama_perusahaan'] ?? $profile->nama_perusahaan,
                    'deskripsi_singkat' => $dbData['deskripsi_singkat'] ?? $profile->deskripsi_singkat,
                    'alamat_kantor_pusat' => $dbData['alamat_kantor_pusat'] ?? $profile->alamat_kantor_pusat,
                    'telepon' => $dbData['telepon_utama'] ?? $profile->telepon,
                    'email' => $dbData['email_utama'] ?? $profile->email,
                    'link_kebijakan_privasi' => $dbData['link_kebijakan_privasi'] ?? $profile->link_kebijakan_privasi,
                    'link_syarat_ketentuan' => $dbData['link_syarat_ketentuan'] ?? $profile->link_syarat_ketentuan,
                    'updated_by' => Auth::id() ?? 'admin',
                ]);
                $profile->save();
                // clear cache in case profile data is cached elsewhere
                Cache::forget('profile_perusahaan_data');
            } catch (\Exception $e) {
                // log and continue; contact save must not fail
                \Log::warning('Failed syncing kontak to profile: ' . $e->getMessage());
            }

            // Prepare data for JSON file
            $dataToSave = [
                'kontak' => $data,
                'updated_at' => now()->toDateTimeString(),
                'updated_by' => Auth::id() ?? 'admin'
            ];

            // Simpan ke file
            File::put($this->jsonPath, json_encode($dataToSave, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // Clear cache untuk memastikan data terbaru
            Cache::forget('kontak_perusahaan');
            Cache::forget('master_kontak_data');

            return [
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'file_path' => $this->jsonPath,
                'database_id' => $kontak->id
            ];

        } catch (\Exception $e) {
            \Log::error('Error saving kontak: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get kontak data from database, cache, or file
     */
    private function getKontakData()
    {
        // Cek cache dulu
        if (Cache::has('kontak_perusahaan')) {
            $data = Cache::get('kontak_perusahaan');
            return $data;
        }

        // Prioritas pertama: Database
        $kontak = MMasterKontak::where('status', 'active')->first();
        if ($kontak) {
            $data = $kontak->toArray();
            // Cache hasilnya
            Cache::put('kontak_perusahaan', $data, now()->addDays(30));
            return $data;
        }

        // Jika tidak ada di database, cek file JSON
        if (File::exists($this->jsonPath)) {
            try {
                $data = json_decode(File::get($this->jsonPath), true);
                if (isset($data['kontak'])) {
                    // Cache hasilnya
                    Cache::put('kontak_perusahaan', $data['kontak'], now()->addDays(30));
                    return $data['kontak'];
                }
            } catch (\Exception $e) {
                \Log::error('Error reading kontak file: ' . $e->getMessage());
             }
        }

        // Return default data
        return $this->getDefaultData();
    }

    /**
     * Default data
     */
    public function getDefaultData()
    {
        return [
            'id' => 1,
            'nama_perusahaan' => 'Smart Shuttle',
            'deskripsi_singkat' => 'Layanan transportasi cerdas yang siap mengantarkan Anda menjelajahi keindahan Jawa Barat dengan harga terbaik dan kenyamanan maksimal.',
            'alamat_kantor_pusat' => 'Ruko Citra Grand CBD, Jl. Alternatif Cibubur – Cileungsi No.KM. 5 ER 01 No 02, Jatirangga, Kec. Jatisampurna, Kota Bks, Jawa Barat 17434',
            'email_utama' => 'mdcitrasolusi@gmail.com',
            'telepon_utama' => '+62 858-1122-4321',
            'email_dukungan' => 'support@smartshuttle.com',
            'telepon_dukungan' => '+62 858-1122-4321',
            'facebook_url' => '#',
            'instagram_url' => '#',
            'twitter_url' => '#',
            'link_kebijakan_privasi' => '#',
            'link_syarat_ketentuan' => '#',
            'jam_operasional' => [
                ['hari' => 'Senin - Jumat', 'jam' => '08:00 - 17:00'],
                ['hari' => 'Sabtu', 'jam' => '08:00 - 15:00'],
                ['hari' => 'Minggu', 'jam' => 'Tutup']
            ],
            'status' => 'active'
        ];
    }

    /**
     * Helper untuk format URL sosial media
     */
    public function formatSocialUrl($url, $platform)
    {
        if (!$url || $url === '#') return '#';

        // Jika sudah full URL
        if (strpos($url, 'http') === 0) return $url;

        // Format berdasarkan platform
        $platformUrls = [
            'facebook' => 'https://facebook.com/',
            'instagram' => 'https://instagram.com/',
            'twitter' => 'https://twitter.com/',
        ];

        if (isset($platformUrls[$platform])) {
            // Hapus @ jika ada
            $username = ltrim($url, '@');
            return $platformUrls[$platform] . $username;
        }

        return $url;
    }

    /**
     * Format WhatsApp number
     */
    public function formatWhatsApp($phone)
    {
        if (!$phone || $phone === '#') return '';
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return '62' . ltrim($phone, '0');
    }

    /**
     * Check if URL is valid (not empty and not #)
     */
    public function isValidUrl($url)
    {
        return $url && $url !== '#';
    }
}
