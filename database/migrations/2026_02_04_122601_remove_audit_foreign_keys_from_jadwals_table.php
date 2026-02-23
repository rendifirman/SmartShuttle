<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus foreign key constraints terlebih dahulu
        Schema::table('jadwals', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);
            
            // Ubah kolom menjadi nullable
            $table->foreignId('created_by')->nullable()->change();
            $table->foreignId('updated_by')->nullable()->change();
            $table->foreignId('deleted_by')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            // Non-nullable kembali
            $table->foreignId('created_by')->nullable(false)->change();
            $table->foreignId('updated_by')->nullable(false)->change();
            $table->foreignId('deleted_by')->nullable(false)->change();
            
            // Tambahkan kembali foreign key (sesuaikan dengan nama constraint yang asli)
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }
};