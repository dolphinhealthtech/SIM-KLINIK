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
        Schema::create('kasir_tindakan_lunas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_faktur');
            $table->string('no_rawat')->nullable();
            $table->string('no_rm');
            $table->string('nama');
            $table->string('nama_tindakan');
            $table->string('harga_tindakan');
            $table->string('pelaksana');
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
        Schema::dropIfExists('kasir_tindakan_lunas');
    }
};
