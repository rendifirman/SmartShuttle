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
        Schema::create('driver_journey_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_driver');
            $table->unsignedBigInteger('id_jadwal_driver');
            $table->integer('current_stop_index')->default(0);
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->string('last_stop_name')->nullable();
            $table->integer('total_stops')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_driver')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_jadwal_driver')->references('id_jadwal_driver')->on('driver_jadwals')->onDelete('cascade');

            $table->index('id_driver');
            $table->index('id_jadwal_driver');
            $table->index('status');
            $table->unique(['id_driver', 'id_jadwal_driver']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_journey_states');
    }
};
