<?php

namespace App\Traits;

trait FechaUtilTrait {

    public function getMesNombre(string $mes){
        switch($mes){
            case "01":
                return "ENERO";
            case "02":
                return "FEBRERO";
            case "03":
                return "MARZO";
            case "04":
                return "ABRIL";
            case "05":
                return "MAYO";
            case "06":
                return "JUNIO";
            case "07":
                return "JULIO";
            case "08":
                return "AGOSTO";
            case "09":
                return "SETIEMBRE";
            case "10":
                return "OCTUBRE";
            case "11":
                return "NOVIEMBRE";
            case "12":
                return "DICIEMBRE";
            default:
                return "NINGUNO";
        }
    }

    public function getDiaSemanaNombre($dia){
        switch($dia){
            case "0":
                return "DOMINGO";
            case "1":
                return "LUNES";
            case "2":
                return "MARTES";
            case "3":
                return "MIÉRCOLES";
            case "4":
                return "JUEVES";
            case "5":
                return "VIERNES";
            case "6":
                return "SÁBADO";
            case "7":
                return "DOMINGO";
            default:
                return "NINGUNO";
        }
    }

    public function tiempoDecimalPorHora($hora){
        if ($hora == null){
            return 0;
        }

        $cadenaHoras = strlen($hora);
        $esCadenaValida = $cadenaHoras === 8 || $cadenaHoras === 5;
        $esConsideraSegundos = $cadenaHoras === 8;

        $arregloHoras = explode(":", $hora);
        $esArregloValido = $esConsideraSegundos
                                ? count($arregloHoras) == 3
                                : count($arregloHoras) == 2;

        $esHoraValida =  $esCadenaValida && $esArregloValido;

        if (!$esHoraValida){
            return 0;
        }

        if ($esConsideraSegundos){
            [$hora, $min, $seg] = $arregloHoras;
        } else {
            [$hora, $min] = $arregloHoras;
            $seg = 0;
        }

        $horasDec = $hora * 1.0;
        $minDec = $min / 60.0;
        $segDec = $seg / 3600.00;

        return $horasDec + $minDec + $segDec;
    }
}
