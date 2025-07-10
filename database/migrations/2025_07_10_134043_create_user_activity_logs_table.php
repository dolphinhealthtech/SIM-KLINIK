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
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('username')->nullable(); // untuk menyimpan username jika user_id tidak ada
            $table->string('activity'); // misalnya: 'akses halaman pasien/tambah'
            $table->string('method', 10)->nullable(); // GET, POST, etc
            $table->text('url')->nullable(); // URL lengkap
            $table->ipAddress('ip_address')->nullable(); // IP user
            $table->text('user_agent')->nullable(); // browser/device info
            $table->json('payload')->nullable(); // data yang dikirim user (tanpa password)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity_logs');
    }
};
