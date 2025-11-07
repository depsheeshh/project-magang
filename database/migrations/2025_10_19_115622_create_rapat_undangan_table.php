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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('instansi_id')->nullable()->constrained('instansi')->onDelete('cascade'); // asal peserta

            $table->string('jabatan')->nullable();


            // Status kehadiran
            $table->enum('status_kehadiran', ['pending','hadir','tidak_hadir','selesai'])->default('pending');

            // Data check-in
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->unsignedBigInteger('checked_in_by')->nullable();
            $table->decimal('checkin_latitude', 10, 7)->nullable();
            $table->decimal('checkin_longitude', 10, 7)->nullable();
            $table->integer('checkin_distance')->nullable();
            $table->integer('keterlambatan_menit')->nullable();

            // QR Code check-in
            $table->string('checkin_token')->nullable()->unique();
            $table->string('checkin_token_hash')->nullable();
            $table->timestamp('qr_scanned_at')->nullable();

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
        Schema::dropIfExists('rapat_undangan');
    }
};
