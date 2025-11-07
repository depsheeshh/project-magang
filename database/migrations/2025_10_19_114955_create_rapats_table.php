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
        Schema::create('rapat', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');

            // Lokasi rapat (geo-fencing)
            $table->string('lokasi')->nullable();
            $table->foreignId('ruangan_id')
            ->nullable()
            ->constrained('ruangan')
            ->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('radius')->default(100); // meter

            $table->integer('jumlah_tamu')->nullable();
            $table->uuid('qr_token')->nullable();
            $table->string('qr_token_hash')->nullable();
            $table->string('status')->default('belum_dimulai');

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
        Schema::dropIfExists('rapat');
    }
};
