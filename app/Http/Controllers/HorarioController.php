<?php

namespace App\Http\Controllers;

use App\DTO\HorarioDTO;
use App\Http\Requests\HorarioRequest;
use App\Services\HorarioService;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{

    public function index()
    {
        return (new HorarioService)->listar();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HorarioRequest $request)
    {
        $data = $request->validated();

        $horarioDTO = new HorarioDTO;

        DB::beginTransaction();

        $horarioDTO->descripcion = $data["descripcion"];
        $horarioDTO->horarioDetalles = $data["horario_detalles"] ?? [];
        $horario =  (new HorarioService)->registrar($horarioDTO);

        DB::commit();

        return $horario;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return (new HorarioService)->leer($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HorarioRequest $request, int $id)
    {
        $data = $request->validated();
        $horarioDTO = new HorarioDTO;

        $horarioDTO->descripcion = $data["descripcion"];
        $horarioDTO->horarioDetalles = $data["horario_detalles"] ?? [];

        DB::beginTransaction();
        $horario = (new HorarioService)->editar($horarioDTO, $id);
        DB::commit();
        return $horario;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();
        $horario = (new HorarioService)->eliminar($id);
        DB::commit();

        return $horario;
    }
}
