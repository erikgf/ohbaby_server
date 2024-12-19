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
        $empleados = $this->empleados->map(function($empleado){
            $contrato = $empleado->contratos->first();
            return [
                "id"=>$contrato->id,
                "empleado_nombres" => "{$empleado->nombres} {$empleado->apellido_paterno} {$empleado->apellido_materno}",
                "empleado_codigo_unico" => $empleado->codigo_unico,
                "horario_id" => $contrato?->horarios?->first()?->id,
                "asistencia"=> $contrato?->asistencias?->first()
            ];
        });

       return [
            "empresa_id"=>$this->id,
            "empresa_nombre"=>$this->razon_social,
            "registros"=>$empleados,
        ];
    }
}
