<?php

namespace App\Http\Controllers;

use App\DTO\TipoEntregaDTO;
use App\Http\Requests\TipoEntregaRequest;
use App\Services\TipoEntregaService;

class TipoEntregaController extends Controller
{
    public function index()
    {
        return (new TipoEntregaService)->listar();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoEntregaRequest $request)
    {
        $data = $request->validated();

        $tipoEntregaDTO = new TipoEntregaDTO;

        $tipoEntregaDTO->descripcion = $data["descripcion"];
        $tipoEntregaDTO->tipo = $data["tipo"];
        $tipoEntrega = (new TipoEntregaService)->registrar($tipoEntregaDTO);

        return $tipoEntrega;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return (new TipoEntregaService)->leer($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipoEntregaRequest $request, int $id)
    {
        $data = $request->validated();

        $tipoEntregaDTO = new TipoEntregaDTO;
        $tipoEntregaDTO->descripcion = $data["descripcion"];
        $tipoEntregaDTO->tipo = $data["tipo"];

        $tipoEntrega = (new TipoEntregaService)->editar($tipoEntregaDTO, $id);

        return $tipoEntrega;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $tipoEntrega = (new TipoEntregaService)->eliminar($id);
        return $tipoEntrega;
    }

}
