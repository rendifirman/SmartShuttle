<?php
// app/Http\Controllers/Admin/KontakPerusahaanController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\KontakService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KontakPerusahaanController extends Controller
{
    protected $kontakService;

    public function __construct(KontakService $kontakService)
    {
        $this->kontakService = $kontakService;
    }

    public function index()
    {
        try {
            // Ambil data dari service
            $kontak = (array) $this->kontakService->getKontak();

            // Pastikan jam_operasional sebagai JSON string untuk form
            if (isset($kontak['jam_operasional']) && is_array($kontak['jam_operasional'])) {
                $kontak['jam_operasional'] = json_encode($kontak['jam_operasional']);
            }

            return view('admin.kontakperusahaan', compact('kontak'));

        } catch (\Exception $e) {
            // Jika error, gunakan data default
            $kontak = $this->kontakService->getDefaultData();
            if (isset($kontak['jam_operasional']) && is_array($kontak['jam_operasional'])) {
                $kontak['jam_operasional'] = json_encode($kontak['jam_operasional']);
            }
            return view('admin.kontakperusahaan', compact('kontak'));
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi data
            $validator = Validator::make($request->all(), [
                'nama_perusahaan' => 'required|string|max:255',
                'deskripsi_singkat' => 'required|string',
                'alamat_kantor_pusat' => 'required|string',
                'email_utama' => 'required|email',
                'email_dukungan' => 'nullable|email',
                'telepon_utama' => 'required|string',
                'telepon_dukungan' => 'nullable|string',
                'facebook_url' => 'nullable|string',
                'instagram_url' => 'nullable|string',
                'twitter_url' => 'nullable|string',
                'link_kebijakan_privasi' => 'nullable|string',
                'link_syarat_ketentuan' => 'nullable|string',
                'jam_operasional' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Clean URLs sebelum disimpan
            $data = $this->cleanUrls($request->all());

            // Simpan data melalui service
            $result = $this->kontakService->saveKontak($data);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kontak perusahaan berhasil disimpan!',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean URL fields
     */
    private function cleanUrls($data)
    {
        $urlFields = [
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'link_kebijakan_privasi',
            'link_syarat_ketentuan'
        ];

        foreach ($urlFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $url = trim($data[$field]);

                // Jika kosong, set ke #
                if ($url === '') {
                    $data[$field] = '#';
                    continue;
                }

                // Jika sudah #, biarkan saja
                if ($url === '#') {
                    continue;
                }

                // Jika tidak dimulai dengan http:// atau https://, tambahkan https://
                if (!preg_match('/^https?:\/\//', $url)) {
                    // Untuk sosial media, hilangkan @ di awal jika ada
                    $url = ltrim($url, '@');
                    // Tambahkan https://
                    $data[$field] = 'https://' . $url;
                }
            } else {
                // Jika field tidak ada atau null, set ke #
                $data[$field] = '#';
            }
        }

        return $data;
    }
}
