<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormularioAsistenciaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $contrato = $this->contratos->first();
        return [
            "id"=>$contrato->id || $this->id,
            "empleado_codigo_unico"=>$this->codigo_unico,
            "empleado_nombres"=>"{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}",
            "horario_id"=>$contrato->first()?->horarios?->first()?->id
        ];
    }
}
