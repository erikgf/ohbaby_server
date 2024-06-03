<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoResource extends JsonResource
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
            "idTipoDocumento"=>$this->id_tipo_documento == "R" ? "RUC": ($this->id_tipo_documento == "D" ? "DNI": "CE"),
            "numeroDocumento"=>$this->numero_documento,
            "apellidoMaterno"=>$this->apellido_materno,
            "apellidoPaterno"=>$this->apellido_paterno,
            "nombres"=>$this->nombres,
            "codigoUnico"=>$this->codigo_unico,
            "direccion"=>$this->direccion,
            "distritoUbigeo"=>$this->distrito_ubigeo,
            "pais"=>$this->pais,
            "fechaNacimiento"=>date("d-m-Y", strtotime($this->fecha_nacimiento)),
            "fechaNacimientoRaw"=>$this->fecha_nacimiento,
            "contratos"=>EmpleadoContratoSoloContratosResource::collection($this->contratos)
        ];
    }
}
