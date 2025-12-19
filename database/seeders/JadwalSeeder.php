<?php
namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Shuttle;
use App\Models\Rute;
use App\Models\MLayanan;
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

        // 1. Cari LAYANAN Smart Shuttle
        $smartShuttleService = MLayanan::where('kode_layanan', 'SMARTSHUTTLE')->first();

        if (!$smartShuttleService) {
            $this->command->error('Layanan Smart Shuttle tidak ditemukan! Jalankan MLayananSeeder dulu.');
            return;
        }

        // 2. Hanya ambil SHUTTLE untuk layanan Smart Shuttle
        $shuttles = Shuttle::where('layanan_id', $smartShuttleService->id_layanan)->get();

        // 3. Hanya ambil RUTE untuk layanan Smart Shuttle
        $rutes = Rute::where('layanan_id', $smartShuttleService->id_layanan)->get();

        if ($shuttles->isEmpty() || $rutes->isEmpty()) {
            $this->command->error('Shuttle atau Rute kosong! Jalankan seeder Shuttle dan Rute dulu.');
            return;
        }

        $this->command->info('Membuat jadwal untuk ' . $shuttles->count() . ' shuttle dan ' . $rutes->count() . ' rute...');

        // 4. Buat jadwal untuk 30 hari ke depan HANYA untuk shuttle Smart Shuttle
        $jadwalCreated = 0;
        for ($i = 0; $i <= 30; $i++) {
            foreach ($rutes as $rute) {
                foreach ($shuttles as $shuttle) {
                    // Skip 50% chance untuk variasi (agar tidak terlalu padat)
                    if (rand(0, 1) === 0) continue;

                    // Waktu keberangkatan (pagi: 6-9, siang: 12-15, malam: 18-21)
                    $period = rand(1, 3);
                    switch ($period) {
                        case 1: $hour = rand(6, 9); break;  // Pagi
                        case 2: $hour = rand(12, 15); break; // Siang
                        case 3: $hour = rand(18, 21); break; // Malam
                    }
                    $minute = rand(0, 1) ? 0 : 30;

                    $tanggal = Carbon::now()->addDays($i);
                    $waktuKeberangkatan = $tanggal->copy()->setTime($hour, $minute);

                    // Parse durasi dari string "HH:MM"
                    $durasiParts = explode(':', $rute->durasi);
                    $jam = (int)($durasiParts[0] ?? 0);
                    $menit = (int)($durasiParts[1] ?? 0);
                    $totalMenit = ($jam * 60) + $menit;

                    // Tambah waktu singgah
                    $pemberhentian = json_decode($rute->rute_pemberhentian, true);
                    if (is_array($pemberhentian)) {
                        foreach ($pemberhentian as $stop) {
                            $totalMenit += ($stop['durasi_singgah'] ?? 10);
                        }
                    }

                    $waktuKedatangan = $waktuKeberangkatan->copy()->addMinutes($totalMenit);

                    // Kapasitas kursi = semua kursi tersedia (karena baru dibuat, belum ada booking)
                    $kapasitas = $shuttle->kapasitas_kursi ?? $shuttle->total_kursi ?? 9;
                    $kursiTersedia = $kapasitas; // Semua kursi tersedia di awal

                    // Cek duplikat (jangan buat jadwal yang sama persis)
                    $existing = Jadwal::where([
                        'shuttle_id' => $shuttle->id,
                        'tanggal_keberangkatan' => $waktuKeberangkatan->format('Y-m-d'),
                        'waktu_keberangkatan' => $waktuKeberangkatan->format('H:i:s')
                    ])->exists();

                    if ($existing) continue;

                    // Buat jadwal
                    $jadwal = Jadwal::create([
                        'shuttle_id' => $shuttle->id,
                        'tanggal_keberangkatan' => $waktuKeberangkatan->format('Y-m-d'),
                        'waktu_keberangkatan' => $waktuKeberangkatan->format('H:i:s'),
                        'waktu_kedatangan' => $waktuKedatangan->format('H:i:s'),
                        'harga_total' => $rute->harga_dasar + rand(10000, 50000),
                        'kursi_tersedia' => $kursiTersedia,
                        'status' => 'tersedia',
                    ]);

                    // Hubungkan dengan rute
                    RuteJadwal::create([
                        'jadwal_id' => $jadwal->id,
                        'rute_id' => $rute->id,
                        'urutan' => 1,
                        'durasi_segment' => $totalMenit,
                        'harga_segment' => $rute->harga_dasar,
                    ]);

                    $jadwalCreated++;
                }
            }
        }

        $this->command->info('JadwalSeeder berhasil! Total: ' . $jadwalCreated . ' jadwal dibuat.');
        $this->command->info('Hanya shuttle Smart Shuttle yang dijadwalkan.');
        $this->command->info('Hanya rute Smart Shuttle yang digunakan.');
    }
}
