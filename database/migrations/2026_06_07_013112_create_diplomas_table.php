<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diplomas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('curso_id')
                ->constrained('cursos')
                ->cascadeOnDelete();

            $table->foreignId('version_plantilla_id')
                ->constrained('versiones_plantilla')
                ->restrictOnDelete();

            $table->foreignId('emitido_por')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('folio')
                ->unique();

            $table->string('token_qr')
                ->unique();

            $table->string('ruta_pdf');

            $table->timestamp('fecha_emision');

            $table->enum('estado', [
                'emitido',
                'revocado',
                'reemitido'
            ])->default('emitido');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diplomas');
    }
};