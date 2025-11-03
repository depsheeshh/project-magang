<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_ruangan');
            $table->unsignedBigInteger('id_kantor')->index();

            // kapasitas maksimal ruangan
            $table->integer('kapasitas_maksimal')->default(0);

            // status dipakai / tidak
            $table->integer('dipakai')->default(false);

            // Audit fields
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();

            // relasi opsional ke tabel kantor
            // $table->foreign('id_kantor')->references('id')->on('kantor')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
