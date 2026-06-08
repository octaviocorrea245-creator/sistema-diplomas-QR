<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name'); // Cambiado de 'name' a 'full_name' (opcional)
            $table->string('username')->unique();
            // Se eliminaron las líneas de email y email_verified_at
            $table->string('role')->default('beneficiario');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};