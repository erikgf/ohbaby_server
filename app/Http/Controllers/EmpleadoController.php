<?php

namespace App\Http\Controllers;

use App\DTO\EmpleadoDTO;
use App\Http\Requests\EmpleadoRequest;
use App\Services\EmpleadoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{

    public function index()
    {
        return (new EmpleadoService)->listar();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmpleadoRequest $request)
    {
        $data = $request->validated();

        $empleadoDTO = new EmpleadoDTO;

        $empleadoDTO->id_tipo_documento = $data["id_tipo_documento"];
        $empleadoDTO->numero_documento = $data["numero_documento"];
        $empleadoDTO->codigo_unico = $data["codigo_unico"];
        $empleadoDTO->apellido_paterno = $data["apellido_paterno"];
        $empleadoDTO->apellido_materno = $data["apellido_materno"];
        $empleadoDTO->fecha_nacimiento = $data["fecha_nacimiento"];
        $empleadoDTO->nombres = $data["nombres"];
        $empleadoDTO->direccion = $data["direccion"];
        $empleadoDTO->distrito_ubigeo = $data["distrito_ubigeo"] ?? NULL;
        $empleadoDTO->pais = $data["pais"] ?? NULL;
        $empleadoDTO->contratos = $data["contratos"] ?? [];

        DB::beginTransaction();
        $empleado =  (new EmpleadoService)->registrar($empleadoDTO);
        DB::commit();
        return $empleado;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return (new EmpleadoService)->leer($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmpleadoRequest $request, int $id)
    {
        $data = $request->validated();

        $empleadoDTO = new EmpleadoDTO;

        $empleadoDTO->id_tipo_documento = $data["id_tipo_documento"];
        $empleadoDTO->numero_documento = $data["numero_documento"];
        $empleadoDTO->codigo_unico = $data["codigo_unico"];
        $empleadoDTO->apellido_paterno = $data["apellido_paterno"];
        $empleadoDTO->apellido_materno = $data["apellido_materno"];
        $empleadoDTO->fecha_nacimiento = $data["fecha_nacimiento"];
        $empleadoDTO->nombres = $data["nombres"];
        $empleadoDTO->direccion = $data["direccion"];
        $empleadoDTO->distrito_ubigeo = $data["distrito_ubigeo"] ?? NULL;
        $empleadoDTO->pais = $data["pais"] ?? NULL;
        $empleadoDTO->contratos = $data["contratos"] ?? [];
        DB::beginTransaction();
        $empleado = (new EmpleadoService)->editar($empleadoDTO, $id);
        DB::commit();

        return $empleado;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();
        $empleado = (new EmpleadoService)->eliminar($id);
        DB::commit();

        return $empleado;
    }


    public function finalizarContrato(Request $request, int $idEmpleadoContrato)
    {
        DB::beginTransaction();
        $fechaCese = (new EmpleadoService)->finalizarContrato($idEmpleadoContrato);
        DB::commit();

        return $fechaCese;
    }

}
