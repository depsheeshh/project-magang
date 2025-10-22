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
        Schema::create('history_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();   // siapa yang melakukan aksi
            $table->string('action');                // created, updated, deleted, restored
            $table->string('table_name');            // tabel yang diubah
            $table->unsignedBigInteger('record_id'); // id record yang diubah
            $table->json('old_values')->nullable();  // data sebelum perubahan
            $table->json('new_values')->nullable();  // data setelah perubahan
            $table->text('reason')->nullable();      // alasan/keterangan aksi

            // audit trail standar
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_logs');
    }
};
