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
        Schema::create('rapat_undangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained('rapat')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status_kehadiran', ['pending','hadir','tidak_hadir'])->default('pending');
            $table->dateTime('checkin_time')->nullable();
            $table->decimal('checkin_lat', 10, 7)->nullable();
            $table->decimal('checkin_lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapat_undangan');
    }
};
