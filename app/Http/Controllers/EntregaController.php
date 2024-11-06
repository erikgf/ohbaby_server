<?php

namespace App\Http\Controllers;

use App\DTO\EntregaDTO;
use App\Http\Requests\EntregaEditarRequest;
use App\Http\Requests\EntregaRequest;
use App\Http\Resources\EntregaListaResource;
use App\Services\EntregaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregaController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            "fromDate" => "nullable|date",
            "toDate" => "nullable|date"
        ]);

        return EntregaListaResource::collection((new EntregaService)->listar($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EntregaRequest $request)
    {
        $data = $request->validated();

        $entregaDTO = new EntregaDTO;

        $entregaDTO->id_tipo_entrega = $data["id_tipo_entrega"];
        $entregaDTO->id_empleado_contrato = $data["id_empleado_contrato"];
        $entregaDTO->cuotas = $data["cuotas"];

        DB::beginTransaction();
        $entrega = (new EntregaService)->registrar($entregaDTO);
        DB::commit();

        return $entrega;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return (new EntregaService)->leer($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EntregaEditarRequest $request, int $id)
    {
        $data = $request->validated();

        $entregaDTO = new EntregaDTO;

        $entregaDTO->id_tipo_entrega = $data["id_tipo_entrega"];
        $entregaDTO->id_empleado_contrato = $data["id_empleado_contrato"];
        $entregaDTO->fecha_registro = $data["fecha_cuota"];
        $entregaDTO->monto_registrado = $data["monto_cuota"];
        $entregaDTO->motivo = $data["motivo_registro"];

        DB::beginTransaction();
        $entrega = (new EntregaService)->editar($entregaDTO, $id);
        DB::commit();

        return $entrega;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();
        $entrega = (new EntregaService)->eliminar($id);
        DB::commit();

        return $entrega;
    }
}
