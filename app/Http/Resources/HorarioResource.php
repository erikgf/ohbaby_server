<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HorarioResource extends JsonResource
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
            "total_horas_semana"=>$this->total_horas_semana,
            "detalles"=> HorarioDetalleResource::collection($this->horarioDetalles)
        ];
    }
}
