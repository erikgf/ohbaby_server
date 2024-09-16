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
        Schema::table('asistencia_registro_empleados', function (Blueprint $table) {
            $table->time("hora_entrada_mañana")->nullable()->change();
            $table->time("hora_salida_mañana")->nullable()->change();
            $table->time("hora_entrada_tarde")->nullable()->change();
            $table->time("hora_salida_tarde")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencia_registro_empleados', function (Blueprint $table) {
            $table->time("hora_entrada_mañana")->change();
            $table->time("hora_salida_mañana")->change();
            $table->time("hora_entrada_tarde")->change();
            $table->time("hora_salida_tarde")->change();
        });
    }
};
