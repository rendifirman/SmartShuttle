<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rute_jadwal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rute');
            $table->unsignedBigInteger('id_shuttle');
            $table->unsignedBigInteger('id_driver')->nullable();
            $table->date('tanggal');
            $table->time('jam_berangkat');
            $table->enum('status', ['open', 'active', 'cancelled', 'done'])->default('open');
            $table->timestamps();

            $table->index('id_rute');
            $table->index('id_shuttle');
            $table->index('id_driver');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rute_jadwal');
    }
};
