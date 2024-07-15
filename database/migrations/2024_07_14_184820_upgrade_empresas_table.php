<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string("numero_documento", 15);
            $table->string("razon_social");
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('empresas')->insert([
            ['numero_documento' => '20608742671', 'razon_social' => 'Confecciones Mark'],
            ['numero_documento' => '20602708609', 'razon_social' => 'OH! BABY CORP EIRL'],
            ['numero_documento' => '20602723624', 'razon_social' => 'EL PUNTO TEXTIL EIRL'],
            ['numero_documento' => '99999999999', 'razon_social' => 'VARIOS']
        ]);

        Schema::table('empleados', function (Blueprint $table) {
            $table->bigInteger("id_empresa")->nullable();
            $table->integer("numero_orden")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn("id_empresa");
            $table->dropColumn("numero_orden");
        });

        Schema::dropIfExists('empresas');
    }
};
