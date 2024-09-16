<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoContratoBuscarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $empleado = $this->empleado;
        $empleadoDesc = "";
        if ($empleado){
            $empleadoDesc = $empleado->apellido_paterno." ".$empleado->apellido_materno.", ".$empleado->nombres;
        }

        return [
            "id"=>$this->id,
            "descripcion"=>$empleadoDesc,
            "fecha_inicio"=>$this->fecha_inicio,
            "fecha_fin"=>$this->fecha_fin
        ];
    }
}
