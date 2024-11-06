<?php
namespace App\Services;

use App\DTO\EntregaDTO;
use App\Http\Resources\EntregaListaResource;
use App\Models\Entrega;

class EntregaService{
    public function listar(array $params){
        $registros = Entrega::query()
                ->with("empleadoContrato.empleado")
                ->with("tipoEntrega")
                ->when(@$params["fromDate"] && @$params["toDate"], function($q) use($params) {
                    return $q->whereBetween("fecha_registro", [$params["fromDate"], $params["toDate"]]);
                })
                ->get();

        return $registros;
    }

    public function registrar(EntregaDTO $entregaDTO) {
        $idRegistros = [];

        if ($entregaDTO->cuotas){
            foreach($entregaDTO->cuotas as $cuota){
                $registro = Entrega::create([
                    "fecha_registro" => $cuota["fecha_cuota"],
                    "monto_registrado" => $cuota["monto_cuota"],
                    "motivo" => @$cuota["motivo_registro"],
                    "id_tipo_entrega"=>$entregaDTO->id_tipo_entrega,
                    "id_empleado_contrato"=>$entregaDTO->id_empleado_contrato,
                ]);

                $registro->save();
                $idRegistros[] = $registro->id;
            }
        }

        $registros = Entrega::query()
                        ->with(["tipoEntrega", "empleadoContrato.empleado"])
                        ->whereIn("id", $idRegistros)
                        ->get();
        return EntregaListaResource::collection($registros);
    }

    public function editar(EntregaDTO $entregaDTO, int $id) {
        $entrega = Entrega::findOrFail($id);
        $entrega->fill([
            "fecha_registro" => $entregaDTO->fecha_registro,
            "monto_registrado" => $entregaDTO->monto_registrado,
            "motivo" => @$entregaDTO->motivo,
            "id_tipo_entrega"=>$entregaDTO->id_tipo_entrega,
            "id_empleado_contrato"=>$entregaDTO->id_empleado_contrato,
        ]);
        $entrega->save();
        $entrega->load(["tipoEntrega", "empleadoContrato.empleado"]);

        return new EntregaListaResource($entrega);
    }

    public function eliminar(int $id) : int{
        $entrega = Entrega::findOrFail($id);
        $entrega->cuotas()->delete();
        $entrega->delete();
        return $entrega->id;
    }

    public function leer(int $id){
        $registro = Entrega::query()
                ->with("empleadoContrato.empleado")
                ->with("tipoEntrega")
                ->with("cuotas")
                ->findOrFail($id);
        return $registro;
    }

}
