<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Shuttle;
use App\Models\KursiTerpesan;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom jika belum ada
        if (!Schema::hasColumn('shuttles', 'layout_kursi')) {
            Schema::table('shuttles', function (Blueprint $table) {
                $table->json('layout_kursi')->nullable()->after('total_kursi');
            });
        }

        if (!Schema::hasColumn('shuttles', 'total_kursi')) {
            Schema::table('shuttles', function (Blueprint $table) {
                $table->integer('total_kursi')->default(9)->after('kapasitas_kursi');
            });
        }

        // 2. Update semua shuttle yang ada dengan layout FIX
        $shuttles = Shuttle::all();

        foreach ($shuttles as $shuttle) {
            // Set total_kursi jika null
            if (!$shuttle->total_kursi) {
                $shuttle->total_kursi = 9;
            }

            // Generate dan simpan layout FIX
            $layoutKursi = KursiTerpesan::generateLayoutKursi($shuttle->total_kursi);
            $shuttle->layout_kursi = $layoutKursi;
            $shuttle->save();
        }

        echo "Fixed layout for " . count($shuttles) . " shuttles\n";
    }

    public function down(): void
    {
        // Tidak menghapus data, hanya migration data
        // Untuk rollback, tidak perlu hapus kolom karena data penting
    }
};
