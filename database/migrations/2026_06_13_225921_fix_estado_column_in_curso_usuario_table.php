<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE curso_usuario MODIFY COLUMN estado VARCHAR(20) NOT NULL DEFAULT 'inscrito'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE curso_usuario MODIFY COLUMN estado ENUM('inscrito', 'completado', 'cancelado') NOT NULL DEFAULT 'inscrito'");
    }
};
