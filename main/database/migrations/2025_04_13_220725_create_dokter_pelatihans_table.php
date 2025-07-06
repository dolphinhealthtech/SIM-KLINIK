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
        Schema::create('dokter_pelatihans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokter_verifikasi_id');
            $table->string('nama');
            $table->string('penyelenggara');
            $table->string('tahun');
            $table->string('sertifikat')->nullable(); // file path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokter_pelatihans');
    }
};
