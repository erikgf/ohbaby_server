<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoContratoSoloContratosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            "id"=>$this->id,
            "fechaInicio"=>$this->fecha_inicio,
            "fechaFin"=>$this->fecha_fin ?? "",
            "observacionesFinContrato" => $this->observaciones_fin_contrato,
            "salario"=>$this->salario,
            "costoHora"=>$this->costo_hora,
            "costoDia"=>$this->costo_dia,
            "diasTrabajo"=>$this->dias_trabajo,
            "horasDia"=>$this->horas_dia,
            "descuentoPlanilla"=>$this->descuento_planilla,
            "horasSemana"=>$this->horas_semana,
            //"horarios"=>$this->horarios
            "idHorario"=>$this->horarios?->first()?->id
        ];
    }
}
