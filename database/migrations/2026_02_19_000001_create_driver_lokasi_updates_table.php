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
        Schema::create('driver_lokasi_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_perjalanan_id');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('alamat')->nullable();
            $table->decimal('akurasi', 8, 2)->nullable();
            $table->timestamp('waktu_update');
            $table->timestamps();

            $table->foreign('driver_perjalanan_id')->references('id')->on('driver_perjalanan')->onDelete('cascade');

            $table->index('driver_perjalanan_id');
            $table->index('waktu_update');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_lokasi_updates');
    }
};
