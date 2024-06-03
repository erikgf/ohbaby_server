<?php

namespace App\Http\Controllers;

use App\Http\Requests\HorarioEmpleadoContratoRequest;
use App\Services\HorarioEmpleadoContratoService;
use Illuminate\Support\Facades\DB;

class HorarioEmpleadoContratoController extends Controller
{
    public function index()
    {
        return (new HorarioEmpleadoContratoService)->listar();
    }

    public function store(HorarioEmpleadoContratoRequest $request, int $idHorario)
    {
        $data = $request->validated();
        $empleadoContratos = $data["empleados_contratos"] ?? [];
        DB::beginTransaction();
        $horario =  (new HorarioEmpleadoContratoService)->registrar($idHorario, $empleadoContratos);
        DB::commit();

        return $horario;
    }

    public function show(int $idHorario)
    {
        return (new HorarioEmpleadoContratoService)->listarEmpleados($idHorario);
    }

    public function indexLibres()
    {
        return (new HorarioEmpleadoContratoService)->listarLibres();
    }
}
