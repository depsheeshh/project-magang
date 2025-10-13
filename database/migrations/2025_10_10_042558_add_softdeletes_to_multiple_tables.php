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
        Schema::table('history_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('history_logs', 'deleted_at')) {
                $table->softDeletes()->after('reason');
            }
        });

        Schema::table('tamu', function (Blueprint $table) {
            if (!Schema::hasColumn('tamu', 'deleted_at')) {
                $table->softDeletes()->after('alamat');
            }
            if (!Schema::hasColumn('tamu', 'created_id')) {
                $table->unsignedBigInteger('created_id')->nullable()->after('deleted_at');
            }
            if (!Schema::hasColumn('tamu', 'updated_id')) {
                $table->unsignedBigInteger('updated_id')->nullable()->after('created_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_logs', function (Blueprint $table) {
            if (Schema::hasColumn('history_logs', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('tamu', function (Blueprint $table) {
            if (Schema::hasColumn('tamu', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('tamu', 'created_id')) {
                $table->dropColumn('created_id');
            }
            if (Schema::hasColumn('tamu', 'updated_id')) {
                $table->dropColumn('updated_id');
            }
        });
    }
};
