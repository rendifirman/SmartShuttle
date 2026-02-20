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
        Schema::table('m_profile_perusahaan', function (Blueprint $table) {
            // Drop existing string columns
            $table->dropColumn(['created_by', 'updated_by']);

            // Add audit fields
            $table->unsignedBigInteger('created_by')->nullable()->after('status');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');

            // Add foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_profile_perusahaan', function (Blueprint $table) {
            if (Schema::hasColumn('m_profile_perusahaan', 'created_by')) {
                try { $table->dropForeign(['created_by']); } catch (\Exception $e) {}
            }
            if (Schema::hasColumn('m_profile_perusahaan', 'updated_by')) {
                try { $table->dropForeign(['updated_by']); } catch (\Exception $e) {}
            }
            if (Schema::hasColumn('m_profile_perusahaan', 'deleted_by')) {
                try { $table->dropForeign(['deleted_by']); } catch (\Exception $e) {}
            }

            // Drop audit columns
            $columns = ['created_by', 'updated_by', 'deleted_by'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('m_profile_perusahaan', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Restore original string columns if not exists
            if (!Schema::hasColumn('m_profile_perusahaan', 'created_by')) {
                $table->string('created_by', 50)->nullable()->after('status');
            }
            if (!Schema::hasColumn('m_profile_perusahaan', 'updated_by')) {
                $table->string('updated_by', 50)->nullable()->after('created_by');
            }
        });
    }
};
