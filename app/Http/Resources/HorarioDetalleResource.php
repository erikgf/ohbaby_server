<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HorarioDetalleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    private $diasSemana = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];

    public function toArray(Request $request): array
    {
        return [
          "id"=>$this->id,
          "horaInicio"=>substr($this->hora_inicio,0,5),
          "horaFin"=>substr($this->hora_fin,0,5),
          "dias"=>$this->procesarDiasRotulo($this->dias)
        ];
    }

    private function procesarDiasRotulo(string $dias): array{

        $diasArreglo = explode(",",$dias);
        if (count($diasArreglo) <= 0){
            return "";
        }

        $diasArregloConsultados = array_map(function($dia){
            return ["id"=>$dia, "descripcion" => $this->diasSemana[$dia]];
        }, $diasArreglo);


        return $diasArregloConsultados;
    }
}
