<?php

namespace App\Services;

use App\Http\Resources\FormularioAsistenciaResource;
use App\Http\Resources\ReporteAsistenciaDiaResource;
use App\Models\Empleado;
use App\Models\AsistenciaRegistroEmpleado;
use App\Models\EmpleadoContrato;
use App\Models\Empresa;
use App\Models\Horario;
use App\Traits\FechaUtilTrait;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Milon\Barcode\DNS1D;

class AsistenciaRegistroEmpleadoService{

    use FechaUtilTrait;

    public function registrar(array $data) {
        $fecha_hora_registrado = (Carbon::now())->format("d/m/d H:i:s");

        $fecha = $data["fecha"];

        AsistenciaRegistroEmpleado::where([
            "fecha"=>$fecha
        ])->delete();

        $asistencias = $data["asistencias"];
        $numeroDiaSemana = Carbon::parse($fecha)->dayOfWeek;

        $idPuntoAcceso  = 1;
        $insertAsistencias = [];

        foreach ($asistencias as $asistencia) {
            if ($asistencia["falto"] == "1"){
                continue;
            }

            $horaEntradaMañana = $asistencia["turno_uno_entrada"];
            $horaSalidaMañana = $asistencia["turno_uno_salida"];
            $horaEntradaTarde = @$asistencia["turno_dos_entrada"];
            $horaSalidaTarde = @$asistencia["turno_dos_entrada"];

            $horasMañana = $this->tiempoDecimalPorHora($horaSalidaMañana) - $this->tiempoDecimalPorHora($horaEntradaMañana);
            $horasTarde = $this->tiempoDecimalPorHora($horaSalidaTarde) - $this->tiempoDecimalPorHora($horaEntradaTarde);
            $totalHoras = $horasMañana + $horasTarde;

            $insertAsistencias[] = [
                "id_empleado_contrato"=>$asistencia["id"],
                "fecha"=>$fecha,
                "numero_dia_semana"=>$numeroDiaSemana,
                "hora_entrada_mañana" => $asistencia["turno_uno_entrada"],
                "hora_salida_mañana" => $asistencia["turno_uno_salida"],
                "hora_entrada_tarde" => @$asistencia["turno_dos_entrada"],
                "hora_salida_tarde" => @$asistencia["turno_dos_entrada"],
                "id_punto_acceso"=>$idPuntoAcceso,
                "total_horas"=>$totalHoras,
                "created_at"=>$fecha_hora_registrado,
                "updated_at"=>$fecha_hora_registrado
            ];
        }

        AsistenciaRegistroEmpleado::insert($insertAsistencias);
        return count($insertAsistencias);
    }

    public function getDataControlSeguridad(string $fecha, string $tipo = "") {
        $time = strtotime($fecha);
        $diaSemana = $this->getDiaSemanaNombre(date('N', $time));
        $dia = date('d', $time);
        $mes = $this->getMesNombre(date('m', $time));
        $año = date('Y', $time);

        $fechaComprimida = date("Ymd", $time);

        $query  =   EmpleadoContrato::with(["empleado"])
                        ->join('empleados', 'empleado_contratos.id_empleado', '=', 'empleados.id')
                        ->orderBy('empleados.numero_orden')
                        ->whereNull("fecha_fin")
                        ->select('empleado_contratos.*','empleados.id','empleado_contratos.id_empleado');

        $rotuloTitulo = "TODOS";
        if ($tipo != ""){
            $empresaFiltro = Empresa::where(["numero_documento"=>"99999999999"])->first();
            if ($empresaFiltro){
                if ($tipo === "varios"){
                    $query->whereIn("id_empresa", [$empresaFiltro->id]);
                    $rotuloTitulo = "OTROS";
                } else {
                    $query->whereNotIn("id_empresa", [$empresaFiltro->id]);
                    $rotuloTitulo = "VARIOS";
                }
            }
        }

        $empleados = $query->get();

        $empleados = $empleados->map(function($item) use ($fechaComprimida){
            $codigo_unico = $item->empleado->codigo_unico;
            $nombres_empleado = $item->empleado->apellido_paterno." ".$item->empleado->apellido_materno.", ".$item->empleado->nombres;
            $qr = $fechaComprimida.".".$codigo_unico;

            return [
                "codigo"=>strtoupper($codigo_unico),
                "nombres_empleado"=>$nombres_empleado,
                "qr"=>DNS1D::getBarcodeHTML($qr,'C128', 2, 55)//asset("storage/$qr.png")
	        ];
        });

        return [
            "dia"=>$dia,
            "mes"=>$mes,
            "anio"=>$año,
            "diaSemana"=>$diaSemana,
            "empleados"=>$empleados,
            "rotuloTitulo"=>$rotuloTitulo
        ];
    }

