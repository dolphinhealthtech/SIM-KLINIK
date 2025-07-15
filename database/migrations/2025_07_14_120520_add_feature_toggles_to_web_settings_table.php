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
        Schema::table('web_settings', function (Blueprint $table) {
            $table->boolean('is_bpjs_active')->default(true);
            $table->boolean('is_satusehat_active')->default(true)->after('is_bpjs_active');
            $table->boolean('is_gudangutama_active')->default(true)->after('is_satusehat_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->dropColumn(['is_bpjs_active', 'is_satusehat_active', 'is_gudangutama_active']);
        });
    }
};
