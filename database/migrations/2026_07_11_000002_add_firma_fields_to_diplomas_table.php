<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diplomas', function (Blueprint $table) {
            // Referencia al firmante que firmó este diploma
            $table->foreignId('firmante_id')
                ->nullable()
                ->after('emitido_por')
                ->constrained('firmantes')
                ->nullOnDelete();

            // Cuándo se firmó digitalmente
            $table->timestamp('firmado_en')
                ->nullable()
                ->after('firmante_id');

            // Si el PDF tiene firma criptográfica incrustada
            $table->boolean('tiene_firma_digital')
                ->default(false)
                ->after('firmado_en');

            // Número de serie del certificado usado al momento de firmar
            // (útil si el DPA renueva su e.firma y queremos saber con cuál se firmó)
            $table->string('cert_serie_usada')
                ->nullable()
                ->after('tiene_firma_digital');
        });
    }

    public function down(): void
    {
        Schema::table('diplomas', function (Blueprint $table) {
            $table->dropForeign(['firmante_id']);
            $table->dropColumn([
                'firmante_id',
                'firmado_en',
                'tiene_firma_digital',
                'cert_serie_usada',
            ]);
        });
    }
};
