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
        Schema::create('gudang_stok_opname_utamas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_obat');
            $table->string('nama_obat');
            $table->string('expired');
            $table->string('qty');
            $table->string('alasan');
            $table->string('harga');
            $table->string('tanggal');
            $table->string('jam');
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
        Schema::dropIfExists('gudang_stok_opname_utamas');
    }
};
