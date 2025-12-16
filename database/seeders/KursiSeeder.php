<?php

namespace Database\Seeders;

use App\Models\Shuttle;
use App\Models\KursiTerpesan;
use App\Models\Jadwal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KursiSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        // 1. Update semua shuttle dengan layout kursi 9 kursi reguler FIX
        $shuttles = Shuttle::all();

        foreach ($shuttles as $shuttle) {
            $totalKursi = 9; // FIX 9 kursi

            // Generate layout FIX yang tidak akan berubah
            $layoutKursi = KursiTerpesan::generateLayoutKursi($totalKursi);

            $shuttle->update([
                'kapasitas_kursi' => $totalKursi,
                'total_kursi' => $totalKursi,
                'layout_kursi' => $layoutKursi // Layout FIX disimpan sebagai JSON
            ]);

            echo "Updated shuttle: {$shuttle->nama_shuttle} with {$totalKursi} seats\n";
        }

        // 2. Untuk setiap jadwal, buat record kursi terpesan (jika ada kursi terjual)
        $jadwals = Jadwal::with('shuttle')->get();

        foreach ($jadwals as $jadwal) {
            $shuttle = $jadwal->shuttle;
            $kursiTerjual = $shuttle->kapasitas_kursi - $jadwal->kursi_tersedia;

            // Jika tidak ada kursi terjual, skip
            if ($kursiTerjual <= 0) {
                continue;
            }

            // Ambil layout FIX dari shuttle
            $layoutKursi = $shuttle->layout_kursi_array;

            // Jika layout kosong, skip
            if (empty($layoutKursi)) {
                continue;
            }

            // Ambil semua nomor kursi dari layout
            $allSeatNumbers = array_column($layoutKursi, 'nomor');

            // Random pilih kursi yang akan dijadikan terpesan
            shuffle($allSeatNumbers);
            $selectedSeats = array_slice($allSeatNumbers, 0, min($kursiTerjual, count($allSeatNumbers)));

            // Insert kursi terpesan
            $kursiTerpesanData = [];
            foreach ($selectedSeats as $seatNumber) {
                $kursiTerpesanData[] = [
                    'jadwal_id' => $jadwal->id,
                    'nomor_kursi' => $seatNumber,
                    'status' => 'terpesan',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Insert ke database (ignore jika sudah ada)
            foreach ($kursiTerpesanData as $data) {
                DB::table('kursi_terpesan')->updateOrInsert(
                    [
                        'jadwal_id' => $data['jadwal_id'],
                        'nomor_kursi' => $data['nomor_kursi']
                    ],
                    $data
                );
            }

            echo "Created {$kursiTerjual} booked seats for schedule ID: {$jadwal->id}\n";
        }

        echo "KursiSeeder completed successfully!\n";
    }
}
