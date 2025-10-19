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
            $table->string('lokasi')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('radius')->default(100);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
