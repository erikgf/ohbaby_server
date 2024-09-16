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
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_tipo_entrega");
            $table->foreignId("id_empleado_contrato");
            $table->string("motivo", 300)->nullable();
            $table->date("fecha_registro");
            $table->float("monto_registrado", 10, 2)->default("0.00");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("id_tipo_entrega")->references("id")->on("tipo_entregas");
            $table->foreign("id_empleado_contrato")->references("id")->on("empleado_contratos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
