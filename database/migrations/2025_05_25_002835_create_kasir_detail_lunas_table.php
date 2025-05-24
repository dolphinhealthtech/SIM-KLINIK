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
        Schema::create('kasir_detail_lunas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_faktur');
            $table->string('no_rawat')->nullable();
            $table->string('no_rm');
            $table->string('nama');
            $table->string('nama_obat_tindakan');
            $table->string('harga_obat_tindakan');
            $table->string('qty_pelaksana');
            $table->string('total');
            $table->string('tanggal');
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
        Schema::dropIfExists('kasir_detail_lunas');
    }
};
