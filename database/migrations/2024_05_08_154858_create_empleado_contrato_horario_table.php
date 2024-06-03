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
        Schema::create('empleado_contrato_horario', function (Blueprint $table) {
            $table->unsignedBigInteger('id_empleado_contrato');
            $table->unsignedBigInteger('id_horario');
            $table->timestamps();

            // Additional columns for pivot data
            // $table->string('additional_data');
            $table->foreign('id_empleado_contrato')->references('id')->on('empleado_contratos');
            $table->foreign('id_horario')->references('id')->on('horarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado_contrato_horario');
    }
};
