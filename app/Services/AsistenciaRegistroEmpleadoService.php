<?php

namespace App\Services;

use App\Models\Empleado;
use App\Models\AsistenciaRegistroEmpleado;
use App\Models\EmpleadoContrato;
use App\Models\Empresa;
use App\Traits\FechaUtilTrait;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;
use Milon\Barcode\Facades\DNS1DFacade;

class AsistenciaRegistroEmpleadoService{

    use FechaUtilTrait;

    public function registrar(array $data) : array {
        $fecha_hora_registrado = (Carbon::now())->format("d/m/d H:i:s");

        $codigo_unico = $data["codigo_unico"];
        $fecha = $data["fecha"];
        //$id_punto_acceso = $data["id_punto_acceso"];

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

        $idEmpleadoContrato = $contratoActivo->id;

        if ($this->validarRepetidoDia($fecha, $idEmpleadoContrato)){
            throw new \Exception("Este colaborador ya tiene una asistencia hoy.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $nombreEmpleado = $empleado->nombres." ".$empleado->apellido_paterno." ".$empleado->apellido_materno;
        $numeroDiaSemana = date('N', strtotime($fecha));

        $idPuntoAcceso  = 1;

        AsistenciaRegistroEmpleado::create([
            "id_empleado_contrato"=>$idEmpleadoContrato,
            "fecha"=>$fecha,
            "numero_dia_semana"=>$numeroDiaSemana,
            "hora_entrada_mañana" => $data["hora_entrada_mañana"],
            "hora_salida_mañana" => $data["hora_salida_mañana"],
            "hora_entrada_tarde" => $data["hora_entrada_tarde"],
            "hora_salida_tarde" => $data["hora_salida_tarde"],
            "id_punto_acceso"=>$idPuntoAcceso,
        ]);

        return [
            "ok"=>1,
            "msg"=>"Asistencia registrada",
            "nombre_empleado"=>$nombreEmpleado,
            "fecha_hora_registrado"=>$fecha_hora_registrado
        ];
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

}
