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
        Schema::table('empleados', function (Blueprint $table) {
            $table->string("celular")->nullable();
            $table->char("sexo", 1)->nullable();
            $table->char("estado_civil", 1)->nullable();
            $table->string("puesto")->nullable();
            $table->string("telefono_referencia", 20)->nullable();
            $table->string("nombre_familiar")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn("celular");
            $table->dropColumn("sexo");
            $table->dropColumn("estado_civil");
            $table->dropColumn("puesto");
            $table->dropColumn("telefono_referencia");
            $table->dropColumn("nombre_familiar");
        });
    }
};
