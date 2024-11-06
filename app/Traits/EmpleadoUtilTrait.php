<?php

namespace App\Traits;

trait EmpleadoUtilTrait {

    public function calcularCostosDiaHora(float $salario, float $dias_trabajo, float $horas_semana){
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
