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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('created_id')->nullable()->after('remember_token');
            $table->unsignedBigInteger('updated_id')->nullable()->after('created_id');
            $table->unsignedBigInteger('deleted_id')->nullable()->after('updated_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['created_id','updated_id','deleted_id']);
        });
    }
};
