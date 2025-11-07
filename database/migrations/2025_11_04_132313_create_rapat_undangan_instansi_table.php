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
        Schema::create('rapat_undangan_instansi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained('rapat')->cascadeOnDelete();
            $table->foreignId('instansi_id')->constrained('instansi')->cascadeOnDelete();
            $table->unsignedInteger('kuota')->default(1); // maksimal tamu per instansi
            $table->unsignedInteger('jumlah_hadir')->default(0); // counter hadir
            $table->timestamps();

            $table->unique(['rapat_id','instansi_id']); // 1 instansi hanya sekali diundang per rapat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapat_undangan_instansi');
    }
};
