<?php

namespace Database\Seeders;

use App\Models\PuntoAcceso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PuntoAccesoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PuntoAcceso::truncate();

        PuntoAcceso::create([
            "descripcion"=>"ENTRADA"
        ]);
    }
}
