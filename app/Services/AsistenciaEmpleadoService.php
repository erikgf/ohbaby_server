<?php

namespace App\Services;

use App\Models\Empleado;
use App\Models\AsistenciaEmpleado;
use App\Traits\FechaUtilTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AsistenciaEmpleadoService{

    use FechaUtilTrait;

    public function consultarEmpleadosPorFecha(string $texto, string $fecha){
        return Empleado::query()
                        ->withWhereHas("contratos", function($q) use($fecha){
                            $q->whereNull("fecha_fin");
                            $q->orWhere( function($q) use ($fecha) {
                            $q->where("fecha_inicio", "<=", $fecha);
                            $q->where("fecha_fin", ">=", $fecha);
                            });
                            $q->select("id","id_empleado");
                        })
                        ->where(function($q) use($texto){
                        $q->where("codigo_unico","LIKE", "%{$texto}%")
                            ->orWhere(DB::raw("CONCAT(nombres,' ',apellido_paterno,' ',apellido_materno)"),"LIKE", "%{$texto}%");
                            })
                        ->select("id", "id_empresa", "codigo_unico", "apellido_paterno", "apellido_materno", "nombres")
                        ->orderBy("numero_orden")
                        ->get();
    }

    public function consultarAsistenciaDia(string $fecha){
        return  AsistenciaEmpleado::query()
                    ->with([
                        "empleado" => function($q)  {
                            $q->select("id", "id_empleado");
                        },
                        "empleado.empleado" => function($q) {
                            $q->select("id", "id_empresa", "codigo_unico", "nombres", "apellido_paterno","apellido_materno");
                            $q->with("empresa");
                        }
                    ])
                    ->where([
                        "fecha"=>$fecha,
                        "id_punto_acceso"=>1
                    ])
                    ->select("id", "id_empleado_contrato","hora_entrada", "hora_salida", "total_horas")
                    ->orderBy("id", "desc")
                    ->get();

    }

    public function eliminarAsistencia(int $id){
        return  AsistenciaEmpleado::query()
                        ->where([
                            "id"=>$id
                        ])
                        ->delete();
    }

    public function guardarAsistencia(string $fecha, int $id_empleado_contrato, string $hora_entrada, string $hora_salida){
        $carbonFecha = Carbon::parse($fecha);

        $numero_dia_semana = $carbonFecha->dayOfWeek;
        $decimal_entrada = $this->tiempoDecimalPorHora($hora_entrada);
        $decimal_salida = $this->tiempoDecimalPorHora($hora_salida);
        $total_horas = $decimal_salida - $decimal_entrada;

        $fecha_hora_entrada = "{$fecha} {$hora_entrada}";

        if ($total_horas < 0){
            //la hora de salida es menor, es decir se trata de un día completo.
            $total_horas = (24 - $decimal_entrada) + $decimal_salida;
            $fecha_hora_salida = "{$carbonFecha->addDay(1)->format("Y-m-d")} {$hora_salida}";
        } else {
            $fecha_hora_salida = "{$fecha} {$hora_salida}";
        }

        $id_punto_acceso = 1;

        $asistencia = AsistenciaEmpleado::create([
            "fecha"=>$fecha,
            "hora_entrada"=>$hora_entrada,
            "fecha_hora_entrada"=>$fecha_hora_entrada,
            "hora_salida"=>$hora_salida,
            "fecha_hora_salida"=>$fecha_hora_salida,
            "id_empleado_contrato"=>$id_empleado_contrato,
            "numero_dia_semana"=>$numero_dia_semana,
            "total_horas"=>$total_horas,
            "id_punto_acceso"=>$id_punto_acceso
        ]);

        $asistencia->load("empleado.empleado");

        return $asistencia;
    }

}
