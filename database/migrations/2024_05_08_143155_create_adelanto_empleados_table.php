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
        Schema::create('adelanto_empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_empleado_contrato");
            $table->date("fecha");
            $table->decimal("importe", 10, 3);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adelanto_empleados');
    }
};
