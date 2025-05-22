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
        Schema::create('staff_pendidikans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_verifikasi_id');
            $table->string('kode'); // SD, SMP, SMA
            $table->string('nama_sekolah');
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
        Schema::dropIfExists('staff_pendidikans');
    }
};
