<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_usuario', function (Blueprint $table) {
            $table->id();

            $table->foreignId('curso_id')
                ->constrained('cursos')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('estado', 20)->default('inscrito');

            $table->timestamp('fecha_completado')
                ->nullable();

            $table->timestamps();

            $table->unique(['curso_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_usuario');
    }
};