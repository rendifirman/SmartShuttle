<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_jadwals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shuttle_id');
            $table->unsignedBigInteger('rute_id');
            $table->date('tanggal_berangkat');
            $table->time('jam_berangkat');
            $table->decimal('harga', 12, 2);
            $table->integer('seat_total');
            $table->integer('seat_available');
            $table->enum('status_jadwal', ['available', 'taken'])->default('available');
            $table->unsignedBigInteger('created_by')->comment('ID admin yang membuat');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('shuttle_id')->references('id')->on('shuttles')->onDelete('cascade');
            $table->foreign('rute_id')->references('id')->on('rutes')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('status_jadwal');
            $table->index('tanggal_berangkat');
            $table->index(['tanggal_berangkat', 'jam_berangkat']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_jadwals');
    }
}; 