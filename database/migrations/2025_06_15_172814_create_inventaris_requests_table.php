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
        Schema::create('inventaris_requests', function (Blueprint $table) {
            $table->id();
            $table->string('kode_request');
            $table->string('kode_klinik');
            $table->string('nama_klinik');
            $table->string('status');
            $table->string('tanggal_input');
            $table->string('user_input_id');
            $table->string('user_input_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_requests');
    }
};
