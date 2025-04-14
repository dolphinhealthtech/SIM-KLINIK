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
        Schema::create('dokter_verifikasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokter_id')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('norek')->nullable();
            $table->string('cabang_bank')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokter_verifikasis');
    }
};
