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
        Schema::table('pasiens', function (Blueprint $table) {
            $table->string('penjamin_2_nama')->nullable();
            $table->string('penjamin_3_nama')->nullable()->after('penjamin_2_nama');

            $table->string('penjamin_2_no')->nullable();
            $table->string('penjamin_3_no')->nullable()->after('penjamin_2_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            //
        });
    }
};
