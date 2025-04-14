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
        Schema::create('dokter_pendidikan_spesialis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokter_verifikasi_id');
            $table->string('nama');
            $table->string('institusi');
            $table->string('tahun_lulus');
            $table->string('ijasah')->nullable(); // file path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokter_pendidikan_spesialis');
    }
};
