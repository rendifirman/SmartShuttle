<?php

namespace App\Services;

use App\Models\MasterHarga;
use Illuminate\Support\Facades\Log;

class HargaService
{
    private $defaultHarga = [
        'berat_pertama' => 5,
        'harga_berat_pertama' => 7000,
        'harga_berat_berikutnya' => 2000,
        'kelipatan_jarak' => 10,
        'harga_per_kelipatan' => 2000,
        'harga_minimum' => 5000,
        'harga_per_kg' => 5000
    ];

    /**
     * Hitung harga pengiriman dengan fallback
     */
    public function hitungHargaPengiriman($berat, $jarak)
    {
        // Coba ambil dari database
        $masterHarga = $this->getMasterHarga();
        
        // Hitung harga berat
        if ($berat <= ($masterHarga['berat_pertama'] ?? $this->defaultHarga['berat_pertama'])) {
            $hargaBerat = $masterHarga['harga_berat_pertama'] ?? $this->defaultHarga['harga_berat_pertama'];
        } else {
            $hargaBerat = ($masterHarga['harga_berat_pertama'] ?? $this->defaultHarga['harga_berat_pertama']) + 
                         (ceil($berat - ($masterHarga['berat_pertama'] ?? $this->defaultHarga['berat_pertama'])) * 
                          ($masterHarga['harga_berat_berikutnya'] ?? $this->defaultHarga['harga_berat_berikutnya']));
        }

        // Hitung harga jarak
        $kelipatan = ceil($jarak / ($masterHarga['kelipatan_jarak'] ?? $this->defaultHarga['kelipatan_jarak']));
        $hargaJarak = $kelipatan * ($masterHarga['harga_per_kelipatan'] ?? $this->defaultHarga['harga_per_kelipatan']);

        $hargaTotal = max(
            $hargaBerat + $hargaJarak,
            $masterHarga['harga_minimum'] ?? $this->defaultHarga['harga_minimum']
        );

        return [
            'harga_berat' => $hargaBerat,
            'harga_jarak' => $hargaJarak,
            'harga_total' => $hargaTotal,
            'using_default' => !isset($masterHarga['id']),
            'master_harga' => $masterHarga
        ];
    }

    /**
     * Ambil master harga dengan cara yang aman
     */
    private function getMasterHarga()
    {
        try {
            $harga = MasterHarga::first();
            
            if ($harga) {
                return $harga->toArray();
            }
        } catch (\Exception $e) {
            Log::warning('Gagal mengambil master harga: ' . $e->getMessage());
        }
        
        return $this->defaultHarga;
    }
}