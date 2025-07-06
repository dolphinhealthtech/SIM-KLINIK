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
        Schema::create('laboratorium_bidang_subs', function (Blueprint $table) {
            $table->id();
            $table->string('laboratorium_bidang_id');
            $table->string('nama_laboratorium_bidang');
            $table->string('nama_sublaboratorium_bidang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorium_bidang_subs');
    }
};
