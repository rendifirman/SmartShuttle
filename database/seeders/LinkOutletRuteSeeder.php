<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;
use App\Models\Rute;
use App\Models\RuteSegment;

class LinkOutletRuteSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== MULAI LINK OUTLET DENGAN RUTE ===');
        
        $rutes = Rute::all();
        
        foreach ($rutes as $rute) {
            $this->command->info('Processing route: ' . $rute->nama_rute);
            
            $pemberhentian = json_decode($rute->rute_pemberhentian, true) ?? [];
            $urutan = 1;
            
            foreach ($pemberhentian as $stop) {
                $kota = $stop['kota'] ?? '';
                $outletNames = $stop['outlets'] ?? [];
                $jarakSegment = $stop['jarak_segment'] ?? 50;
                
                foreach ($outletNames as $outletName) {
                    // Cari outlet berdasarkan nama dan kota
                    $outlet = Outlet::where('nama_outlet', $outletName)
                        ->whereHas('branch', function($q) use ($kota) {
                            $q->where('kota', $kota);
                        })
                        ->first();
                    
                    if ($outlet) {
                        // Cek apakah sudah ada segment
                        $existingSegment = RuteSegment::where('rute_id', $rute->id)
                            ->where('outlet_id', $outlet->id)
                            ->first();
                        
                        if (!$existingSegment) {
                            // Tentukan apakah ini pickup atau drop point
                            $isPickup = ($urutan === 1); // Outlet pertama bisa pickup
                            $isDrop = ($urutan === count($pemberhentian)); // Outlet terakhir bisa drop
                            
                            // Hitung jarak kumulatif (simplified)
                            $jarakKumulatif = ($urutan - 1) * $jarakSegment;
                            
                            RuteSegment::create([
                                'rute_id' => $rute->id,
                                'urutan_segment' => $urutan,
                                'outlet_id' => $outlet->id,
                                'kota' => $kota,
                                'nama_lokasi' => $outlet->nama_outlet,
                                'jarak_segment' => $jarakSegment,
                                'jarak_kumulatif' => $jarakKumulatif,
                                'estimasi_waktu' => $stop['durasi_singgah'] ?? 15,
                                'is_pickup_point' => $isPickup,
                                'is_drop_point' => $isDrop,
                                'status_aktif' => true,
                            ]);
                            
                            $this->command->info('  Linked: ' . $outlet->nama_outlet . ' (kota: ' . $kota . ')');
                        }
                    } else {
                        $this->command->warn('  Outlet not found: ' . $outletName . ' in ' . $kota);
                    }
                }
                
                $urutan++;
            }
        }
        
        $this->command->info('=== SELESAI LINK OUTLET DENGAN RUTE ===');
        $this->command->info('Total rute segments: ' . RuteSegment::count());
    }
}