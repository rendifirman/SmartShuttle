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
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_driver');
            $table->unsignedBigInteger('id_jadwal_driver')->nullable();
            $table->string('location_name')->nullable();
            $table->string('location_detail')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('stop_index')->default(0);
            $table->string('status')->default('in_transit')->comment('in_transit, arrived, completed');
            $table->timestamps();

            $table->foreign('id_driver')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_jadwal_driver')->references('id_jadwal_driver')->on('driver_jadwals')->onDelete('set null');
            $table->index('id_driver');
            $table->index('id_jadwal_driver');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_locations');
    }
};
