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
        Schema::create('pelayanan_soap_dokter_diets', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rm');
            $table->string('nama');
            $table->string('no_rawat');
            $table->string('sex');
            $table->string('penjamin');
            $table->date('tanggal_lahir');
            $table->string('Jenis_diet')->nullable();
            $table->string('jenis_diet_makanan')->nullable();
            $table->string('jenis_diet_makanan_tidak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanan_soap_dokter_diets');
    }
};
