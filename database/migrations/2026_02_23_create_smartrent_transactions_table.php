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
        Schema::create('smartrent_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique()->index(); // SR2026021230D448
            $table->string('invoice_number', 100)->nullable(); // INV-SR-20260223-XXXXX
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Vehicle Information
            $table->integer('vehicle_id')->index();
            $table->string('vehicle_name', 100);
            $table->string('vehicle_type', 50)->nullable();
            $table->decimal('vehicle_price', 15, 2); // Price per day
            $table->integer('duration'); // Number of days
            $table->decimal('vehicle_total', 15, 2); // vehicle_price * duration
            
            // Driver Information
            $table->enum('service_type', ['with_driver', 'self_drive'])->default('self_drive');
            $table->decimal('driver_price_per_day', 15, 2)->default(0);
            $table->decimal('driver_total', 15, 2)->default(0); // driver_price * duration
            
            // Total Price
            $table->decimal('total_price', 15, 2);
            
            // Customer Information
            $table->string('customer_name', 100);
            $table->string('customer_email', 100);
            $table->string('customer_phone', 20);
            $table->text('customer_address')->nullable();
            
            // Rental Schedule
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('pickup_location', 255)->nullable();
            
            // Additional Details
            $table->text('notes')->nullable();
            $table->string('ktp_path', 255)->nullable();
            $table->string('sim_path', 255)->nullable();
            $table->string('other_document_path', 255)->nullable();
            
            // Payment Information
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'failed', 'cancelled'])->default('unpaid')->index();
            $table->string('payment_method', 50)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_proof_path', 255)->nullable();
            
            // Status
            $table->enum('status', ['pending_payment', 'confirmed', 'ongoing', 'completed', 'cancelled'])->default('pending_payment')->index();
            
            // Additional Information stored as JSON
            $table->json('additional_data')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'payment_status']);
            $table->index(['user_id', 'created_at']);
            $table->index(['payment_status', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smartrent_transactions');
    }
};
