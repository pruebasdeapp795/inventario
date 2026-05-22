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
        Schema::table('inventario_saps', function (Blueprint $table) {
            $table->boolean('seleccionado')->default(false)->after('valor_libre_util');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventario_saps', function (Blueprint $table) {
            $table->dropColumn('seleccionado');
        });
    }

};
