<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaEmpleado;
use App\Models\EmpleadoContrato;
use App\Services\ReporteAsistenciaRegistroEmpleadoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteAsistenciaEmpleadoController extends Controller
{
    private ReporteAsistenciaRegistroEmpleadoService $service;

    public function __construct()
    {
        $this->service = new ReporteAsistenciaRegistroEmpleadoService;
    }

    public function sueldos(Request $request){
        $data = $request->validate([
            "desde"=>"required|date",
            "hasta"=>"required|date"
        ]);

        return $this->service->sueldos($data);
    }

    public function asistencias(Request $request){
        $data = $request->validate([
            "desde"=>"required|date",
            "hasta"=>"required|date"
        ]);

        return $this->service->asistencias($data);
    }
}
