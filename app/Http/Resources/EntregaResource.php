<?php

namespace App\Http\Resources;

use App\Models\TipoEntrega;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntregaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "id_tipo_entrega"=>$this->id_tipo_entrega,
            "id_empleado_contrato"=>$this->id_empleado_contrato,
            "fecha_registro"=>$this->fecha_registro,
            "monto_registrado"=>$this->monto_registrado,
            "cuotas"=>$this->whenLoaded("cuotas")
        ];
    }
}
