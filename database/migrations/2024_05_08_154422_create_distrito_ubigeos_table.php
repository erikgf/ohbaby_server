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
        Schema::create('distrito_ubigeos', function (Blueprint $table) {
            $table->char("id", 6)->primary();
            $table->string("name", 300);
            $table->char("id_provincia_ubigeo", 4);
            $table->char("id_departamento_ubigeo", 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distrito_ubigeos');
    }
};
