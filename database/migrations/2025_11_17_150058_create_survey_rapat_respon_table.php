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
        Schema::create('survey_rapat_respon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')
                ->constrained('survey_rapat')
                ->onDelete('cascade');
            $table->foreignId('user_id')->nullable();
            $table->string('nama');
            $table->string('instansi')->nullable();
            $table->json('jawaban');
            // Audit trail
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_rapat_respon');
    }
};
