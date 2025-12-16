<?php
namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Shuttle;
use App\Models\Rute;
use App\Models\RuteJadwal;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwals')->truncate();
        DB::table('rute_jadwals')->truncate();
        
        $shuttles = Shuttle::all();
        $rutes = Rute::all();

        if ($shuttles->isEmpty() || $rutes->isEmpty()) {
            return;
        }

        // Buat jadwal untuk setiap rute
        foreach ($rutes as $rute) {
            // Ambil 3 shuttle pertama untuk setiap rute (agar tidak terlalu banyak)
            $shuttlesToUse = $shuttles->take(3);
            
            foreach ($shuttlesToUse as $shuttle) {
                // Buat jadwal untuk 14 hari ke depan
                for ($i = 1; $i <= 14; $i++) {
                    // Generate waktu keberangkatan pagi (06:00 - 10:00) atau sore (14:00 - 18:00)
                    $isMorning = rand(0, 1);
                    $hour = $isMorning ? rand(6, 10) : rand(14, 18);
                    $minute = rand(0, 3) * 15; // 0, 15, 30, atau 45
                    
                    $waktuKeberangkatan = Carbon::now()
                        ->addDays($i)
                        ->setTime($hour, $minute);
                    
                    // Parse durasi rute
                    $durasiParts = explode(':', $rute->durasi);
                    $totalDurasi = ((int)$durasiParts[0] * 60) + ((int)($durasiParts[1] ?? 0));
                    
                    // Tambahkan waktu singgah di setiap pemberhentian
                    $pemberhentian = json_decode($rute->rute_pemberhentian, true);
                    if (is_array($pemberhentian)) {
                        foreach ($pemberhentian as $stop) {
                            $totalDurasi += ($stop['durasi_singgah'] ?? 10);
                        }
                    }
                    
                    $waktuKedatangan = $waktuKeberangkatan->copy()->addMinutes($totalDurasi);
                    
                    // Tentukan kapasitas kursi tersedia (70-100% dari kapasitas)
                    $kapasitasShuttle = $shuttle->kapasitas_kursi;
                    $kursiTersedia = rand(floor($kapasitasShuttle * 0.7), $kapasitasShuttle);
                    
                    // Cek apakah jadwal sudah ada untuk menghindari duplikasi
                    $existingJadwal = Jadwal::where([
                        'shuttle_id' => $shuttle->id,
                        'tanggal_keberangkatan' => $waktuKeberangkatan->format('Y-m-d'),
                        'waktu_keberangkatan' => $waktuKeberangkatan->format('H:i'),
                    ])->exists();
                    
                    if ($existingJadwal) {
                        continue;
                    }
                    
                    // Buat jadwal
                    $jadwal = Jadwal::create([
                        'shuttle_id' => $shuttle->id,
                        'tanggal_keberangkatan' => $waktuKeberangkatan->format('Y-m-d'),
                        'waktu_keberangkatan' => $waktuKeberangkatan->format('H:i'),
                        'waktu_kedatangan' => $waktuKedatangan->format('H:i'),
                        'harga_total' => $rute->harga_dasar,
                        'kursi_tersedia' => $kursiTersedia,
                        'status' => $kursiTersedia > 0 ? 'tersedia' : 'penuh',
                    ]);
                    
                    // Hubungkan jadwal dengan rute
                    RuteJadwal::create([
                        'jadwal_id' => $jadwal->id,
                        'rute_id' => $rute->id,
                        'urutan' => 1,
                        'durasi_segment' => $totalDurasi,
                        'harga_segment' => $rute->harga_dasar,
                    ]);
                }
            }
        }
    }
}