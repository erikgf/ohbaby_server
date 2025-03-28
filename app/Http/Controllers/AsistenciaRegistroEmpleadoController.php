<?php

namespace App\Http\Controllers;

use App\Http\Requests\AsistenciaRegistroEmpleadoRequest;
use App\Services\AsistenciaRegistroEmpleadoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaRegistroEmpleadoController extends Controller
{
    public function store(AsistenciaRegistroEmpleadoRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        $res =  (new AsistenciaRegistroEmpleadoService)->registrar($data);
        DB::commit();

        return $res;
    }

    public function consultar(Request $request)
    {
        $data = $request->validate([
            "codigo_unico"=>"required|string|size:2",
            "fecha"=>"required|string|size:8"
        ]);

        DB::beginTransaction();
        $res =  (new AsistenciaRegistroEmpleadoService)->consultar($data["fecha"], $data["codigo_unico"]);
        DB::commit();

        return $res;
    }

    public function getDataControlSeguridad(string $fecha)
    {
        return (new AsistenciaRegistroEmpleadoService)->getDataControlSeguridad($fecha);
    }

    public function getDataFormularioAsistencia(string $fecha)
    {
        return (new AsistenciaRegistroEmpleadoService)->getDataFormularioAsistencia($fecha);
    }

    public function getDataAsistenciaManual(string $fecha)
    {
        return (new AsistenciaRegistroEmpleadoService)->getDataAsistenciaManual($fecha);
    }
}
