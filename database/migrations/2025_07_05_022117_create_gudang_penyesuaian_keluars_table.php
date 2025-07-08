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
        Schema::create('gudang_penyesuaian_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('kode_obat');
            $table->string('nama_obat');
            $table->string('qty_sebelum');
            $table->string('qty_mutasi');
            $table->string('qty_sesudah');
            $table->string('jenis_penyesuaian');
            $table->string('alasan');
            $table->string('tanggal');
            $table->string('jam');
            $table->string('harga');
            $table->string('expired');
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
        Schema::dropIfExists('gudang_penyesuaian_keluars');
    }
};
