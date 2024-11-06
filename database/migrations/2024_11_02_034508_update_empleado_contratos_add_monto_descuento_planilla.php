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
        //
        Schema::table('empleado_contratos', function (Blueprint $table) {
            $table->float("descuento_planilla")->nullable();
            $table->float("horas_semana")->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleado_contratos', function (Blueprint $table) {
            $table->dropColumn("descuento_planilla");
            $table->dropColumn("horas_semana");
        });
    }
};
