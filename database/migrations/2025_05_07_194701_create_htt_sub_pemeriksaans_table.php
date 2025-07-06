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
        Schema::create('htt_sub_pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->string('htt_pemeriksaan_id');
            $table->string('nama_pemeriksaan');
            $table->string('nama_subpemeriksaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('htt_sub_pemeriksaans');
    }
};
