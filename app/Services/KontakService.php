<?php
// app/Services/KontakService.php
namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

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
     * Save kontak data - tanpa database
     */
    public function saveKontak($data)
    {
        try {
            $dataToSave = [
                'kontak' => $data,
                'updated_at' => now()->toDateTimeString(),
                'updated_by' => auth()->id() ?? 'admin'
            ];

            // Simpan ke file
            File::put($this->jsonPath, json_encode($dataToSave, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // Clear cache
            Cache::forget('kontak_perusahaan');

            return [
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'file_path' => $this->jsonPath
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
     * Get kontak data from file or cache
     */
    private function getKontakData()
    {
        // Cek cache dulu
        if (Cache::has('kontak_perusahaan')) {
            $data = Cache::get('kontak_perusahaan');
            return $data;
        }

        // Cek file JSON
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
