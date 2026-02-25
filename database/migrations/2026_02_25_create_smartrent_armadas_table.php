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
        Schema::create('smartrent_armadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shuttle_id')->nullable()->comment('Link ke shuttle jika ada');
            $table->string('nama', 255)->comment('Nama armada/kendaraan');
            $table->string('tipe', 100)->comment('Tipe kendaraan: MPV, SUV, Hatchback, Sedan, Minibus');
            $table->integer('kapasitas')->comment('Kapasitas penumpang');
            $table->string('nomor_polisi', 20)->unique()->comment('Plat nomor kendaraan');
            $table->integer('tahun')->comment('Tahun pembuatan');
            $table->string('bahan_bakar', 50)->comment('Jenis bahan bakar');
            $table->text('deskripsi')->nullable()->comment('Deskripsi armada');
            $table->string('gambar', 255)->nullable()->comment('Foto armada');
            $table->decimal('harga_dasar', 15, 2)->comment('Harga dasar per hari');
            $table->decimal('harga_dengan_sopir', 15, 2)->nullable()->comment('Harga dengan sopir per hari');
            $table->json('fasilitas')->nullable()->comment('Fasilitas sebagai JSON array');
            $table->enum('status', ['aktif', 'nonaktif', 'maintenance'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('shuttle_id')->references('id')->on('shuttles')->onDelete('set null');
            
            // Indexes
            $table->index('tipe');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smartrent_armadas');
    }
};
