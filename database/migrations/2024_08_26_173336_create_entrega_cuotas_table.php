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
        Schema::create('entrega_cuotas', function (Blueprint $table) {
            $table->id();
            $table->integer("numero_cuota");
            $table->foreignId("id_entrega");
            $table->date("fecha_cuota");
            $table->float("monto_cuota");
            $table->char("es_entregado", 1)->default("1")->comment("1: Sí entregado al personal, 0: No entregado al personal");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("id_entrega")->references("id")->on("entregas");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega_cuotas');
    }
};
