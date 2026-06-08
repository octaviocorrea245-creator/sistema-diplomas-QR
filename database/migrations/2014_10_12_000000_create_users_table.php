<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
<<<<<<< HEAD
    /**
     * Run the migrations.
     */
=======
>>>>>>> master
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
=======
            $table->string('full_name'); // Cambiado de 'name' a 'full_name' (opcional)
            $table->string('username')->unique();
            // Se eliminaron las líneas de email y email_verified_at
            $table->string('role')->default('beneficiario');
            $table->unsignedBigInteger('department_id')->nullable();
>>>>>>> master
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

<<<<<<< HEAD
    /**
     * Reverse the migrations.
     */
=======
>>>>>>> master
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
<<<<<<< HEAD
};
=======
};
>>>>>>> master
