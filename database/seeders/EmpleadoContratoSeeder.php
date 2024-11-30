<?php

namespace Database\Seeders;

use App\Models\EmpleadoContrato;
use App\Models\Horario;
use App\Services\HorarioEmpleadoContratoService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpleadoContratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        $monto_descuento_planilla = config("globals.MONTO_DESCUENTO_PLANILLA");

        $id_horario = 10;
        $horario = Horario::find($id_horario);
        $horario->total_horas_semana = 59.50;
        $horario->save();

        EmpleadoContrato::query()->update([
            "horas_semana"=>59.50
        ]);

        $empleadosContrato = EmpleadoContrato::query()->get();
        $empleadosContratoIds = $empleadosContrato->map( fn ($item) => $item->id)->toArray();

        (new HorarioEmpleadoContratoService)->registrar($id_horario, $empleadosContratoIds);

        $dias_trabajo = config("globals.DIAS_TRABAJO_MENSUAL");

        foreach ($empleadosContrato as $contrato) {
            $costos = $this->calcularCostosDiaHora($contrato->salario, $dias_trabajo, $contrato->horas_semana);
            $contrato->update($costos);
        }

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

        DB::commit();
    }

    private function calcularCostosDiaHora(float $salario, float $dias_trabajo, float $horas_semana){
        $dias_en_semana = 6;
        if ($dias_trabajo <= 0){
            $costo_dia = 0;
        } else {
            $costo_dia = round($salario / $dias_trabajo, 2);
        }

        $sueldo_semanal =  round($costo_dia * $dias_en_semana, 2);
        $costo_hora = round($sueldo_semanal / $horas_semana, 2);
        $horas_dia = round($horas_semana / $dias_en_semana, 2);

        return [
            "costo_hora"=>$costo_hora,
            "costo_dia"=>$costo_dia,
            "horas_dia"=>$horas_dia
        ];
    }
}
