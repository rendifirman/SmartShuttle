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
        Schema::table('shuttles', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('updated_at');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
            $table->softDeletes();

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
        Schema::table('shuttles', function (Blueprint $table) {
            if (Schema::hasColumn('shuttles', 'created_by')) {
                try { $table->dropForeign(['created_by']); } catch (\Exception $e) {}
            }
            if (Schema::hasColumn('shuttles', 'updated_by')) {
                try { $table->dropForeign(['updated_by']); } catch (\Exception $e) {}
            }
            if (Schema::hasColumn('shuttles', 'deleted_by')) {
                try { $table->dropForeign(['deleted_by']); } catch (\Exception $e) {}
            }
            $columns = ['created_by', 'updated_by', 'deleted_by', 'deleted_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('shuttles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
