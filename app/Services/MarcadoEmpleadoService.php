<?php

namespace App\Services;

use App\Models\Empleado;
use App\Models\MarcadoEmpleado;
use Carbon\Carbon;

class MarcadoEmpleadoService{

    public function registrar(string $codigoUnico) : array {

        $empleado= Empleado::with("contratoActivo")
                            ->where(["codigo_unico"=>$codigoUnico])
                            ->first();

        if (!$empleado){
            return [
                "ok"=>0,
                "msg"=>"No encontrado"
            ];
        }

        $contratoActivo = $empleado?->contratoActivo;
        if (!$contratoActivo){
            return [
                "ok"=>0,
                "msg"=>"Personal sin contrato activo"
            ];
        }

        $nombreEmpleado = $empleado->nombres." ".$empleado->apellido_paterno." ".$empleado->apellido_materno;

        $idEmpleadoContrato = $contratoActivo->id;
        $moment = Carbon::now();
        $fecha = $moment->format("Y-m-d");
        $hora  = $moment->format("H:i:s");
        $numeroDiaSemana = $moment->dayOfWeek;

        //Por ahora se considera un punto de acceso.
        $idPuntoAcceso  = 1;

        MarcadoEmpleado::create([
            "id_empleado_contrato"=>$idEmpleadoContrato,
            "hora"=>$hora,
            "fecha"=>$fecha,
            "numero_dia_semana"=>$numeroDiaSemana,
            "id_punto_acceso"=>$idPuntoAcceso
        ]);

        return [
            "ok"=>1,
            "msg"=>"Asistencia registrada",
            "label"=>$nombreEmpleado,
            "hora"=>$hora
        ];
    }

}
