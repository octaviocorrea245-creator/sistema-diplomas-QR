<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                ->constrained('departamentos')
                ->cascadeOnDelete();

            $table->string('nombre');

            $table->text('descripcion')
                ->nullable();

            $table->enum('tipo', [
                'global',
                'grupo',
                'individual'
            ]);

            $table->boolean('activa')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantillas');
    }
};