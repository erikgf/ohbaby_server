<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaRegistroEmpleado;
use App\Models\EmpleadoContrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteAsistenciaRegistroEmpleado extends Controller
{
    //
    public function sueldos(Request $request){
        $data = $request->validate([
            "desde"=>"required|date",
            "hasta"=>"required|date"
        ]);

        return EmpleadoContrato::query()
                    ->whereHas("asistenciasOld", fn($q) =>  $q->whereBetween("fecha", [$data["desde"], $data["hasta"]]))
                    ->with([
                        "empleado" => function($q){
                            $q->select("id", "id_empresa", "codigo_unico", "numero_documento", "nombres", "apellido_paterno","apellido_materno");
                            $q->with("empresa", fn($q) => $q->select("id", "razon_social"));
                        },
                        "entregas"=>function($q) use ($data){
                            $q->whereBetween("fecha_registro", [$data["desde"], $data["hasta"]]);
                            $q->select("id_empleado_contrato","fecha_registro", DB::raw("SUM(monto_registrado) as monto_registrado"));
                            $q->groupBy("id_empleado_contrato","fecha_registro");
                            $q->orderBy("fecha_registro");
                        }])
                    ->withSum(["asistenciasOld" => fn($q) =>  $q->whereBetween("fecha", [$data["desde"], $data["hasta"]])], "total_horas" )
                    ->get();
    }

    public function asistencias(Request $request){
        $data = $request->validate([
            "desde"=>"required|date",
            "hasta"=>"required|date"
        ]);

        return AsistenciaRegistroEmpleado::query()
                    ->with([
                        "empleadoBase"=>function($q){
                            $q->select("empleados.id", "codigo_unico");
                            $q->withTrashedParents();
                            $q->withTrashed();
                        }
                    ])
                    ->whereBetween("fecha", [$data["desde"], $data["hasta"]])
                    ->get(["id","id_empleado_contrato", "fecha", "hora_entrada_mañana", "hora_salida_mañana", "hora_entrada_tarde", "hora_salida_tarde", "total_horas"]);
    }
}
