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
        Schema::table('rapat_undangan', function (Blueprint $table) {
            $table->foreignId('rapat_undangan_instansi_id')
                  ->nullable()
                  ->after('rapat_id')
                  ->constrained('rapat_undangan_instansi')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapat_undangan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('rapat_undangan_instansi_id');
        });
    }
};
