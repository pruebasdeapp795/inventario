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
            $table->decimal('valor_diferencia', 15, 2)->default(0)->after('diferencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ciclico_items', function (Blueprint $table) {
            $table->dropColumn('valor_diferencia');
        });
    }
};
