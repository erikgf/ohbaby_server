<?php
namespace App\Services;

use App\DTO\TipoEntregaDTO;
use App\Http\Resources\TipoEntregaResource;
use App\Models\TipoEntrega;

class TipoEntregaService{
    public function listar(){
        $registros = TipoEntrega::query()->get();
        return TipoEntregaResource::collection($registros);
    }

    public function registrar(TipoEntregaDTO $tipoEntregaDTO) {
        $registro = TipoEntrega::create([
            "descripcion"=>$tipoEntregaDTO->descripcion,
            "tipo"=>$tipoEntregaDTO->tipo,
        ]);

        $registro->save();
        return new TipoEntregaResource($registro);
    }

    public function editar(TipoEntregaDTO $tipoEntregaDTO, int $id) {
        $tipoEntrega = TipoEntrega::findOrFail($id);
        $tipoEntrega->fill([
            "descripcion"=>$tipoEntregaDTO->descripcion,
            "tipo"=>$tipoEntregaDTO->tipo,
        ]);

        $tipoEntrega->save();
        return new TipoEntregaResource($tipoEntrega);
    }

    public function eliminar(int $id) : int{
        $tipoEntrega = TipoEntrega::findOrFail($id);
        $tipoEntrega->delete();
        return $tipoEntrega->id;
    }

    public function leer(int $id){
        $registro = TipoEntrega::findOrFail($id);
        return new TipoEntregaResource($registro);
    }

}
