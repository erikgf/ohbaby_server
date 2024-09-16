<?php

namespace App\Services;

use App\Http\Resources\EmpleadoContratoBuscarResource;
use App\Models\EmpleadoContrato;

class EmpleadoContratoService{

    public function buscarTerm(string $searchTerm){
        $empleados = EmpleadoContrato::query()
                                ->with(["empleado" => function($q){
                                    $q->select("id", "nombres","apellido_paterno","apellido_materno");
                                }])
                                ->whereHas("empleado", function($q) use($searchTerm){
                                    $q->where("nombres", "like", '%'.$searchTerm.'%')
                                            ->orWhere("apellido_paterno", "like", '%'.$searchTerm.'%')
                                            ->orWhere("apellido_materno", "like", '%'.$searchTerm.'%');
                                })
                                ->whereNull("fecha_fin")
                                ->get();
        return EmpleadoContratoBuscarResource::collection($empleados);
    }


}
