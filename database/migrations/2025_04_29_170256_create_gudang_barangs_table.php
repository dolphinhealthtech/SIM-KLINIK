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
        Schema::create('gudang_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->string('jenis_formularium');
            $table->string('kfa_kode');
            $table->string('nama_industri_barang');
            $table->string('satuan_kecil');
            $table->string('satuan_sedang');
            $table->string('satuan_besar');
            $table->integer('nilai_satuan_kecil');
            $table->integer('nilai_satuan_sedang');
            $table->integer('nilai_satuan_besar');
            $table->string('tempat_penyimpanan');
            $table->string('barcode');
            $table->string('gudang_kategori');
            $table->string('jenis_obat');
            $table->string('jenis_generik');
            $table->string('bentuk_sediaan');
            $table->string('user_input_id');
            $table->string('user_input_nama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_barangs');
    }
};
