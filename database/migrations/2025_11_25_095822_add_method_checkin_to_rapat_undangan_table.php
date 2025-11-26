<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rapat_undangan', function (Blueprint $table) {
            // Tambahkan kolom method_checkin
            $table->string('method_checkin')->nullable()->after('status_kehadiran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapat_undangan', function (Blueprint $table) {
            // Hapus kolom method_checkin saat rollback
            $table->dropColumn('method_checkin');
        });
    }
};
