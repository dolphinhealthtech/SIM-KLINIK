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
        Schema::create('role_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('role_id'); // Kunci asing untuk tabel roles
            $table->string('redirect_route');
            $table->timestamps();
            $table->foreign('role_id')->references('name')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_redirects');
    }
};
