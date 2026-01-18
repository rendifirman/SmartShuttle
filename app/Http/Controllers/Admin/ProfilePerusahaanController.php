<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MProfilePerusahaan;
use App\Models\MLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProfilePerusahaanController extends Controller
{
    public function index()
    {
        // Ambil data perusahaan (asumsi hanya 1 record)
        $profile = MProfilePerusahaan::first();

        // Jika belum ada data, buat data default dan simpan ke database
        if (!$profile) {
            $profile = new MProfilePerusahaan([
                'nama_perusahaan' => 'PT. Smart Shuttle Indonesia',
                'nama_dagang' => 'Smart Shuttle Group',
                'deskripsi_singkat' => 'Smart Shuttle adalah solusi transportasi cerdas yang menghubungkan berbagai kota dan mempermudah mobilitas masyarakat dengan layanan yang cepat dan terpercaya.',
                'alamat_kantor_pusat' => 'Jl. Sudirman No. 45, Jakarta Selatan',
                'telepon' => '(021) 555-1234',
                'email' => 'info@smartshuttle.co.id',
                'website' => 'www.smartshuttle.co.id',
                'visi' => 'Menjadi perusahaan terdepan di Indonesia dalam menyediakan solusi mobilitas dan logistik yang inovatif dan berkelanjutan demi kemudahan masyarakat.',
                'misi' => '• Menyediakan layanan transportasi dan logistik yang cepat, aman, dan ramah lingkungan.
• Mengoptimalkan penggunaan teknologi untuk meningkatkan efisiensi dan kepuasan pelanggan.
• Membangun jaringan luas untuk mendukung mobilitas masyarakat di seluruh Indonesia.
• Mengedepankan keselamatan dan kenyamanan dalam setiap layanan.',
                'npwp' => '01.234.567.8-901.000',
                'kode_izin_penyelenggaraan' => 'KIP-56789-XYZ',
                'siup' => 'SIUP-2024-12345',
                'nib' => '1234567890123',
                'nomor_sertifikat_transportasi' => 'TRNS-00012345',
                'tdp' => 'TDP-2024-98765',
                'tanggal_berdiri' => '2025-11-10',
                'penanggung_jawab_utama' => 'Dr. Rina Dewi',
                'nama_pendiri' => 'Ir. Agus Santoso',
                'link_kebijakan_refund' => 'https://smartshuttle.co.id/refund-policy',
                'link_kebijakan_privasi' => 'https://smartshuttle.co.id/privacy-policy',
                'link_syarat_ketentuan' => 'https://smartshuttle.co.id/terms',
                'link_bantuan' => 'https://smartshuttle.co.id/bantuan',
                'link_faq' => 'https://smartshuttle.co.id/faq',
                'status' => 'active',
                'created_by' => 'system'
            ]);
            $profile->save(); // Simpan ke database
        }

        // Ambil data layanan
        $layanan = MLayanan::orderBy('urutan_tampilan')->get();

        return view('admin.profileperusahaan', compact('profile', 'layanan'));
    }

    public function update(Request $request)
    {
        try {
            Log::info('Profile Update Request:', $request->all());

            $validator = Validator::make($request->all(), [
                'nama_perusahaan' => 'required|string|max:255',
                'nama_dagang' => 'nullable|string|max:255',
                'deskripsi_singkat' => 'required|string',
                'alamat_kantor_pusat' => 'required|string',
                'telepon' => 'required|string|max:20',
                'email' => 'required|email|max:100',
                'website' => 'nullable|url|max:100',
                'visi' => 'required|string',
                'misi' => 'required|string',
                'npwp' => 'nullable|string|max:25',
                'kode_izin_penyelenggaraan' => 'nullable|string|max:100',
                'siup' => 'nullable|string|max:50',
                'nib' => 'nullable|string|max:50',
                'nomor_sertifikat_transportasi' => 'nullable|string|max:100',
                'tdp' => 'nullable|string|max:50',
                'tanggal_berdiri' => 'nullable|date',
                'penanggung_jawab_utama' => 'nullable|string|max:100',
                'nama_pendiri' => 'nullable|string|max:100',
                'link_kebijakan_refund' => 'nullable|url',
                'link_kebijakan_privasi' => 'nullable|url',
                'link_syarat_ketentuan' => 'nullable|url',
                'link_bantuan' => 'nullable|url',
                'link_faq' => 'nullable|url',
                'logo_perusahaan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'background_website' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'struktur_organisasi' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Cari atau buat profil baru
            $profile = MProfilePerusahaan::first();

            if (!$profile) {
                $profile = new MProfilePerusahaan();
                $profile->created_by = auth()->guard('admin')->user()->id ?? 'admin';
            }

            // Update data dasar
            $profile->fill([
                'nama_perusahaan' => $request->nama_perusahaan,
                'nama_dagang' => $request->nama_dagang,
                'deskripsi_singkat' => $request->deskripsi_singkat,
                'alamat_kantor_pusat' => $request->alamat_kantor_pusat,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'website' => $request->website,
                'visi' => $request->visi,
                'misi' => $request->misi,
                'npwp' => $request->npwp,
                'kode_izin_penyelenggaraan' => $request->kode_izin_penyelenggaraan,
                'siup' => $request->siup,
                'nib' => $request->nib,
                'nomor_sertifikat_transportasi' => $request->nomor_sertifikat_transportasi,
                'tdp' => $request->tdp,
                'tanggal_berdiri' => $request->tanggal_berdiri,
                'penanggung_jawab_utama' => $request->penanggung_jawab_utama,
                'nama_pendiri' => $request->nama_pendiri,
                'link_kebijakan_refund' => $request->link_kebijakan_refund,
                'link_kebijakan_privasi' => $request->link_kebijakan_privasi,
                'link_syarat_ketentuan' => $request->link_syarat_ketentuan,
                'link_bantuan' => $request->link_bantuan,
                'link_faq' => $request->link_faq,
                'updated_by' => auth()->guard('admin')->user()->id ?? 'admin',
                'status' => 'active'
            ]);

            // Handle upload logo perusahaan
            if ($request->hasFile('logo_perusahaan')) {
                $file = $request->file('logo_perusahaan');
                $filename = 'logo-' . time() . '.' . $file->getClientOriginalExtension();

                // Delete old logo if exists
                if ($profile->logo_perusahaan && Storage::exists('public/' . $profile->logo_perusahaan)) {
                    Storage::delete('public/' . $profile->logo_perusahaan);
                }

                $path = $file->storeAs('public/profile', $filename);
                $profile->logo_perusahaan = 'profile/' . $filename;

                Log::info('Logo uploaded:', ['path' => $path, 'filename' => $filename]);
            }

            // Handle upload background website
            if ($request->hasFile('background_website')) {
                $file = $request->file('background_website');
                $filename = 'background-' . time() . '.' . $file->getClientOriginalExtension();

                // Delete old background if exists
                if ($profile->background_website && Storage::exists('public/' . $profile->background_website)) {
                    Storage::delete('public/' . $profile->background_website);
                }

                $path = $file->storeAs('public/profile', $filename);
                $profile->background_website = 'profile/' . $filename;

                Log::info('Background uploaded:', ['path' => $path, 'filename' => $filename]);
            }

            // Handle upload struktur organisasi
            if ($request->hasFile('struktur_organisasi')) {
                $file = $request->file('struktur_organisasi');
                $filename = 'struktur-' . time() . '.' . $file->getClientOriginalExtension();

                // Delete old structure if exists
                if ($profile->struktur_organisasi_file && Storage::exists('public/' . $profile->struktur_organisasi_file)) {
                    Storage::delete('public/' . $profile->struktur_organisasi_file);
                }

                $path = $file->storeAs('public/documents', $filename);
                $profile->struktur_organisasi_file = 'documents/' . $filename;

                Log::info('Struktur organisasi uploaded:', ['path' => $path, 'filename' => $filename]);
            }

            // Simpan data
            $profile->save();

            Log::info('Profile saved successfully:', ['id' => $profile->id_profile]);

            if ($request->expectsJson()) {
                $data = $profile->toArray();
                if ($profile->tanggal_berdiri) {
                    $data['tanggal_berdiri'] = $profile->tanggal_berdiri->format('Y-m-d');
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Profile perusahaan berhasil diperbarui.',
                    'data' => $data,
                    'redirect' => route('admin.profileperusahaan')
                ]);
            }

            return redirect()->route('admin.profileperusahaan')
                ->with('success', 'Profile perusahaan berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Profile update error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateLayanan(Request $request, $id)
    {
        try {
            $layanan = MLayanan::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nama_layanan' => 'required|string|max:100',
                'deskripsi_singkat' => 'required|string|max:255',
                'status_aktif' => 'required|boolean',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $layanan->nama_layanan = $request->nama_layanan;
            $layanan->deskripsi_singkat = $request->deskripsi_singkat;
            $layanan->slug = Str::slug($request->nama_layanan);
            $layanan->status_aktif = $request->status_aktif;

            // Handle upload logo layanan
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = 'layanan-' . $layanan->kode_layanan . '-' . time() . '.' . $file->getClientOriginalExtension();

                // Delete old logo if exists
                if ($layanan->logo && Storage::exists('public/' . $layanan->logo)) {
                    Storage::delete('public/' . $layanan->logo);
                }

                $path = $file->storeAs('public/layanan', $filename);
                $layanan->logo = 'layanan/' . $filename;
            }

            $layanan->save();

            return redirect()->route('admin.profileperusahaan')
                ->with('success', 'Layanan berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function createLayanan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode_layanan' => 'required|string|max:50|unique:m_layanan',
                'nama_layanan' => 'required|string|max:100',
                'deskripsi_singkat' => 'required|string|max:255',
                'kategori_layanan' => 'required|in:transport,logistics,rental',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $layanan = new MLayanan();
            $layanan->kode_layanan = $request->kode_layanan;
            $layanan->nama_layanan = $request->nama_layanan;
            $layanan->slug = Str::slug($request->nama_layanan);
            $layanan->deskripsi_singkat = $request->deskripsi_singkat;
            $layanan->deskripsi_panjang = $request->deskripsi_singkat;
            $layanan->kategori_layanan = $request->kategori_layanan;
            $layanan->status_aktif = $request->status_aktif ?? true;
            $layanan->urutan_tampilan = MLayanan::max('urutan_tampilan') + 1;
            $layanan->meta = ['has_schedule' => true];

            // Handle upload logo
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = 'layanan-' . $layanan->kode_layanan . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/layanan', $filename);
                $layanan->logo = 'layanan/' . $filename;
            }

            $layanan->save();

            return redirect()->route('admin.profileperusahaan')
                ->with('success', 'Layanan baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteLayanan($id)
    {
        try {
            $layanan = MLayanan::findOrFail($id);

            // Delete logo if exists
            if ($layanan->logo && Storage::exists('public/' . $layanan->logo)) {
                Storage::delete('public/' . $layanan->logo);
            }

            $layanan->delete();

            return redirect()->route('admin.profileperusahaan')
                ->with('success', 'Layanan berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
