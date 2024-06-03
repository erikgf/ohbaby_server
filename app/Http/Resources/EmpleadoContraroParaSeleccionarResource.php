<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoContraroParaSeleccionarResource extends JsonResource
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
            "idEmpleado"=>$this->empleado->id,
            "idTipoDocumento"=>$this->empleado->id_tipo_documento,
            "numeroDocumento"=>$this->empleado->numero_documento,
            "apellidoMaterno"=>$this->empleado->apellido_materno,
            "apellidoPaterno"=>$this->empleado->apellido_paterno,
            "nombres"=>$this->empleado->nombres,
            "codigoUnico"=>$this->empleado->codigo_unico
        ];
    }
}
