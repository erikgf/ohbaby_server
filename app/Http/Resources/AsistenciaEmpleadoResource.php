<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsistenciaEmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $empleado = $this->empleado->empleado;
        return [
            "id"=>$this->id,
            "codigo_unico"=>$empleado->codigo_unico,
            "empleado"=>"{$empleado->nombres} {$empleado->apellido_paterno} {$empleado->apellido_materno}",
            "empresa"=>$empleado->empresa->razon_social,
            "hora_entrada"=>$this->hora_entrada,
            "hora_salida"=>$this->hora_salida,
            "total_horas"=>$this->total_horas
        ];
    }
}
