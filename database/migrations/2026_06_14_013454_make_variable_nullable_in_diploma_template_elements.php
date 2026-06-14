<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('diploma_template_elements', function (Blueprint $table) {
            $table->string('variable')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('diploma_template_elements', function (Blueprint $table) {
            $table->string('variable')->nullable(false)->change();
        });
    }
};
