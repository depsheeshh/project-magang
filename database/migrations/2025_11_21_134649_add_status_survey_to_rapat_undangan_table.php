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
            $table->enum('status_survey', ['belum_isi','sudah_isi'])
                ->default('belum_isi')
                ->after('status_kehadiran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapat_undangan', function (Blueprint $table) {
            $table->dropColumn('status_survey');
        });
    }
};
