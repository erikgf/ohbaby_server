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
        Schema::create('asistencia_empleados', function (Blueprint $table) {
            $table->id();
            $table->date("fecha")->index();
            $table->unsignedBigInteger("id_empleado_contrato")->index();
            $table->time("hora_entrada");
            $table->dateTime("fecha_hora_entrada");
            $table->time("hora_salida");
            $table->dateTime("fecha_hora_salida");
            $table->integer("numero_dia_semana");
            $table->unsignedBigInteger("id_punto_acceso");
            $table->float("total_horas")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia_empleados');
    }
};
