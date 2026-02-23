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
        Schema::create('driver_perjalanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jadwal_driver');
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai')->nullable();
            $table->decimal('jarak_tempuh', 8, 2)->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_jadwal_driver')->references('id_jadwal_driver')->on('driver_jadwals')->onDelete('cascade');

            $table->index('id_jadwal_driver');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_perjalanan');
    }
};
