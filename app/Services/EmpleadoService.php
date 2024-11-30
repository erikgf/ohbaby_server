<?php

namespace App\Services;

use App\DTO\EmpleadoDTO;
use App\Http\Resources\EmpleadoBuscarResource;
use App\Http\Resources\EmpleadoLightResource;
use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;
use App\Models\EmpleadoContrato;
use App\Models\Horario;
use App\Traits\EmpleadoUtilTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class EmpleadoService{

    use EmpleadoUtilTrait;

    public function registrar(EmpleadoDTO $empleadoDTO) : EmpleadoResource {
        $now = date("Y-m-d H:i:s");

        $codigoUnicoExiste = true;
        while ($codigoUnicoExiste) {
            $codigo_unico = strtoupper(Str::random(2));
            $codigoUnicoExiste  = Empleado::query()
                                        ->whereIn("codigo_unico", [$codigo_unico])
                                        ->exists();
        }
        $empleadoDTO->codigo_unico = $codigo_unico;

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
            "id_empresa"=>$empleadoDTO->id_empresa,
            "numero_orden"=>$empleadoDTO->numero_orden,
            "celular"=>$empleadoDTO->celular,
            "sexo"=>$empleadoDTO->sexo,
            "telefono_referencia"=>$empleadoDTO->telefono_referencia,
            "nombre_familiar"=>$empleadoDTO->nombre_familiar,
            "puesto"=>$empleadoDTO->puesto,
            'estado_civil'=>$empleadoDTO->estado_civil
        ]);

        $dias_trabajo = config("globals.DIAS_TRABAJO_MENSUAL");

        if (count($empleadoDTO->contratos) > 0){
            for ($i=0; $i < count($empleadoDTO->contratos); $i++) {
                $item = $empleadoDTO->contratos[$i];
                $salario = $item["salario"];
                $id_horario = $item["id_horario"];

                $horas_semana = Horario::find($id_horario, ["total_horas_semana"])?->total_horas_semana;
                $objCalcular = $this->calcularCostosDiaHora($salario, $dias_trabajo, $horas_semana);
                $costo_hora = $objCalcular["costo_hora"];
                $costo_dia = $objCalcular["costo_dia"];
                $horas_dia = $objCalcular["horas_dia"];

                $empleadoContrato = EmpleadoContrato::create([
                    "id_empleado"=>$empleado->id,
                    "fecha_inicio"=>$item["fecha_inicio"],
                    "salario"=>$salario,
                    "descuento_planilla"=>$item["descuento_planilla"],
                    "dias_trabajo"=>$dias_trabajo,
                    "horas_semana"=>$horas_semana,
                    "costo_hora"=>$costo_hora,
                    "costo_dia"=>$costo_dia,
                    "horas_dia"=>$horas_dia
                ]);

                $empleadoContrato->horarios()->attach([$id_horario], [
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }

        $empleado->load("empresa");

        return new EmpleadoResource($empleado);
    }

    public function editar(EmpleadoDTO $empleadoDTO, int $id) : EmpleadoResource{

        $empleadoEditado = Empleado::findOrFail($id);

        $empleadoEditado->fill([
            "id_tipo_documento"=>$empleadoDTO->id_tipo_documento,
            "numero_documento"=>$empleadoDTO->numero_documento,
            "apellido_paterno"=>$empleadoDTO->apellido_paterno,
            "apellido_materno"=>$empleadoDTO->apellido_materno,
            "fecha_nacimiento"=>$empleadoDTO->fecha_nacimiento,
            "nombres"=>$empleadoDTO->nombres,
            "direccion"=>$empleadoDTO->direccion,
            "distrito_ubigeo"=>$empleadoDTO->distrito_ubigeo,
            "pais"=>$empleadoDTO->pais,
            "id_empresa"=>$empleadoDTO->id_empresa,
            "numero_orden"=>$empleadoDTO->numero_orden,
            "celular"=>$empleadoDTO->celular,
            "sexo"=>$empleadoDTO->sexo,
            "telefono_referencia"=>$empleadoDTO->telefono_referencia,
            "nombre_familiar"=>$empleadoDTO->nombre_familiar,
            "puesto"=>$empleadoDTO->puesto,
            'estado_civil'=>$empleadoDTO->estado_civil
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

        $dias_trabajo = config("globals.DIAS_TRABAJO_MENSUAL");

        foreach ($contratos as $item) {
            $fecha_inicio = $item["fecha_inicio"];
            $salario = $item["salario"];
            $id_horario = @$item["id_horario"];

            if ($id_horario){
                $horas_semana = Horario::find($id_horario, ["total_horas_semana"])?->total_horas_semana;
                $objCalcular = $this->calcularCostosDiaHora($salario, $dias_trabajo, $horas_semana);
                $costo_hora = $objCalcular["costo_hora"];
                $costo_dia = $objCalcular["costo_dia"];
                $horas_dia = $objCalcular["horas_dia"];

                if ($item["id"] == NULL){
                    $empleadoContrato = new EmpleadoContrato();
                    $empleadoContrato->create([
                        "id_empleado"=>$empleadoEditado->id,
                        "fecha_inicio"=>$fecha_inicio,
                        "salario"=>$salario,
                        "descuento_planilla"=>$item["descuento_planilla"],
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
                        "descuento_planilla"=>$item["descuento_planilla"],
                        "costo_hora"=>$costo_hora,
                        "dias_trabajo"=>$dias_trabajo,
                        "horas_dia"=>$horas_dia
                    ]);
                }
            }
        }

        $empleadoEditado->load("contratos");
        $empleadoEditado->load("empresa");

        return new EmpleadoResource($empleadoEditado);
    }

    public function eliminar(int $id) : int{
        $empleado = Empleado::findOrFail($id);
        $empleado->contratos()->delete();
        //Preguntar si tengo horas.
        $empleado->delete();

        return $empleado->id;
    }

    public function listar($data) : ResourceCollection{
        $id_empresa = @$data["id_empresa"];

        $empleados = Empleado::query()
                        ->with([
                            "empresa",
                        ])
                        ->withCount(["contratoActivo as contrato_activo_con_horario" => fn($q) => $q->whereHas("horarios")])
                        ->when($id_empresa && $id_empresa != config("globals.ID_TODOS_LOS_ITEMS"), function($query) use($id_empresa){
                            $query->where("id_empresa", $id_empresa);
                        })
                        ->get();
        return EmpleadoLightResource::collection($empleados);
    }

    public function leer(int $id) : EmpleadoResource{
        $empleado = Empleado::with(["empresa","contratos"=>function($c){
            $c->orderBy("fecha_inicio", "DESC");
        }])->findOrFail($id);
        return new EmpleadoResource($empleado);
    }

    public function finalizarContrato(int $idEmpleadoContrato, string $fechaFin, string $observacionesFinContrato) : array{
        $empleadoContrato = EmpleadoContrato::findOrFail($idEmpleadoContrato);
        $empleadoContrato->update([
            "fecha_fin"=>$fechaFin,
            "observaciones_fin_contrato" => $observacionesFinContrato
        ]);

        return [$fechaFin, $observacionesFinContrato];
    }

    public function buscarTerm(string $searchTerm){
        $empleados = Empleado::whereHas("contratoActivo")
                                ->with("contratoActivo.horarios.horarioDetalles")
                                ->where(function($q) use($searchTerm)
                                    {
                                        $q->where("nombres", "like", '%'.$searchTerm.'%')
                                            ->orWhere("apellido_paterno", "like", '%'.$searchTerm.'%')
                                            ->orWhere("apellido_materno", "like", '%'.$searchTerm.'%');
                                })
                                ->get();
        return EmpleadoBuscarResource::collection($empleados);
    }


}
