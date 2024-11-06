<?php

namespace Database\Seeders;

use App\Models\EmpleadoContrato;
use App\Models\Horario;
use Illuminate\Database\Seeder;

class EmpleadoContratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monto_descuento_planilla = config("globals.MONTO_DESCUENTO_PLANILLA");

        $horario = Horario::find(10);
        $horario->total_horas_semana = 59.50;
        $horario->save();

        EmpleadoContrato::query()->update([
            "horas_semana"=>59.50
        ]);

        EmpleadoContrato::query()
            ->whereHas("empleado", function($q){
                $q->where("id_empresa", "<>", 4);
            })
            ->update([
                "descuento_planilla"=>$monto_descuento_planilla
            ]);


        EmpleadoContrato::query()
            ->whereHas("empleado", function($q){
                $q->where("id_empresa", "=", 4);
            })
            ->update([
                "descuento_planilla"=>"0.00"
            ]);

    }
}
