<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;

class ReordenarEmpleadosEmpresa extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $empresas = Empresa::query()
                ->with("empleados", fn($q) => $q->orderBy("fecha_ingreso"))
                ->get();

            $empresas->each(function($item){
                foreach($item->empleados as $i => $empleado){
                    $empleado->update(["numero_orden" => $i + 1]);
                }
            });
        });

    }
}