    public function consultar(string $fechaUnida, string $codigo_unico) : array {

        $fecha = substr($fechaUnida, 0, 4)."-".substr($fechaUnida, 4, 2)."-".substr($fechaUnida, 6, 2);

        $empleado= Empleado::with("contratoActivo")
                            ->where(["codigo_unico"=>$codigo_unico])
                            ->first();

        if (!$empleado){
            return [
                "ok"=>0,
                "msg"=>"No encontrado"
            ];
        }

        $contratoActivo = $empleado?->contratoActivo;
        if (!$contratoActivo){
            return [
                "ok"=>0,
                "msg"=>"Personal sin contrato activo"
            ];
        }

        if ($this->validarRepetidoDia($fecha, $contratoActivo->id)){
            throw new \Exception("Este colaborador ya tiene una asistencia hoy.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $nombreEmpleado = $empleado->nombres." ".$empleado->apellido_paterno." ".$empleado->apellido_materno;

        return [
            "ok"=>1,
            "nombre_empleado"=>$nombreEmpleado,
            "fecha_formateada"=>date("d/m/Y", strtotime($fecha)),
            "fecha"=>$fecha,
            "codigo_unico"=>$codigo_unico,
            "manana_entrada"=>"08:00",
            "manana_salida"=>"13:00",
            "tarde_entrada"=>"14:00",
            "tarde_salida"=>"18:00",
        ];
    }

    public function validarRepetidoDia(string $fecha, int $id_empleado_contrato){
        return  AsistenciaRegistroEmpleado::where(["fecha"=>$fecha, "id_empleado_contrato"=>$id_empleado_contrato])->exists();
    }

    public function getDataFormularioAsistencia(string $fecha){
        $registros = Empleado::query()
                ->whereHas("contratos", function($q) use($fecha){
                    $q->whereNull("fecha_fin");
                    $q->orWhere( function($q) use ($fecha) {
                        $q->where("fecha_inicio", "<=", $fecha);
                        $q->where("fecha_fin", ">=", $fecha);
                    });
                })
                ->with([
                    "contratos" => function($q) use($fecha){
                        $q->with([
                            "horarios" => fn($q) => $q->select("id")
                        ]);
                        $q->whereNull("fecha_fin");
                        $q->orWhere( function($q) use ($fecha) {
                            $q->where("fecha_inicio", "<=", $fecha);
                            $q->where("fecha_fin", ">=", $fecha);
                        });
                        $q->select("id", "id_empleado");
                    }
                ])
                ->select("id", "codigo_unico", "apellido_paterno", "apellido_materno", "nombres")
                ->orderBy("apellido_paterno")
                ->get();

        $horariosId = [];
        foreach ($registros as $empleado) {
            foreach ($empleado->contratos as $contrato) {
                foreach ($contrato->horarios as $horario) {
                    $horariosId[] = $horario->id;
                }
            }
        }

        $horariosId = array_values(array_unique($horariosId));

        $horarios = Horario::query()
                        ->with([
                            "horarioDetalles" => function($q) {
                                $q->select("id_horario", "hora_inicio", "hora_fin", "dias");
                                $q->orderBy("dias");
                                $q->orderBy("hora_inicio");
                            }
                        ])
                        ->whereIn("id", $horariosId)
                        ->get([
                           "id"
                        ]);


        $registros_realizados = AsistenciaRegistroEmpleado::where([
            "fecha" => $fecha
        ])
        ->select([
            "id_empleado_contrato",
            "hora_entrada_mañana",
            "hora_salida_mañana",
            "hora_entrada_tarde",
            "hora_salida_tarde"
        ])
        ->get();

        return  [
                    "registros"=>FormularioAsistenciaResource::collection($registros),
                    "registros_realizados"=>$registros_realizados,
                    "horarios"=>$horarios->map(function($horario){
                        $horario->horarioDetalles->map(function ($horario_detalle){
                            $horario_detalle->dias = explode(",", $horario_detalle->dias);
                            return $horario_detalle;
                        });
                        return $horario;
                    })
                ];
    }

    public function getDataAsistenciaManual(string $fecha){
        $registros = Empresa::query()
                        ->whereHas("empleados")
                        ->with([
                            "empleados" => function($q) use($fecha){
                                $q->with([
                                    "contratos" => function($q) use($fecha){
                                        $q->with([
                                            "horarios" => fn($q) => $q->select("id")
                                        ]);
                                        $q->whereNull("fecha_fin");
                                        $q->orWhere( function($q) use ($fecha) {
                                            $q->where("fecha_inicio", "<=", $fecha);
                                            $q->where("fecha_fin", ">=", $fecha);
                                        });
                                        $q->with([
                                            "asistencias" => function($q) use ($fecha){
                                                $q->where("fecha", "=", $fecha);
                                            }
                                        ]);
                                        $q->select("id", "id_empleado");
                                    }
                                ]);
                                $q->select("id", "id_empresa", "codigo_unico", "apellido_paterno", "apellido_materno", "nombres");
                                $q->orderBy("numero_orden");
                            }
                        ])
                        ->select("id", "razon_social")
                        ->get();

        $horariosId = [];
        foreach ($registros as $item) {
            foreach ($item->empleados as $empleado) {
                foreach ($empleado->contratos as $contrato) {
                    foreach ($contrato->horarios as $horario) {
                        $horariosId[] = $horario->id;
                    }
                }
            }
        }

        $horariosId = array_values(array_unique($horariosId));

        $horarios = Horario::query()
                        ->with([
                            "horarioDetalles" => function($q) {
                                $q->select("id_horario", "hora_inicio", "hora_fin", "dias");
                                $q->orderBy("dias");
                                $q->orderBy("hora_inicio");
                            }
                        ])
                        ->whereIn("id", $horariosId)
                        ->get([
                           "id"
                        ]);

        return  [
                    "registros"=>ReporteAsistenciaDiaResource::collection($registros),
                    "horarios"=>$horarios->map(function($horario){
                        $horario->horarioDetalles->map(function ($horario_detalle){
                            $horario_detalle->dias = explode(",", $horario_detalle->dias);
                            return $horario_detalle;
                        });
                        return $horario;
                    })
                ];
    }

}
