<?php
namespace App\Services;

use App\DTO\EntregaDTO;
use App\Http\Resources\EntregaListaResource;
use App\Models\Entrega;
use App\Models\EntregaCuota;
use Carbon\Carbon;

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
        $registro = Entrega::create([
            "id_tipo_entrega"=>$entregaDTO->id_tipo_entrega,
            "id_empleado_contrato"=>$entregaDTO->id_empleado_contrato,
            "fecha_registro"=>$entregaDTO->fecha_registro,
            "motivo"=>$entregaDTO->motivo
        ]);

        if ($entregaDTO->cuotas){
            $fecha_cuota = $entregaDTO->fecha_registro;
            $monto_registrado = 0.00;
            foreach($entregaDTO->cuotas as $i => $cuota){
                $fecha_cuota = Carbon::parse($fecha_cuota)->addMonthsNoOverflow(1)->startOfMonth()->format("Y-m-d");
                $registro->cuotas()->save(new EntregaCuota([
                    "numero_cuota" => ($i + 1),
                    "fecha_cuota" => $fecha_cuota,
                    "monto_cuota" => $cuota["monto_cuota"],
                    "es_entregado" => config("globals.ENTREGA.ENTREGADA")
                ]));

                $monto_registrado += $cuota["monto_cuota"];
            }
        }

        $registro->monto_registrado = $monto_registrado;
        $registro->save();

        $registro->load("tipoEntrega");
        $registro->load("empleadoContrato.empleado");

        return new EntregaListaResource($registro);
    }

    public function editar(EntregaDTO $entregaDTO, int $id) {
        $entrega = Entrega::findOrFail($id);
        $entrega->cuotas()->delete();
        $entrega->delete();

        $registro = $this->registrar($entregaDTO);
        return $registro;
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
