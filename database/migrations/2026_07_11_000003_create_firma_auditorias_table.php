<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('firma_auditorias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('diploma_id')
                  ->constrained('diplomas')
                  ->cascadeOnDelete();

            $table->foreignId('firmante_id')
                  ->constrained('firmantes')
                  ->cascadeOnDelete();

            $table->foreignId('usuario_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // 'individual' o 'masivo'
            $table->string('accion', 20)->default('individual');

            // Número de serie del certificado usado en el momento de la firma
            $table->string('cert_serie', 100)->nullable();

            // IP desde donde se ejecutó la firma
            $table->string('ip', 45)->nullable();

            // Solo created_at (el registro no se modifica)
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firma_auditorias');
    }
};
