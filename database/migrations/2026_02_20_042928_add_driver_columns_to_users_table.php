<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu sebelum menambah
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 16)->nullable();
            }
            
            if (!Schema::hasColumn('users', 'join_date')) {
                $table->date('join_date')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'sim_number')) {
                $table->string('sim_number')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'sim_expiry_date')) {
                $table->date('sim_expiry_date')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'ktp_file')) {
                $table->string('ktp_file')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'sim_file')) {
                $table->string('sim_file')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['nik', 'join_date', 'sim_number', 'sim_expiry_date', 'ktp_file', 'sim_file', 'avatar'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};