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
        Schema::create('inventaris_data_barang_utamas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->string('kategori_barang');
            $table->string('satuan_barang');
            $table->string('jenis_barang');
            $table->string('masa_pakai_barang');
            $table->string('masa_pakai_waktu_barang');
            $table->string('deskripsi_barang');
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
        Schema::dropIfExists('inventaris_data_barang_utamas');
    }
};
