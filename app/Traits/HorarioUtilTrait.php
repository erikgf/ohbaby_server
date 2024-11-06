<?php

namespace App\Traits;

trait HorarioUtilTrait {

    public function validarSoloSemanaDias(string $dias){
        $diasSemana = [1,2,3,4,5,6];
        $arregloDias = explode(",", $dias);

        return !(bool) (array_intersect($diasSemana, $arregloDias));
    }

    public function obtenerHorasSemanaHorarioDetalle(string $dias, string $horaInicio, string $horaFin){
        $arHorarioInicioDecimal = explode(":", $horaInicio);
        $horarioInicioDecimal = (float) $arHorarioInicioDecimal[0] + $arHorarioInicioDecimal[1]/60 + (count($arHorarioInicioDecimal) > 2 ? ($arHorarioInicioDecimal[2]/3600) : 0);
        $arHorarioFinDecimal = explode(":", $horaFin);
        $horarioFinDecimal = (float) $arHorarioFinDecimal[0] + $arHorarioFinDecimal[1]/60 + (count($arHorarioFinDecimal) > 2 ? ($arHorarioFinDecimal[2]/3600) : 0);

        $totalHorasDecimal = $horarioFinDecimal - $horarioInicioDecimal;
        $cantidadDias = count(explode(",", $dias));

        return $totalHorasDecimal * $cantidadDias;
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
