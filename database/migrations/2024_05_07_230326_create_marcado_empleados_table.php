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
        Schema::create('marcado_empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_empleado_contrato");
            $table->time("hora");
            $table->date("fecha");
            $table->integer("numero_dia_semana");
            $table->unsignedBigInteger("id_punto_acceso");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marcado_empleados');
    }
};
