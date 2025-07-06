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
        Schema::create('perawatan_tindakans', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->foreignId('perawatan_kategori_id');
            $table->string('tarif_dokter');
            $table->string('tarif_perawat');
            $table->string('tarif_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perawatan_tindakans');
    }
};
