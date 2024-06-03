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
        Schema::create('empleado_contratos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_empleado");
            $table->date("fecha_inicio");
            $table->date("fecha_fin")->nullable();
            $table->decimal("salario", 10, 2);
            $table->decimal("costo_hora", 10, 2);
            $table->decimal("costo_dia", 10, 2);
            $table->integer("dias_trabajo");
            $table->decimal("horas_dia", 4, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado_contratos');
    }
};
