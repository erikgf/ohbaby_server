<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HorarioEmpleadoResource extends JsonResource
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
            "descripcion"=>$this->descripcion,
            "detalles"=> HorarioDetalleResource::collection($this->horarioDetalles),
            "personal"=>EmpleadoContraroParaSeleccionarResource::collection($this->empleadoContratos)
        ];
    }
}
