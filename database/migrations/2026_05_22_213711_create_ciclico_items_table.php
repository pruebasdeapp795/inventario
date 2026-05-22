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
        Schema::create('ciclico_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciclico_id')->constrained('ciclicos')->onDelete('cascade');
            $table->string('material');
            $table->string('descripcion')->nullable();
            $table->string('centro')->nullable();
            $table->string('almacen')->nullable();
            $table->decimal('stock_sap', 15, 3)->default(0);
            $table->string('um')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclico_items');
    }
};
