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
        Schema::table('empleado_contratos', function (Blueprint $table) {
            $table->text("observaciones_fin_contrato")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleado_contratos', function (Blueprint $table) {
            $table->dropColumn("observaciones_fin_contrato");
        });
    }
};
