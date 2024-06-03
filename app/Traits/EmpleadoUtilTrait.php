<?php

namespace App\Traits;

trait EmpleadoUtilTrait {

    public function calcularCostosDiaHora(float $salario, float $dias_trabajo, float $horas_dia){
        if ($dias_trabajo <= 0){
            $costo_dia = 0;
        } else {
            $costo_dia = round($salario / $dias_trabajo, 2);
        }

        if ($horas_dia <= 0){
            $costo_hora = 0;
        } else{
            $costo_hora = round( $costo_dia / $horas_dia , 2);
        }

        return [
            "costo_hora"=>$costo_hora,
            "costo_dia"=>$costo_dia
        ];
    }
}
