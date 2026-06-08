<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantilla_curso', function (Blueprint $table) {
            $table->id();

            $table->foreignId('plantilla_id')
                ->constrained('plantillas')
                ->cascadeOnDelete();

            $table->foreignId('curso_id')
                ->constrained('cursos')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'plantilla_id',
                'curso_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantilla_curso');
    }
};