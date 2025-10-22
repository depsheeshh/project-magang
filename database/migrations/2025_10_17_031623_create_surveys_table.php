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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('link')->nullable()->unique();
            $table->foreignId('kunjungan_id')
                ->constrained('kunjungan') // pastikan sesuai nama tabel
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->tinyInteger('rating')->nullable(); // rating umum
            $table->text('feedback')->nullable();      // feedback umum

            // ✅ Tambahan field sesuai requirement
            $table->unsignedTinyInteger('kemudahan_registrasi')->nullable();
            $table->unsignedTinyInteger('keramahan_pelayanan')->nullable();
            $table->unsignedTinyInteger('waktu_tunggu')->nullable();
            $table->text('saran')->nullable();

            // Audit trail
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
