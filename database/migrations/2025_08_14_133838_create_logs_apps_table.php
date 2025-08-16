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
        Schema::create('logs_apps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username')->nullable();
            $table->string('activity');
            $table->integer('response_status')->nullable(); // ubah jadi integer
            $table->string('ip_address', 45)->nullable();   // panjang 45 untuk IPv6
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('device')->nullable();
            $table->enum('is_api', ['Yes', 'No'])->default('No');
            $table->string('method', 10);
            $table->time('time')->nullable();
            $table->json('payload')->nullable();
            $table->longText('response_body')->nullable(); // untuk menyimpan isi response
            $table->decimal('execution_ms', 8, 2)->nullable(); // untuk waktu eksekusi dalam ms
            $table->timestamps(); // created_at & updated_at otomatis

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_apps');
    }
};
