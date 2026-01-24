<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Rute;
use App\Models\RuteSegment;
use App\Models\Outlet;
use App\Models\MasterHarga;

class SmartSendSeeder extends Seeder
{
    public function run(): void
    {
        // 2. Update rute yang sudah ada dengan data segment
        $rutes = Rute::all();
        
        foreach ($rutes as $rute) {
            // Hapus segment lama jika ada
            RuteSegment::where('rute_id', $rute->id)->delete();
            
            // Parse pemberhentian dari JSON
            $pemberhentian = json_decode($rute->rute_pemberhentian, true);
            
            if (is_array($pemberhentian)) {
                $jarakKumulatif = 0;
                $urutan = 1;
                
                foreach ($pemberhentian as $stop) {
                    // Cari outlet di kota tersebut
                    $outlets = Outlet::whereHas('branch', function($q) use ($stop) {
                        $q->where('kota', $stop['kota'] ?? '');
                    })->get();
                    
                    // Ambil outlet pertama sebagai contoh
                    $outlet = $outlets->first();
                    
                    // Buat segment
                    RuteSegment::create([
                        'rute_id' => $rute->id,
                        'urutan_segment' => $urutan,
                        'outlet_id' => $outlet->id ?? null,
                        'kota' => $stop['kota'] ?? '',
                        'nama_lokasi' => $stop['kota'] ?? 'Unknown',
                        'jarak_segment' => $stop['jarak_segment'] ?? 50, // default 50km
                        'jarak_kumulatif' => $jarakKumulatif + ($stop['jarak_segment'] ?? 50),
                        'estimasi_waktu' => $stop['durasi_singgah'] ?? 15,
                        'is_pickup_point' => $urutan === 1, // segment pertama bisa pickup
                        'is_drop_point' => $urutan === count($pemberhentian), // segment terakhir bisa drop
                        'status_aktif' => true,
                    ]);
                    
                    $jarakKumulatif += $stop['jarak_segment'] ?? 50;
                    $urutan++;
                }
                
                // HAPUS BARIS INI atau SIMPAN ke kolom lain:
                // $rute->jarak_total = $jarakKumulatif;
                // $rute->save();
                
                // Atau update kolom jarak yang sudah ada (jika mau):
                // $rute->jarak = $jarakKumulatif;
                // $rute->save();
            }
        }

        // 3. Buat contoh shipment untuk testing
        if (DB::table('shipments')->count() === 0) {
            $rute = Rute::first();
            $segments = RuteSegment::where('rute_id', $rute->id)->get();
            
            if ($segments->count() >= 2) {
                // HAPUS kolom yang tidak ada dari insert
                DB::table('shipments')->insert([
                    [
                        'kode_resi' => 'SS-' . date('Ymd') . '-0001',
                        'rute_id' => $rute->id,
                        'segment_asal_id' => $segments[0]->id,
                        'segment_tujuan_id' => $segments[1]->id,
                        'outlet_asal_id' => $segments[0]->outlet_id,
                        'outlet_tujuan_id' => $segments[1]->outlet_id,
                        'kota_asal' => $segments[0]->kota,
                        'kota_tujuan' => $segments[1]->kota,
                        'berat' => 3.5,
                        'jarak' => 120,
                        'harga_berat' => 7000,
                        'harga_jarak' => 24000,
                        'harga_total' => 31000,
                        'nama_pengirim' => 'Budi Santoso',
                        'telepon_pengirim' => '081234567890',
                        'nama_penerima' => 'Siti Rahayu',
                        'telepon_penerima' => '081298765432',
                        'alamat_tujuan' => 'Jl. Merdeka No. 123',
                        'status' => 'diproses',
                        // HAPUS: 'status_pengiriman' => 'dalam_perjalanan', // kolom tidak ada
                        'berat_aktual' => 3.5,
                        'jarak_tempuh' => 120,
                        'user_id' => 1,
                        'tanggal_dibuat' => now()->subDays(2),
                        'tanggal_dikirim' => now()->subDays(1),
                        'created_at' => now()->subDays(2),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }

        $this->command->info('SmartSend seeder completed!');
    }
}