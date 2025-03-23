<?php

namespace App\Services;

use App\Models\AsistenciaEmpleado;
use App\Models\EmpleadoContrato;
use Illuminate\Support\Facades\DB;

class ReporteAsistenciaRegistroEmpleadoService{

    /*
    ## Sueldos Mejorados.
    Empleado::query()
  	->with([
	  "contratos" => function($q) use($data) {
		//$q->whereHas("asistencias", fn($q) =>  $q->whereBetween("fecha", [$data["desde"], $data["hasta"]]));
		$q->with(["entregas"=>function($q) use ($data){
			$q->whereBetween("fecha_registro", [$data["desde"], $data["hasta"]]);
			$q->select("id_empleado_contrato","fecha_registro", DB::raw("SUM(monto_registrado) as monto_registrado"));
			$q->groupBy("id_empleado_contrato","fecha_registro");
			$q->orderBy("fecha_registro");
		}]);
		//
		$q->withSum(["asistencias" => fn($q) =>  $q->whereBetween("fecha", [$data["desde"], $data["hasta"]])], "total_horas" );
	  },
	 ])
  	->where(["id" => 64])
  	->get();
    */
    public function sueldos(array $data){
        return EmpleadoContrato::query()
                    /*
                    ->whereNull("fecha_fin")
                    ->orWhere(function($q) use($data){
                        $q->where("fecha_inicio", "<=", $data["desde"]);
                        $q->where("fecha_fin", ">=", $data["hasta"]);
                    })
                    */
                    ->whereHas("asistencias", fn($q) =>  $q->whereBetween("fecha", [$data["desde"], $data["hasta"]]))
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
                    ->withSum(["asistencias" => fn($q) =>  $q->whereBetween("fecha", [$data["desde"], $data["hasta"]])], "total_horas" )
                    ->get();
    }

    public function asistencias(array $data){
        return AsistenciaEmpleado::query()
                    ->with([
                        "empleadoBase"=>function($q){
                            $q->select("empleados.id", "codigo_unico");
                            $q->withTrashedParents();
                            $q->withTrashed();
                        }
                    ])
                    ->whereBetween("fecha", [$data["desde"], $data["hasta"]])
                    ->get(["id","id_empleado_contrato", "fecha", "hora_entrada", "hora_salida", "total_horas"]);
    }
}