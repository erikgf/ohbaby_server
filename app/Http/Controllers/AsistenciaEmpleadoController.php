<?php

namespace App\Http\Controllers;

use App\Http\Resources\AsistenciaEmpleadoEmpleadosResource;
use App\Http\Resources\AsistenciaEmpleadoResource;
use App\Services\AsistenciaEmpleadoService;
use Illuminate\Http\Request;

class AsistenciaEmpleadoController extends Controller
{
    public function getEmpleados(Request $request)
    {
        $data = $request->validate([
            "texto"=>"required|string",
            "fecha"=>"required|date"
        ]);

        $response =  (new AsistenciaEmpleadoService)->consultarEmpleadosPorFecha($data["texto"], $data["fecha"]);
        return AsistenciaEmpleadoEmpleadosResource::collection($response);
    }

    public function index(Request $request)
    {
        $data = $request->validate([
            "fecha"=>"required|date"
        ]);

        $response =  (new AsistenciaEmpleadoService)->consultarAsistenciaDia($data["fecha"]);
        return AsistenciaEmpleadoResource::collection($response);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "fecha"=>"required|date",
            "id_empleado_contrato"=>"required|integer",
            "hora_entrada"=>"required|string|max:8",
            "hora_salida"=>"required|string|max:8",
        ]);

        $res =  (new AsistenciaEmpleadoService)->guardarAsistencia($data["fecha"], $data["id_empleado_contrato"], $data["hora_entrada"], $data["hora_salida"]);
        return new AsistenciaEmpleadoResource($res);
    }

    public function destroy(int $id)
    {
        $res =  (new AsistenciaEmpleadoService)->eliminarAsistencia($id);
        return $res;
    }
}
