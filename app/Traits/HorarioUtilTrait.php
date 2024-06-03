<?php

namespace App\Traits;

trait HorarioUtilTrait {

    public function validarSoloSemanaDias(string $dias){
        $diasSemana = [1,2,3,4,5,6];
        $arregloDias = explode(",", $dias);

        return !(bool) (array_intersect($diasSemana, $arregloDias));
    }

    public function verificarHorarioDetalleNoCruzan(array $horarioDetalles) : bool {
        $seCruza = false;

        /*

        1:00 - 9:00
        1,2,3,4,5
        vs
        14:00 - 20:00
        1,2,3,4,5
        vs
        8:00 - 14:00
        6

        */
        foreach ($horarioDetalles as $detalle) {


        }


        return $seCruza;
    }
}
