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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->char("id_tipo_documento", 1);
            $table->string("numero_documento", 15);
            $table->string("apellido_paterno", 300);
            $table->string("apellido_materno", 300);
            $table->string("nombres", 300);
            $table->char("codigo_unico", 3);
            $table->string("direccion", 300)->nullable();
            $table->char("distrito_ubigeo", 6)->nullable();
            $table->char("pais", 2)->nullable();
            $table->date("fecha_nacimiento")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
