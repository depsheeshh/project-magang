<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instansi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_instansi');
            $table->string('lokasi')->nullable();
            $table->string('alias');
            $table->string('jenis');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instansi');
    }
};
