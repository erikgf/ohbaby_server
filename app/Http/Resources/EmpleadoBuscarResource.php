<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoBuscarResource extends JsonResource
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
            "descripcion"=>$this->apellido_paterno." ".$this->apellido_materno.", ".$this->nombres,
            "contrato"=>$this->whenLoaded("contratoActivo")
        ];
    }
}
