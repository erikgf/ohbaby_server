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
            default:
                return "NINGUNO";
        }
    }
}
