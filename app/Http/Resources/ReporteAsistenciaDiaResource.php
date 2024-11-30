<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReporteAsistenciaDiaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $empleados = $this->empleados->map(function($empleado){
            return [
                "empleado_nombres" => "{$empleado->nombres} {$empleado->apellido_paterno} {$empleado->apellido_materno}",
                "empleado_codigo_unico" => $empleado->codigo_unico,
                "horario_id" => $empleado->contratos->first()?->horarios?->first()?->id
            ];
        });

        return [
            "empresa_id"=>$this->id,
            "empresa_nombre"=>$this->razon_social,
            "registros"=>$empleados,
        ];
    }
}
