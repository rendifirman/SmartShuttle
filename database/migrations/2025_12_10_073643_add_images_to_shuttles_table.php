<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shuttles', function (Blueprint $table) {
            $table->string('gambar_depan')->nullable()->after('nomor_polisi');
            $table->string('gambar_samping')->nullable()->after('gambar_depan');
            $table->string('gambar_belakang')->nullable()->after('gambar_samping');
            $table->string('gambar_interior')->nullable()->after('gambar_belakang');
        });
    }

    public function down(): void
    {
        Schema::table('shuttles', function (Blueprint $table) {
            $columns = ['gambar_depan', 'gambar_samping', 'gambar_belakang', 'gambar_interior'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('shuttles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};