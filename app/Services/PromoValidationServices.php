<?php

namespace App\Services;

use App\Models\Promo;
use Carbon\Carbon;

class PromoValidationService
{
    /**
     * Validate promo with user conditions
     */
    public static function validate(Promo $promo, array $userData, array $bookingData): array
    {
        $isMember = isset($userData['membership_status']) && $userData['membership_status'] === 'active';
        $jumlahTiket = $bookingData['jumlah_tiket'] ?? 1;
        $totalPembelian = $bookingData['total_pembelian'] ?? 0;

        // Cek status promo
        if (!$promo->isValid()) {
            return [
                'valid' => false,
                'message' => 'Promo tidak aktif atau sudah kadaluarsa'
            ];
        }

        // Cek minimal pembelian
        if ($totalPembelian < $promo->minimal_pembelian) {
            return [
                'valid' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($promo->minimal_pembelian, 0, ',', '.')
            ];
        }

        // Cek kategori keluarga
        if ($promo->kategori_promo === 'keluarga' && $promo->min_tiket && $jumlahTiket < $promo->min_tiket) {
            return [
                'valid' => false,
                'message' => "Minimal {$promo->min_tiket} tiket untuk promo keluarga"
            ];
        }

        // Cek kategori membership
        if ($promo->kategori_promo === 'membership' && !$isMember) {
            return [
                'valid' => false,
                'message' => 'Promo ini hanya untuk member'
            ];
        }

        // Cek khusus member
        if ($promo->khusus_member && !$isMember) {
            return [
                'valid' => false,
                'message' => 'Promo ini hanya untuk member'
            ];
        }

        // Hitung diskon
        $diskon = $promo->calculateDiscount($totalPembelian);
        $totalSetelahDiskon = $totalPembelian - $diskon;

        return [
            'valid' => true,
            'message' => 'Promo berhasil diterapkan',
            'diskon' => $diskon,
            'total_setelah_diskon' => $totalSetelahDiskon
        ];
    }

    /**
     * Validate for checkout (with promo code string)
     */
    public static function validateForCheckout(string $promoCode, array $userData, array $bookingData): array
    {
        $promo = Promo::where('kode_promo', strtoupper($promoCode))->first();

        if (!$promo) {
            return [
                'valid' => false,
                'message' => 'Kode promo tidak ditemukan'
            ];
        }

        return self::validate($promo, $userData, $bookingData);
    }

    /**
     * Get eligible promos with status (duplicate dari controller)
     */
    public static function getEligiblePromosWithStatus(array $userData, array $bookingData, $serviceType = 'shuttle'): array
    {
        $isMember = isset($userData['membership_status']) && $userData['membership_status'] === 'active';
        $jumlahTiket = $bookingData['jumlah_tiket'] ?? 1;
        $totalPembelian = $bookingData['total_pembelian'] ?? 0;

        $promos = Promo::active()
            ->where(function($query) use ($serviceType) {
                $query->where('tipe_promo', $serviceType)
                      ->orWhere('tipe_promo', 'all');
            })
            ->get()
            ->map(function ($promo) use ($userData, $jumlahTiket, $totalPembelian, $isMember) {
                // Validasi sederhana
                $eligible = true;
                $reason = null;
                
                // Cek status dasar
                if (!$promo->isValid()) {
                    $eligible = false;
                    $reason = 'Promo tidak aktif';
                }
                
                // Cek minimal pembelian
                elseif ($totalPembelian < $promo->minimal_pembelian) {
                    $eligible = false;
                    $reason = 'Min. pembelian Rp ' . number_format($promo->minimal_pembelian, 0, ',', '.');
                }
                
                // Cek kategori keluarga
                elseif ($promo->kategori_promo === 'keluarga' && $promo->min_tiket && $jumlahTiket < $promo->min_tiket) {
                    $eligible = false;
                    $reason = "Minimal {$promo->min_tiket} tiket";
                }
                
                // Cek kategori membership
                elseif ($promo->kategori_promo === 'membership' && !$isMember) {
                    $eligible = false;
                    $reason = 'Khusus member';
                }
                
                // Cek khusus member
                elseif ($promo->khusus_member && !$isMember) {
                    $eligible = false;
                    $reason = 'Hanya untuk member';
                }

                return [
                    'promo' => $promo,
                    'eligible' => $eligible,
                    'reason' => $reason,
                ];
            });

        return $promos->toArray();
    }
}