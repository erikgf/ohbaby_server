<?php

namespace App\Services;

use App\DTO\EmpleadoDTO;
use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;
use App\Models\EmpleadoContrato;
use App\Traits\EmpleadoUtilTrait;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class EmpleadoService{

    use EmpleadoUtilTrait;

    public function registrar(EmpleadoDTO $empleadoDTO) : EmpleadoResource {
        $empleado = Empleado::create([
            "id_tipo_documento"=>$empleadoDTO->id_tipo_documento,
            "numero_documento"=>$empleadoDTO->numero_documento,
            "codigo_unico"=>$empleadoDTO->codigo_unico,
            "apellido_paterno"=>$empleadoDTO->apellido_paterno,
            "apellido_materno"=>$empleadoDTO->apellido_materno,
            "fecha_nacimiento"=>$empleadoDTO->fecha_nacimiento,
            "nombres"=>$empleadoDTO->nombres,
            "direccion"=>$empleadoDTO->direccion,
            "distrito_ubigeo"=>$empleadoDTO->distrito_ubigeo,
            "pais"=>$empleadoDTO->pais,
        ]);

        if (count($empleadoDTO->contratos) > 0){
            $contratos = array_map(function($item){
                $dias_trabajo = $item["dias_trabajo"];
                $horas_dia = $item["horas_dia"];
                $salario = $item["salario"];

                $objCalcular = $this->calcularCostosDiaHora($salario, $dias_trabajo, $horas_dia);
                $costo_hora = $objCalcular["costo_hora"];
                $costo_dia = $objCalcular["costo_dia"];

                return new EmpleadoContrato([
                    "fecha_inicio"=>$item["fecha_inicio"],
                    "salario"=>$salario,
                    "costo_hora"=>$costo_hora,
                    "costo_dia"=>$costo_dia,
                    "dias_trabajo"=>$dias_trabajo,
                    "horas_dia"=>$horas_dia
                ]);
            }, $empleadoDTO->contratos);

            $empleado->contratos()->saveMany($contratos);
        }

        return new EmpleadoResource($empleado);
    }

    public function editar(EmpleadoDTO $empleadoDTO, int $id) : EmpleadoResource{

        $empleadoEditado = Empleado::findOrFail($id);

        $empleadoEditado->fill([
            "id_tipo_documento"=>$empleadoDTO->id_tipo_documento,
            "numero_documento"=>$empleadoDTO->numero_documento,
            "codigo_unico"=>$empleadoDTO->codigo_unico,
            "apellido_paterno"=>$empleadoDTO->apellido_paterno,
            "apellido_materno"=>$empleadoDTO->apellido_materno,
            "fecha_nacimiento"=>$empleadoDTO->fecha_nacimiento,
            "nombres"=>$empleadoDTO->nombres,
            "direccion"=>$empleadoDTO->direccion,
            "distrito_ubigeo"=>$empleadoDTO->distrito_ubigeo,
            "pais"=>$empleadoDTO->pais,
        ]);

        $empleadoEditado->save();

        $contratos = $empleadoDTO->contratos;
        $idContratos = [];

        foreach ($contratos as $value) {
            if ($value["id"] != null){
                array_push($idContratos, $value["id"]);
            }
        }

        $contratosBorrar = EmpleadoContrato::whereNotIn("id", $idContratos)->where(["id_empleado"=>$id])->get(["id","fecha_fin"]);

        foreach ($contratosBorrar as $contratoBorrar) {
            if ($contratoBorrar->fecha_fin != NULL){
                throw new \Exception("Está intentando eliminar un CONTRATO que ya fue CONCLUIDO.", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $contratoBorrar->delete();
        }

        foreach ($contratos as $item) {
            $fecha_inicio = $item["fecha_inicio"];
            $dias_trabajo = $item["dias_trabajo"];
            $horas_dia = $item["horas_dia"];
            $salario = $item["salario"];

            $objCalcular = $this->calcularCostosDiaHora($salario, $dias_trabajo, $horas_dia);
            $costo_hora = $objCalcular["costo_hora"];
            $costo_dia = $objCalcular["costo_dia"];

            if ($item["id"] == NULL){
                $empleadoContrato = new EmpleadoContrato();
                $empleadoContrato->create([
                    "id_empleado"=>$empleadoEditado->id,
                    "fecha_inicio"=>$fecha_inicio,
                    "salario"=>$salario,
                    "costo_hora"=>$costo_hora,
                    "costo_dia"=>$costo_dia,
                    "dias_trabajo"=>$dias_trabajo,
                    "horas_dia"=>$horas_dia
                ]);

            } else {
                $empleadoContrato = EmpleadoContrato::findOrFail($item["id"]);
                $empleadoContrato->update([
                    "fecha_inicio"=>$fecha_inicio,
                    "salario"=>$salario,
                    "costo_hora"=>$costo_hora,
                    "dias_trabajo"=>$dias_trabajo,
                    "horas_dia"=>$horas_dia
                ]);
            }
        }

        $empleadoEditado->load("contratos");

        return new EmpleadoResource($empleadoEditado);
    }

    public function eliminar(int $id) : int{
        $empleado = Empleado::findOrFail($id);
        $empleado->contratos()->delete();
        //Preguntar si tengo horas.
        $empleado->delete();

        return $empleado->id;
    }

    public function listar() : ResourceCollection{
        $empleados = Empleado::with("contratos")->get();
        return EmpleadoResource::collection($empleados);
    }

    public function leer(int $id) : EmpleadoResource{
        $empleado = Empleado::with(["contratos"=>function($c){
            $c->orderBy("fecha_inicio", "DESC");
        }])->findOrFail($id);
        return new EmpleadoResource($empleado);
    }

    public function finalizarContrato(int $idEmpleadoContrato) : string{

        $fechaCese = Carbon::now()->format("Y-m-d");
        $empleadoContrato = EmpleadoContrato::findOrFail($idEmpleadoContrato);
        $empleadoContrato->update([
            "fecha_fin"=>$fechaCese
        ]);

        return $fechaCese;
    }


}
