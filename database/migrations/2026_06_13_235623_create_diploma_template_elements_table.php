<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diploma_template_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('diploma_templates')->cascadeOnDelete();
            $table->string('tipo'); // texto, qr
            $table->string('variable'); // nombre_alumno, qr, curso, folio, fecha_emision, calificacion
            $table->float('x')->default(0);
            $table->float('y')->default(0);
            $table->float('width')->default(200);
            $table->float('height')->default(50);
            $table->json('config_json')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diploma_template_elements');
    }
};
