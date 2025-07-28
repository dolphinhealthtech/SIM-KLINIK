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
        Schema::table('asuransis', function (Blueprint $table) {
            $table->string('jenis_asuransi');
            $table->string('verif_pasien');
            $table->string('filter_obat');
            $table->string('tanggal_mulai');
            $table->string('tanggal_akhir');
            $table->string('alamat_asuransi')->nullable();
            $table->string('no_telp_asuransi')->nullable();
            $table->string('faksimil')->nullable();
            $table->string('pic')->nullable();
            $table->string('no_telp_pic')->nullable();
            $table->string('jabatan_pic')->nullable();
            $table->string('bank')->nullable();
            $table->string('no_rekening')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asuransis', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_asuransi',
                'verif_pasien',
                'filter_obat',
                'tanggal_mulai',
                'tanggal_akhir',
                'alamat_asuransi',
                'no_telp_asuransi',
                'faksimil',
                'pic',
                'no_telp_pic',
                'jabatan_pic',
                'bank',
                'no_rekening',
            ]);
        });
    }
};
