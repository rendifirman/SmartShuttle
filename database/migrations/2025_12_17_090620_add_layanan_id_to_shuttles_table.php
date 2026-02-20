<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\MLayanan;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('shuttles', function (Blueprint $table) {
            // Tambah kolom layanan_id sebagai foreign key ke m_layanan
            $table->foreignId('layanan_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('m_layanan', 'id_layanan')
                  ->onDelete('set null');

            // Tambah index untuk performa query
            $table->index(['layanan_id', 'status']);
        });

        // Update data existing (jika ada)
        $this->updateExistingShuttles();
    }

    /**
     * Update shuttles yang sudah ada dengan layanan default (Smart Shuttle)
     */
    private function updateExistingShuttles()
    {
        try {
            // Cari layanan Smart Shuttle
            $smartShuttleLayanan = MLayanan::where('kode_layanan', 'SMARTSHUTTLE')->first();

            if ($smartShuttleLayanan) {
                // Update semua shuttle existing ke layanan Smart Shuttle
                DB::table('shuttles')->update([
                    'layanan_id' => $smartShuttleLayanan->id_layanan,
                    'updated_at' => now()
                ]);

                Log::info('Updated existing shuttles with layanan_id: ' . $smartShuttleLayanan->id_layanan);
            }
        } catch (\Exception $e) {
            Log::error('Error updating existing shuttles: ' . $e->getMessage());
        }
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('shuttles', function (Blueprint $table) {
            if (Schema::hasColumn('shuttles', 'layanan_id')) {
                $table->dropForeign(['layanan_id']);
                $table->dropColumn('layanan_id');
            }
        });
    }
};
