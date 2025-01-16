<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntregaListaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $empleado =  $this->empleadoContrato?->empleado;
        $empleadoDesc = "";
        if ($empleado){
            $empleadoDesc = "{$empleado->apellido_paterno} {$empleado->apellido_materno}, {$empleado->nombres}";
        }

        return [
            "id"=>$this->id,
            "tipo_entrega"=>$this->tipoEntrega,
            "tipo_entrega_desc"=>$this->tipoEntrega?->descripcion,
            "empleado_contrato"=>$this->empleadoContrato,
            "empleado_contrato_desc"=>$empleadoDesc,
            "fecha_registro"=>Carbon::parse($this->fecha_registro)->format("d-m-Y"),
            "fecha_registro_raw"=>$this->fecha_registro,
            "monto_registrado"=>number_format($this->monto_registrado, 3),
            "motivo"=>$this->motivo
        ];
    }
}
