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
        Schema::table('empleado_contratos', function (Blueprint $table) {
            $table->decimal("salario", 12, 3)->change();
            $table->decimal("costo_hora", 12, 3)->change();
            $table->decimal("costo_dia", 12, 3)->change();
            $table->decimal("descuento_planilla", 12, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->decimal("salario", 12, 2)->change();
            $table->decimal("costo_hora", 12, 2)->change();
            $table->decimal("costo_dia", 12, 2)->change();
            $table->decimal("descuento_planilla", 12, 2)->change();
        });
    }
};
