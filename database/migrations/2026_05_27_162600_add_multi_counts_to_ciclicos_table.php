<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ciclicos', function (Blueprint $table) {
            $table->integer('intento_actual')->default(1); // 1, 2, 3
        });

        Schema::table('ciclico_items', function (Blueprint $table) {
            $table->decimal('conteo_1', 15, 2)->nullable();
            $table->decimal('conteo_2', 15, 2)->nullable();
            $table->decimal('conteo_3', 15, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ciclicos', function (Blueprint $table) {
            $table->dropColumn('intento_actual');
        });
        Schema::table('ciclico_items', function (Blueprint $table) {
            $table->dropColumn(['conteo_1', 'conteo_2', 'conteo_3']);
        });
    }
};
