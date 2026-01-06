<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek apakah kolom sudah ada sebelum menambah
        if (!Schema::hasColumn('shuttles', 'gambar_depan')) {
            Schema::table('shuttles', function (Blueprint $table) {
                $table->string('gambar_depan')->nullable()->after('fasilitas');
            });
        }

        if (!Schema::hasColumn('shuttles', 'gambar_samping')) {
            Schema::table('shuttles', function (Blueprint $table) {
                $table->string('gambar_samping')->nullable()->after('gambar_depan');
            });
        }

        if (!Schema::hasColumn('shuttles', 'gambar_belakang')) {
            Schema::table('shuttles', function (Blueprint $table) {
                $table->string('gambar_belakang')->nullable()->after('gambar_samping');
            });
        }

        if (!Schema::hasColumn('shuttles', 'gambar_interior')) {
            Schema::table('shuttles', function (Blueprint $table) {
                $table->string('gambar_interior')->nullable()->after('gambar_belakang');
            });
        }

        if (!Schema::hasColumn('shuttles', 'total_kursi')) {
            Schema::table('shuttles', function (Blueprint $table) {
                $table->integer('total_kursi')->default(9)->after('kapasitas_kursi');
            });
        }

        if (!Schema::hasColumn('shuttles', 'layout_kursi')) {
            Schema::table('shuttles', function (Blueprint $table) {
                $table->json('layout_kursi')->nullable()->after('total_kursi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shuttles', function (Blueprint $table) {
            $table->dropColumn([
                'gambar_depan',
                'gambar_samping',
                'gambar_belakang',
                'gambar_interior',
                'total_kursi',
                'layout_kursi'
            ]);
        });
    }
};
