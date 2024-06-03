<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\EmpleadoContrato;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empleados = Empleado::factory(10)->create();

        foreach ($empleados as $key => $value) {
            EmpleadoContrato::factory(1)->create([
                "id_empleado"=>$value->id
            ]);
        }
    }
}
