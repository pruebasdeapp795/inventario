<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materiales_sap', function (Blueprint $table) {
            $table->id();
            $table->string('cod')->unique()->comment('Código SAP del material');
            $table->string('material')->comment('Descripción del material');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiales_sap');
    }
};
