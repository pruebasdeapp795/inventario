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
        Schema::create('inventario_saps', function (Blueprint $table) {
            $table->id();
            $table->string('material')->index();
            $table->string('texto_breve_de_material')->nullable();
            $table->string('unidad_medida_base')->nullable();
            $table->string('centro')->nullable();
            $table->string('almacen')->nullable();
            $table->decimal('libre_utilizacion', 15, 3)->default(0);
            $table->decimal('valor_libre_util', 15, 2)->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_saps');
    }
};
