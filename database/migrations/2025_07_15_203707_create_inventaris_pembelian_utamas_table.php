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
        Schema::create('inventaris_pembelian_utamas', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('tanggal_pembelian');
            $table->string('total_harga');
            $table->string('petugas_penerima');
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
        Schema::dropIfExists('inventaris_pembelian_utamas');
    }
};
