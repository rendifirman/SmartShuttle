<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Outlet;
use App\Models\Rute;
use App\Models\MasterHarga;

class SmartSendController extends Controller
{
    /**
     * API: Get outlet tujuan berdasarkan outlet asal
     */
    public function getOutletTujuan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outlet_asal_id' => 'required|exists:outlets,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Outlet asal tidak valid'
            ], 422);
        }

        // Gunakan method dari Rute untuk filter
        $outletTujuan = Rute::getOutletTujuanValid($request->outlet_asal_id);
        
        if ($outletTujuan->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada outlet tujuan yang tersedia'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $outletTujuan,
            'total' => $outletTujuan->count()
        ]);
    }

    /**
     * API: Hitung estimasi harga
     */
    public function hitungEstimasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outlet_asal_id' => 'required|exists:outlets,id',
            'outlet_tujuan_id' => 'required|exists:outlets,id',
            'berat' => 'required|numeric|min:0.1|max:100',
            'panjang' => 'nullable|numeric|min:1|max:500',
            'lebar' => 'nullable|numeric|min:1|max:500',
            'tinggi' => 'nullable|numeric|min:1|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 1. Cari rute dan jarak
            $ruteData = Rute::cariRuteUntukOutlet(
                $request->outlet_asal_id,
                $request->outlet_tujuan_id
            );

            if (!$ruteData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada rute yang tersedia antara outlet yang dipilih'
                ], 404);
            }

            $rute = $ruteData['rute'];
            $jarak = $ruteData['jarak'];
            $beratAktual = $request->berat;

            // 2. Hitung berat volumetric (dalam kg, dimensi dalam cm)
            $volume = ($request->panjang ?? 0) * ($request->lebar ?? 0) * ($request->tinggi ?? 0);
            $beratVolumetric = $volume > 0 ? $volume / 6000 : 0;
            
            // 3. Tentukan berat perhitungan (ambil yang lebih besar)
            $beratPerhitungan = max($beratAktual, $beratVolumetric);

            // 4. Hitung harga menggunakan MasterHarga atau default
            $harga = $this->hitungHarga($beratPerhitungan, $jarak);

            // 5. Format response
            $outletAsal = Outlet::with('branch')->find($request->outlet_asal_id);
            $outletTujuan = Outlet::with('branch')->find($request->outlet_tujuan_id);

            return response()->json([
                'success' => true,
                'data' => [
                    'outlet_asal' => [
                        'id' => $outletAsal->id,
                        'nama' => $outletAsal->nama_outlet,
                        'kota' => $outletAsal->branch->kota ?? 'Unknown',
                        'alamat' => $outletAsal->alamat_lengkap
                    ],
                    'outlet_tujuan' => [
                        'id' => $outletTujuan->id,
                        'nama' => $outletTujuan->nama_outlet,
                        'kota' => $outletTujuan->branch->kota ?? 'Unknown',
                        'alamat' => $outletTujuan->alamat_lengkap
                    ],
                    'rute' => [
                        'id' => $rute->id,
                        'nama' => $rute->nama_rute,
                        'kode' => $rute->kode_rute,
                        'jarak_km' => $jarak
                    ],
                    'berat' => [
                        'aktual' => round($beratAktual, 2),
                        'volumetric' => round($beratVolumetric, 2),
                        'perhitungan' => round($beratPerhitungan, 2),
                        'satuan' => 'kg'
                    ],
                    'dimensi' => [
                        'panjang' => $request->panjang ?? 0,
                        'lebar' => $request->lebar ?? 0,
                        'tinggi' => $request->tinggi ?? 0,
                        'volume' => $volume,
                        'satuan' => 'cm³'
                    ],
                    'jarak' => [
                        'km' => round($jarak, 2),
                        'satuan' => 'km'
                    ],
                    'harga' => $harga,
                    'estimasi_waktu' => $this->hitungEstimasiWaktu($jarak),
                    'perhitungan' => $this->formatPerhitungan($beratPerhitungan, $jarak, $harga),
                    'catatan' => 'Ini hanya estimasi harga. Harga final ditentukan saat penimbangan di outlet.'
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error hitung estimasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
 * Helper: Hitung harga berdasarkan MasterHarga
 */
private function hitungHarga($berat, $jarak)
{
    // Coba ambil master harga
    $masterHarga = null;
    try {
        $masterHarga = \App\Models\MasterHarga::first();
    } catch (\Exception $e) {
        \Log::warning('Error mengambil master harga: ' . $e->getMessage());
    }

    // Default values
    $default = [
        'berat_pertama' => 5,
        'harga_berat_pertama' => 7000,
        'harga_berat_berikutnya' => 2000,
        'kelipatan_jarak' => 10,
        'harga_per_kelipatan' => 2000
    ];
    
    // Gunakan nilai dari masterHarga jika tersedia, jika tidak gunakan default
    $beratPertama = ($masterHarga ? $masterHarga->berat_pertama : null) ?? $default['berat_pertama'];
    $hargaBeratPertama = ($masterHarga ? $masterHarga->harga_berat_pertama : null) ?? $default['harga_berat_pertama'];
    $hargaBeratBerikutnya = ($masterHarga ? $masterHarga->harga_berat_berikutnya : null) ?? $default['harga_berat_berikutnya'];
    $kelipatanJarak = ($masterHarga ? $masterHarga->kelipatan_jarak : null) ?? $default['kelipatan_jarak'];
    $hargaPerKelipatan = ($masterHarga ? $masterHarga->harga_per_kelipatan : null) ?? $default['harga_per_kelipatan'];

    // Hitung harga berat
    if ($berat <= $beratPertama) {
        $hargaBerat = $hargaBeratPertama;
    } else {
        $hargaBerat = $hargaBeratPertama + 
                     (ceil($berat - $beratPertama) * $hargaBeratBerikutnya);
    }

    // Hitung harga jarak
    $kelipatan = ceil($jarak / $kelipatanJarak);
    $hargaJarak = $kelipatan * $hargaPerKelipatan;

    $hargaTotal = $hargaBerat + $hargaJarak;

    return [
        'harga_berat' => $hargaBerat,
        'harga_jarak' => $hargaJarak,
        'harga_total' => $hargaTotal,
        'formatted' => [
            'berat' => 'Rp ' . number_format($hargaBerat, 0, ',', '.'),
            'jarak' => 'Rp ' . number_format($hargaJarak, 0, ',', '.'),
            'total' => 'Rp ' . number_format($hargaTotal, 0, ',', '.')
        ],
        'using_default' => $masterHarga ? false : true
    ];
}

    /**
     * Helper: Format detail perhitungan berat
     */
    private function formatDetailBerat($berat, $aturan)
    {
        if ($berat <= $aturan->berat_pertama) {
            return "Berat {$berat} kg ≤ {$aturan->berat_pertama} kg pertama: Rp " . 
                   number_format($aturan->harga_berat_pertama, 0, ',', '.');
        } else {
            $tambahan = ceil($berat - $aturan->berat_pertama);
            return "{$aturan->berat_pertama} kg pertama: Rp " . 
                   number_format($aturan->harga_berat_pertama, 0, ',', '.') . 
                   " + {$tambahan} kg × Rp " . 
                   number_format($aturan->harga_berat_berikutnya, 0, ',', '.');
        }
    }

    /**
     * Helper: Format detail perhitungan jarak
     */
    private function formatDetailJarak($jarak, $aturan)
    {
        $kelipatan = ceil($jarak / $aturan->kelipatan_jarak);
        return "Jarak {$jarak} km = {$kelipatan} × {$aturan->kelipatan_jarak} km × Rp " . 
               number_format($aturan->harga_per_kelipatan, 0, ',', '.');
    }

    /**
     * Helper: Hitung estimasi waktu pengiriman
     */
    private function hitungEstimasiWaktu($jarak)
    {
        // Asumsi: 60 km/jam, + waktu proses di outlet
        $jam = $jarak / 60;
        $totalMenit = ceil($jam * 60) + 120; // +2 jam untuk proses
        
        if ($totalMenit > 1440) {
            return ceil($totalMenit / 1440) . ' hari';
        } elseif ($totalMenit > 60) {
            return ceil($totalMenit / 60) . ' jam';
        } else {
            return $totalMenit . ' menit';
        }
    }

    /**
     * Helper: Format perhitungan untuk display (User-Friendly)
     */
    private function formatPerhitungan($berat, $jarak, $harga)
    {
        $beratPertama = 5;
        $hargaBeratPertama = 7000;
        $hargaBeratBerikutnya = 2000;
        $kelipatanJarak = 10;
        $hargaPerKelipatan = 2000;
        
        $text = "INFORMASI HARGA PENGIRIMAN\n";
        $text .= str_repeat("─", 40) . "\n\n";
        
        // Informasi Berat
        $text .= "INFORMASI BERAT:\n";
        $text .= "• {$beratPertama} kg pertama: Rp " . number_format($hargaBeratPertama, 0, ',', '.') . "\n";
        
        if ($berat > $beratPertama) {
            $beratTambahan = ceil($berat - $beratPertama);
            $text .= "• Tambahan {$beratTambahan} kg × Rp " . number_format($hargaBeratBerikutnya, 0, ',', '.') . "\n";
        }
        
        $text .= "  Subtotal Berat: Rp " . number_format($harga['harga_berat'], 0, ',', '.') . "\n\n";
        
        // Informasi Jarak
        $text .= "INFORMASI JARAK:\n";
        $text .= "• Total jarak: " . round($jarak, 1) . " km\n";
        $text .= "• Biaya per {$kelipatanJarak} km: Rp " . number_format($hargaPerKelipatan, 0, ',', '.') . "\n";
        $text .= "  Subtotal Jarak: Rp " . number_format($harga['harga_jarak'], 0, ',', '.') . "\n\n";
        
        // Total
        $text .= str_repeat("─", 40) . "\n";
        $text .= "TOTAL BIAYA: Rp " . number_format($harga['harga_total'], 0, ',', '.') . "\n";
        $text .= str_repeat("─", 40);
        
        return $text;
    }
}