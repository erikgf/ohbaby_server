<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Lang;

class TipoEntregaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tipoDesc = "";
        if ($this->tipo == config("globals.TIPO_ENTREGA.ADELANTO")){
            $tipoDesc = Lang::get("strings.tipo_entrega_adelanto");
        } else {
            $tipoDesc = Lang::get("strings.tipo_entrega_descuento");
        }

        return [
            "id"=>$this->id,
            "tipo"=>$this->tipo,
            "descripcion"=>$this->descripcion,
            "tipoDesc"=> $tipoDesc
        ];
    }
}
