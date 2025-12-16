    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('m_profile_perusahaan', function (Blueprint $table) {
                $table->id('id_profile');
                
                // 1. Informasi Dasar Perusahaan
                $table->string('nama_perusahaan', 200);
                $table->string('nama_dagang', 100);
                $table->string('logo_perusahaan')->nullable();
                $table->text('deskripsi_singkat')->nullable();
                $table->text('visi')->nullable();
                $table->text('misi')->nullable();
                
                // 2. Informasi Kontak
                $table->text('alamat_kantor_pusat');
                $table->string('telepon', 20);
                $table->string('email', 100);
                $table->string('website', 100)->nullable();
                $table->string('background_website')->nullable();
                $table->string('jam_operasional', 100)->nullable();
                
                // 3. Informasi Legal / Administratif
                $table->string('npwp', 25)->nullable();
                $table->string('nib', 50)->nullable();
                $table->string('siup', 50)->nullable();
                $table->string('tdp', 50)->nullable();
                $table->string('nomor_sertifikat_transportasi', 100)->nullable();
                $table->string('kode_izin_penyelenggaraan', 100)->nullable();
                
                // 4. Informasi Pembentukan Perusahaan
                $table->date('tanggal_berdiri');
                $table->string('nama_pendiri', 200)->nullable();
                $table->string('penanggung_jawab_utama', 200)->nullable();
                $table->string('struktur_organisasi_file')->nullable();
                $table->text('struktur_organisasi_text')->nullable();
                
                // 5. Brand & Unit Bisnis
                $table->string('brand_smartshuttle', 100)->nullable();
                $table->string('brand_smartsent', 100)->nullable();
                $table->string('brand_smartrent', 100)->nullable();
                $table->text('deskripsi_unit_bisnis')->nullable();
                
                // 6. Dokumen Pendukung (path file)
                $table->string('sop_layanan_customer_file')->nullable();
                $table->text('sop_layanan_customer_text')->nullable();
                $table->string('kebijakan_refund_file')->nullable();
                $table->text('kebijakan_refund_text')->nullable();
                $table->string('kebijakan_privasi_file')->nullable();
                $table->text('kebijakan_privasi_text')->nullable();
                $table->string('syarat_ketentuan_file')->nullable();
                $table->text('syarat_ketentuan_text')->nullable();
                
                // Links untuk halaman kebijakan
                $table->string('link_kebijakan_refund')->nullable();
                $table->string('link_kebijakan_privasi')->nullable();
                $table->string('link_syarat_ketentuan')->nullable();
                
                // Status dan Metadata
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->string('created_by', 50)->nullable();
                $table->string('updated_by', 50)->nullable();
                
                $table->timestamps();
                $table->softDeletes();
            });
        }

        public function down()
        {
            Schema::dropIfExists('m_profile_perusahaan');
        }
    };