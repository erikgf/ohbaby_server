<?php

namespace App\Services;

use App\Http\Resources\EmpleadoContraroParaSeleccionarResource;
use App\Http\Resources\HorarioEmpleadoResource;
use App\Models\EmpleadoContrato;
use App\Models\Horario;
use Carbon\Carbon;
use Illuminate\Http\Response;

class HorarioEmpleadoContratoService{

    public function listar(){
        $horarios = Horario::with(["empleadoContratos.empleado", "horarioDetalles"])->get();
        return HorarioEmpleadoResource::collection($horarios);
    }

    public function listarLibres(){
        $empleadosLibres = EmpleadoContrato::whereDoesntHave("horarios")
                    ->join("empleados as e", "e.id","=","empleado_contratos.id_empleado")
                    ->whereNull("fecha_fin")
                    ->orderBy("e.apellido_paterno")
                    ->orderBy("e.apellido_materno")
                    ->orderBy("e.nombres")
                    ->select("empleado_contratos.id", "empleado_contratos.id_empleado")
                    ->get();
        return EmpleadoContraroParaSeleccionarResource::collection($empleadosLibres);
    }

    public function registrar(int $idHorario, array $empleadoContratos){
        $horario = Horario::findOrFail($idHorario);
        $now = Carbon::now();

        $horario->empleadoContratos()->detach();

        $cantidadRegistrosRepetidos = EmpleadoContrato::whereIn("id", $empleadoContratos)
                ->whereHas("horarios", function($q) use($horario){
                   $q->whereNotIn("id", [$horario->id]);
                })->count();

        if ($cantidadRegistrosRepetidos > 0 ){
            throw new \Exception("Ya existe algun empleado en otro horario asignado.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (count($empleadoContratos) > 0 ){
            $horario->empleadoContratos()->attach($empleadoContratos, [
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        return $horario;
    }

    public function listarEmpleados(int $idHorario){
        $horario = Horario::findOrFail($idHorario);
        $empleadosLibres = EmpleadoContrato::whereDoesntHave("horarios")->get();
        $empleadosHorario = $horario->empleadoContratos()->with("empleado")->get();

        return [
            "empleadosLibres"=> EmpleadoContraroParaSeleccionarResource::collection($empleadosLibres),
            "empleadosHorario"=> EmpleadoContraroParaSeleccionarResource::collection($empleadosHorario),
        ];
    }

}
