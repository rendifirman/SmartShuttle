<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('smartrent_orders', function (Blueprint $table) {
            $table->id();
            // Nomor pesanan unik
            $table->string('order_number', 50)->unique()->index();
            $table->string('invoice_number', 100)->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Informasi Kendaraan
            $table->integer('vehicle_id')->nullable()->index();
            $table->string('vehicle_name', 100)->nullable();
            $table->string('vehicle_type', 50)->nullable();
            $table->decimal('vehicle_price', 15, 2)->default(0);
            $table->integer('duration')->default(1);
            $table->decimal('vehicle_total', 15, 2)->default(0);

            // Layanan Sopir
            $table->enum('service_type', ['with_driver', 'self_drive'])->default('self_drive');
            $table->decimal('driver_price_per_day', 15, 2)->default(0);
            $table->decimal('driver_total', 15, 2)->default(0);

            // Harga Total
            $table->decimal('total_price', 15, 2)->default(0);

            // Data Pelanggan
            $table->string('customer_name', 100);
            $table->string('customer_email', 100);
            $table->string('customer_phone', 20);
            $table->text('customer_address')->nullable();

            // Jadwal Sewa
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('pickup_location', 255)->nullable();

            // Dokumen
            $table->string('ktp_path', 255)->nullable();
            $table->string('sim_path', 255)->nullable();
            $table->string('other_document_path', 255)->nullable();

            // Status Pesanan
            $table->enum('status', ['pending_payment', 'paid', 'confirmed', 'ongoing', 'completed', 'cancelled'])->default('pending_payment')->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('smartrent_orders');
    }
};
