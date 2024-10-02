<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaRegistroEmpleado;
use App\Models\EmpleadoContrato;
use Illuminate\Http\Request;

class ReporteAsistenciaRegistroEmpleado extends Controller
{
    //
    public function sueldos(Request $request){
        $data = $request->validate([
            "desde"=>"required|date",
            "hasta"=>"required|date"
        ]);

        return EmpleadoContrato::query()
                    ->whereHas("asistencias", fn($q) =>  $q->whereBetween("fecha", [$data["desde"], $data["hasta"]]))
                    ->with([
                        "empleado",
                        "entregas"=>function($q) use ($data) {
                            $q->select("id", "id_tipo_entrega", "id_empleado_contrato");
                            $q->whereHas("cuotas", fn($q)=>$q->whereBetween("fecha_cuota", [$data["desde"], $data["hasta"]]));
                            $q->withSum(["cuotas" => fn($q) => $q->whereBetween("fecha_cuota", [$data["desde"], $data["hasta"]])], "monto_cuota");
                            $q->with("tipoEntrega", function($q){
                                $q->select("id","tipo");
                            });
                        }])
                    ->withSum("asistencias", "total_horas" )
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
