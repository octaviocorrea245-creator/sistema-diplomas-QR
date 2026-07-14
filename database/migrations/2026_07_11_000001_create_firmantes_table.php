<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('firmantes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('departamento_id')
                ->constrained('departamentos')
                ->cascadeOnDelete();

            // Datos del firmante
            $table->string('nombre');
            $table->string('cargo')->default('Director de Programa Académico');

            // Rutas de los archivos de e.firma (guardados en storage/app/private/)
            $table->string('cer_path');   // ruta del .cer (certificado público)
            $table->string('key_path');   // ruta del .key (llave privada cifrada)

            // Datos extraídos del certificado (se llenan al subir el .cer)
            $table->string('rfc')->nullable();
            $table->string('certificado_numero')->nullable();
            $table->timestamp('cert_valido_desde')->nullable();
            $table->timestamp('cert_expira_en')->nullable();

            // Estado
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firmantes');
    }
};
