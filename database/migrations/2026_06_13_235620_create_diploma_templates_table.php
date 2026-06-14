<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diploma_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->unique()->constrained('cursos')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('background_image')->nullable();
            $table->integer('canvas_width')->default(800);
            $table->integer('canvas_height')->default(600);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diploma_templates');
    }
};
