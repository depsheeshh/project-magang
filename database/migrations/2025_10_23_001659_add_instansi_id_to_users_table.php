<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // tambahkan kolom instansi_id nullable
            $table->unsignedBigInteger('instansi_id')->nullable()->after('email_verified_at');

            // tambahkan foreign key ke tabel instansi
            $table->foreign('instansi_id')
                  ->references('id')
                  ->on('instansi')
                  ->onDelete('set null'); // kalau instansi dihapus, user tetap ada tapi instansi_id jadi null
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn('instansi_id');
        });
    }
};

