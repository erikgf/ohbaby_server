<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsistenciaEmpleadoEmpleadosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->contratos[0]->id,
            "descripcion"=>"{$this->codigo_unico} | {$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}"
        ];
    }
}
