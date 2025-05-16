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
        Schema::create('gudang_klinik_request_details', function (Blueprint $table) {
            $table->id();
            $table->string('kode_request');
            $table->string('kode_obat_alkes');
            $table->string('nama_obat_alkes');
            $table->string('qty');
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
        Schema::dropIfExists('gudang_klinik_request_details');
    }
};
