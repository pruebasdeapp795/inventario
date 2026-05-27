<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ciclico_items', function (Blueprint $table) {
            $table->boolean('seleccionado')->default(false)->after('um');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ciclico_items', function (Blueprint $table) {
            $table->dropColumn('seleccionado');
        });
    }
};
