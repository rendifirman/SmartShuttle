    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('membership_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('transaction_id')->unique();
                $table->decimal('amount', 15, 2);
                $table->decimal('discount', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2);
                $table->string('payment_method')->nullable();
                $table->string('payment_status')->default('pending');
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('account_name')->nullable();
                $table->string('bukti_pembayaran')->nullable();
                $table->string('nama_pengirim')->nullable();
                $table->date('tanggal_transfer')->nullable();
                $table->decimal('jumlah_transfer', 15, 2)->nullable();
                $table->text('catatan')->nullable();
                $table->timestamp('waktu_kadaluarsa')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('transaction_id');
                $table->index('payment_status');
                $table->index(['user_id', 'payment_status']);
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('membership_payments');
        }
    };
