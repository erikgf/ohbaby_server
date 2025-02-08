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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmpleadoService{

    use EmpleadoUtilTrait;

    public function registrar(EmpleadoDTO $empleadoDTO) : EmpleadoLightResource {
        $now = date("Y-m-d H:i:s");

        $codigoUnicoExiste = true;
        while ($codigoUnicoExiste) {
            $codigo_unico = strtoupper(Str::random(2));
            $codigoUnicoExiste  = Empleado::query()
                                        ->whereIn("codigo_unico", [$codigo_unico])
                                        ->exists();
        }
        $empleadoDTO->codigo_unico = $codigo_unico;

        $numero_orden = Empleado::query()
                        ->where("id_empresa", $empleadoDTO->id_empresa)
                        ->max("numero_orden");

        $numero_orden = $numero_orden == null ? 1 : $numero_orden + 1;

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
            "numero_orden"=>$numero_orden,
            "celular"=>$empleadoDTO->celular,
            "sexo"=>$empleadoDTO->sexo,
            "telefono_referencia"=>$empleadoDTO->telefono_referencia,
            "nombre_familiar"=>$empleadoDTO->nombre_familiar,
            "puesto"=>$empleadoDTO->puesto,
            'estado_civil'=>$empleadoDTO->estado_civil,
            'fecha_ingreso'=>$empleadoDTO->fecha_ingreso
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
                    "descuento_planilla"=>@$item["descuento_planilla"] ?? "0.00",
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

        return new EmpleadoLightResource($empleado);
    }

    public function editar(EmpleadoDTO $empleadoDTO, int $id) : EmpleadoLightResource{

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
            "celular"=>$empleadoDTO->celular,
            "sexo"=>$empleadoDTO->sexo,
            "telefono_referencia"=>$empleadoDTO->telefono_referencia,
            "nombre_familiar"=>$empleadoDTO->nombre_familiar,
            "puesto"=>$empleadoDTO->puesto,
            'estado_civil'=>$empleadoDTO->estado_civil,
            'fecha_ingreso'=>$empleadoDTO->fecha_ingreso
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

                $now = now();

                if ($item["id"] == NULL){
                    $empleadoContrato = new EmpleadoContrato();
                    $empleadoContrato->create([
                        "id_empleado"=>$empleadoEditado->id,
                        "fecha_inicio"=>$fecha_inicio,
                        "salario"=>$salario,
                        "descuento_planilla"=>@$item["descuento_planilla"] ?? "0.00",
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
                        "descuento_planilla"=>@$item["descuento_planilla"] ?? "0.00",
                        "costo_hora"=>$costo_hora,
                        "dias_trabajo"=>$dias_trabajo,
                        "horas_dia"=>$horas_dia
                    ]);

                    $empleadoContrato->horarios()->detach();
                }

                $empleadoContrato->horarios()->attach([$id_horario], [
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }

        $empleadoEditado->load("contratoActivo");
        $empleadoEditado->load("empresa");
        $empleadoEditado->loadCount(["contratoActivo" => fn($q) => $q->whereHas("horarios")]);

        return new EmpleadoLightResource($empleadoEditado);
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
                            "contratoActivo"
                        ])
                        ->withCount(["contratoActivo" => fn($q) => $q->whereHas("horarios")])
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

    public function upsertMasivo(array $dataAlta, array $dataBaja){
        $ahora = now();
        $id_key = "id_numero_documento";
        $id_field_name  = "numero_documento";
        $table_name = "empleados";
        $table_contratos_name = "empleado_contratos";
        $table_contratos_horario_name = "empleado_contrato_horario";

        $dias_trabajo = config("globals.DIAS_TRABAJO_MENSUAL");

        $registrosInsertados = [];
        $registrosEditados = [];

        if (!is_null($dataAlta) && count($dataAlta) > 0){
            foreach ($dataAlta as $item) {
                if (@$item[$id_key]){
                    $registrosEditados[] = $item;
                } else {
                    $registrosInsertados[] = $item;
                }
            }

            if (count($registrosEditados) > 0){
                $ids = array_column($registrosEditados, $id_key);
                $idTipoDocumentoCases=[];
                $numeroDocumentoCases = [];
                $idEmpresasCases = [];
                $aPaternoCases = [];
                $aMaternoCases = [];
                $nombresCases = [];
                $fechaNacCases = [];
                $sexoCases = [];
                $fechaIngresoCases = [];
                $direccionCases = [];
                $distritoUbigeoCases= [];
                $paisCase= [];
                $celularCase= [];
                $puestoCase= [];
                $nombreFamiliarCase= [];
                $telefonoReferenciaCase= [];
                $estadoCivilCase = [];

                foreach ($registrosEditados as $record) {
                    $id = trim($record[$id_key]);
                    $id_tipo_documento = trim($record['id_tipo_documento']);
                    $id_empresa = $record['id_empresa'];
                    $numero_documento = trim($record['numero_documento']);
                    $a_paterno = trim($record['apellido_paterno']);
                    $a_materno = trim($record['apellido_materno']);
                    $nombres = trim($record['nombres']);
                    $fecha_nacimiento = @$record['fecha_nacimiento'];
                    $direccion = @$record['direccion'];
                    $distrito_ubigeo = @$record['distrito_ubigeo'];
                    $pais = @$record['pais'];
                    $sexo = @$record['sexo'];
                    $celular = @$record['celular'];
                    $puesto = @$record['puesto'];
                    $fecha_ingreso = @$record['fecha_ingreso'];
                    $nombre_familiar = @$record['nombre_familiar'];
                    $telefono_referencia = @$record['telefono_referencia'];
                    $estado_civil = $record['estado_civil'];
                    // Create CASE statements for name and surname updates.
                    $idTipoDocumentoCases[] = "WHEN {$id_field_name} = $id THEN '$id_tipo_documento'";
                    $idEmpresasCases[] = "WHEN {$id_field_name} = $id THEN '$id_empresa'";
                    $numeroDocumentoCases[] = "WHEN {$id_field_name} = $id THEN '$numero_documento'";
                    $aPaternoCases[] = "WHEN {$id_field_name} = $id THEN '$a_paterno'";
                    $aMaternoCases[] = "WHEN {$id_field_name} = $id THEN '$a_materno'";
                    $nombresCases[] = "WHEN {$id_field_name} = $id THEN '$nombres'";
                    if ($fecha_nacimiento){
                        $fechaNacCases[] = "WHEN {$id_field_name} = $id THEN '$fecha_nacimiento'";
                    }
                    if ($sexo){
                        $sexoCases[] = "WHEN {$id_field_name} = $id THEN '$sexo'";
                    }
                    if ($fecha_ingreso){
                        $fechaIngresoCases[] = "WHEN {$id_field_name} = $id THEN '$fecha_ingreso'";
                    }
                    if ($direccion){
                        $direccionCases[] = "WHEN {$id_field_name} = $id THEN '$direccion'";
                    }
                    if ($distrito_ubigeo){
                        $distritoUbigeoCases[] = "WHEN {$id_field_name} = $id THEN '$distrito_ubigeo'";
                    }
                    if ($pais){
                        $paisCase[] = "WHEN {$id_field_name} = $id THEN '$pais'";
                    }
                    if ($celular){
                        $celularCase[] = "WHEN {$id_field_name} = $id THEN '$celular'";
                    }
                    if ($puesto){
                        $puestoCase[] = "WHEN {$id_field_name} = $id THEN '$puesto'";
                    }
                    if ($nombre_familiar){
                        $nombreFamiliarCase[] = "WHEN {$id_field_name} = $id THEN '$nombre_familiar'";
                    }
                    if ($telefono_referencia){
                        $telefonoReferenciaCase[] = "WHEN {$id_field_name} = $id THEN '$telefono_referencia'";
                    }
                    if ($estado_civil){
                        $estadoCivilCase[] = "WHEN {$id_field_name} = $id THEN '$estado_civil'";
                    }
                }

                // Build the raw SQL query.
                $sql = "
                    UPDATE  {$table_name}
                    SET
                        numero_documento = CASE " . implode(' ', $numeroDocumentoCases) . " ELSE numero_documento END,
                        id_tipo_documento = CASE " . implode(' ', $numeroDocumentoCases) . " ELSE id_tipo_documento END,
                        apellido_paterno = CASE " . implode(' ', $aPaternoCases) . " ELSE apellido_paterno END,
                        apellido_materno = CASE " . implode(' ', $aMaternoCases) . " ELSE apellido_materno END,
                        nombres = CASE " . implode(' ', $nombresCases) . " ELSE nombres END,
                        ". (count($fechaNacCases) > 0 ? "fe cha_nacimiento = CASE " . implode(' ', $fechaNacCases) . " ELSE fecha_nacimiento END, " : ""). "
                        ". (count($sexoCases) > 0 ? "sexo = CASE " . implode(' ', $sexoCases) . " ELSE sexo END, " : ""). "
                        ". (count($fechaIngresoCases) > 0 ? "fecha_ingreso = CASE " . implode(' ', $fechaIngresoCases) . " ELSE fecha_ingreso END, " : ""). "
                        ". (count($direccionCases) > 0 ? "direccion = CASE " . implode(' ', $direccionCases) . " ELSE direccion END, " : ""). "
                        ". (count($distritoUbigeoCases) > 0 ? "distrito_ubigeo = CASE " . implode(' ', $distritoUbigeoCases) . " ELSE distrito_ubigeo END, " : ""). "
                        ". (count($paisCase) > 0 ? "pais = CASE " . implode(' ', $paisCase) . " ELSE pais END, " : ""). "
                        ". (count($celularCase) > 0 ? "celular = CASE " . implode(' ', $celularCase) . " ELSE celular END, " : ""). "
                        ". (count($nombreFamiliarCase) > 0 ? "nombre_familiar = CASE " . implode(' ', $nombreFamiliarCase) . " ELSE nombre_familiar END, " : ""). "
                        ". (count($telefonoReferenciaCase) > 0 ? "telefono_referencia = CASE " . implode(' ', $telefonoReferenciaCase) . " ELSE telefono_referencia END, " : ""). "
                        ". (count($estadoCivilCase) > 0 ? "estado_civil = CASE " . implode(' ', $estadoCivilCase) . " ELSE estado_civil END, " : ""). "
                        updated_at = '".$ahora."'
                    WHERE {$id_field_name} IN (" . implode(',', array_fill(0, count($ids), '?')) . ")
                ";

                // Execute the query using Laravel's DB facade.
                DB::statement($sql, $ids);

                foreach ($registrosEditados as $record) {
                    $contrato_fecha_inicio = @$record['contrato_fecha_inicio'];
                    $contrato_salario = @$record['contrato_salario'];
                    $contrato_descuento = @$record['contrato_descuento'];
                    $contrato_horario = @$record['contrato_horario'];

                    $horas_semana = Horario::find($contrato_horario, ["total_horas_semana"])?->total_horas_semana;
                    $objCalcular = $this->calcularCostosDiaHora($contrato_salario, $dias_trabajo, $horas_semana);
                    $costo_hora = $objCalcular["costo_hora"];
                    $costo_dia = $objCalcular["costo_dia"];
                    $horas_dia = $objCalcular["horas_dia"];

                    $contrato = DB::table($table_contratos_name)
                                ->where([
                                    "numero_documento"=>$record[$id_key]
                                ])
                                ->whereNull("fecha_fin")
                                ->first(["id"]);

                    $id_empleado_contrato = $contrato->id;

                    $sql = "UPDATE  {$table_contratos_name}
                            SET
                                fecha_inicio = ?,
                                salario = ?,
                                descuento_planilla = ?,
                                costo_hora = ?,
                                costo_dia = ?,
                                dias_trabajo = {$dias_trabajo},
                                horas_dia = ?,
                                horas_semana = ?
                                updated_at = '".$ahora."'
                            WHERE id = ?
                        ";

                    // Execute the query using Laravel's DB facade.
                    DB::statement($sql, [$contrato_fecha_inicio, $contrato_salario, $contrato_descuento, $costo_hora, $costo_dia, $horas_dia, $horas_semana, $id_empleado_contrato]);

                    DB::statement("DELETE FROM {$table_contratos_horario_name} WHERE id_empleado_contrato = ?", [$id_empleado_contrato]);

                    DB::table($table_contratos_horario_name)->insert(
                        [
                           "id_empleado_contrato"=>$id_empleado_contrato,
                           "id_horario"=>$item["contrato_horario"],
                           "created_at"=>$ahora,
                           "updated_at"=>$ahora,
                        ]
                    );
                }

            }

            if (count($registrosInsertados) > 0){
                foreach ($registrosInsertados as $item) {
                    $newItem = [
                        "id_tipo_documento"=>$item["id_tipo_documento"],
                        "numero_documento"=>$item["numero_documento"],
                        "id_empresa"=>$item["id_empresa"],
                        "apellido_paterno" => $item["apellido_paterno"],
                        "apellido_materno" => $item["apellido_materno"],
                        "nombres" => $item["nombres"],
                        "direccion" => $item["direccion"],
                        "distrito_ubigeo" => $item["distrito_ubigeo"],
                        "pais" => $item["pais"],
                        "fecha_nacimiento" => $item["fecha_nacimiento"],
                        "celular" => $item["celular"],
                        "sexo" =>@$item["sexo"] ?? "M",
                        "estado_civil"=>$item["estado_civil"],
                        "puesto"=>$item["puesto"],
                        "telefono_referencia"=>$item["telefono_referencia"],
                        "nombre_familiar"=>$item["nombre_familiar"],
                        "fecha_ingreso" => $item["fecha_ingreso"],
                        "created_at"=>$ahora,
                        "updated_at"=>$ahora
                    ];

                    $codigoUnicoExiste = true;
                    while ($codigoUnicoExiste) {
                        $codigo_unico = strtoupper(Str::random(2));
                        $codigoUnicoExiste  = Empleado::query()
                                                    ->whereIn("codigo_unico", [$codigo_unico])
                                                    ->exists();
                    }

                    $newItem["codigo_unico"] = $codigo_unico;

                    $numero_orden = Empleado::query()
                        ->where("id_empresa", $item["id_empresa"])
                        ->max("numero_orden");

                    $newItem["numero_orden"]  = $numero_orden == null ? 1 : $numero_orden + 1;

                    $id_empleado = DB::table($table_name)->insertGetId($newItem);

                    $horas_semana = Horario::find($item["contrato_horario"], ["total_horas_semana"])?->total_horas_semana;
                    $objCalcular = $this->calcularCostosDiaHora($item["contrato_salario"], $dias_trabajo, $horas_semana);
                    $costo_hora = $objCalcular["costo_hora"];
                    $costo_dia = $objCalcular["costo_dia"];
                    $horas_dia = $objCalcular["horas_dia"];

                    //registro de contrato
                    $id_empleado_contrato = DB::table($table_contratos_name)->insertGetId(
                        [
                           "id_empleado"=>$id_empleado,
                           "fecha_inicio"=>$item["contrato_fecha_inicio"],
                           "salario"=>$item["contrato_salario"],
                           "costo_hora"=>$costo_hora,
                           "costo_dia"=>$costo_dia,
                           "dias_trabajo"=>$dias_trabajo,
                           "horas_dia"=>$horas_dia,
                           "descuento_planilla"=>$item["contrato_descuento"],
                           "created_at"=>$ahora,
                           "updated_at"=>$ahora,
                           "horas_semana"=>$horas_semana
                        ]
                    );

                    DB::table($table_contratos_horario_name)->insert(
                        [
                           "id_empleado_contrato"=>$id_empleado_contrato,
                           "id_horario"=>$item["contrato_horario"],
                           "created_at"=>$ahora,
                           "updated_at"=>$ahora,
                        ]
                    );

                }
            }
        }

        if (!is_null($dataBaja) && count($dataBaja) > 0){
            $ids = array_column($dataBaja, 'numero_documento');

            $sql = "
                    UPDATE {$table_name}
                    SET
                        deleted_at = '".$ahora."'
                    WHERE {$id_field_name} IN (" . implode(',', array_fill(0, count($ids), '?')) . ")
                ";

            DB::statement($sql, $ids);

            $sql = "
                    UPDATE {$table_contratos_name}
                    SET
                        deleted_at = '".$ahora."'
                    WHERE id_empleado in (SELECT {$id_field_name} FROM {$table_name} IN (" . implode(',', array_fill(0, count($ids), '?')) . "))
                ";

            DB::statement($sql, $ids);
        }

        return [
            "insertados_count"=>count($registrosInsertados),
            "editados_count"=>count($registrosEditados),
            "eliminados_count"=>count($dataBaja)
        ];
    }


}
