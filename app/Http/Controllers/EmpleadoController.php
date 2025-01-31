<?php

namespace App\Http\Controllers;

use App\DTO\EmpleadoDTO;
use App\Http\Requests\EmpleadoMasivoRequest;
use App\Http\Requests\EmpleadoRequest;
use App\Services\EmpleadoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{

    public function index(Request $request)
    {
        $data = $request->validate([
            "searchTerm" => "nullable|string|max:300",
            "id_empresa"=>"nullable|string"
        ]);

        if (isset($data["searchTerm"])){
            return (new EmpleadoService)->buscarTerm($data["searchTerm"]);
        }

        return (new EmpleadoService)->listar($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmpleadoRequest $request)
    {
        $data = $request->validated();

        $empleadoDTO = new EmpleadoDTO;

        $empleadoDTO->id_tipo_documento = $data["id_tipo_documento"];
        $empleadoDTO->numero_documento = $data["numero_documento"];
        //$empleadoDTO->codigo_unico = $data["codigo_unico"];
        $empleadoDTO->apellido_paterno = $data["apellido_paterno"];
        $empleadoDTO->apellido_materno = $data["apellido_materno"];
        $empleadoDTO->fecha_nacimiento = $data["fecha_nacimiento"];
        $empleadoDTO->nombres = $data["nombres"];
        $empleadoDTO->direccion = $data["direccion"];
        $empleadoDTO->distrito_ubigeo = $data["distrito_ubigeo"] ?? NULL;
        $empleadoDTO->pais = $data["pais"] ?? NULL;
        $empleadoDTO->contratos = $data["contratos"] ?? [];
        $empleadoDTO->id_empresa = $data["id_empresa"];
        $empleadoDTO->numero_orden = $data["numero_orden"];
        $empleadoDTO->celular = @$data["celular"];
        $empleadoDTO->sexo = $data["sexo"];
        $empleadoDTO->telefono_referencia = @$data["telefono_referencia"];
        $empleadoDTO->nombre_familiar = @$data["nombre_familiar"];
        $empleadoDTO->puesto = $data["puesto"];
        $empleadoDTO->estado_civil = $data["estado_civil"];
        $empleadoDTO->fecha_ingreso = $data["fecha_ingreso"];

        DB::beginTransaction();
        $empleado =  (new EmpleadoService)->registrar($empleadoDTO);
        DB::commit();
        return $empleado;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return (new EmpleadoService)->leer($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmpleadoRequest $request, int $id)
    {
        $data = $request->validated();

        $empleadoDTO = new EmpleadoDTO;

        $empleadoDTO->id_tipo_documento = $data["id_tipo_documento"];
        $empleadoDTO->numero_documento = $data["numero_documento"];
        //$empleadoDTO->codigo_unico = $data["codigo_unico"];
        $empleadoDTO->apellido_paterno = $data["apellido_paterno"];
        $empleadoDTO->apellido_materno = $data["apellido_materno"];
        $empleadoDTO->fecha_nacimiento = $data["fecha_nacimiento"];
        $empleadoDTO->nombres = $data["nombres"];
        $empleadoDTO->direccion = $data["direccion"];
        $empleadoDTO->distrito_ubigeo = $data["distrito_ubigeo"] ?? NULL;
        $empleadoDTO->pais = $data["pais"] ?? NULL;
        $empleadoDTO->contratos = $data["contratos"] ?? [];
        $empleadoDTO->id_empresa = $data["id_empresa"];
        $empleadoDTO->numero_orden = $data["numero_orden"];
        $empleadoDTO->celular = @$data["celular"];
        $empleadoDTO->sexo = @$data["sexo"];
        $empleadoDTO->telefono_referencia = @$data["telefono_referencia"];
        $empleadoDTO->nombre_familiar = @$data["nombre_familiar"];
        $empleadoDTO->puesto = @$data["puesto"];
        $empleadoDTO->estado_civil = @$data["estado_civil"];
        $empleadoDTO->fecha_ingreso = $data["fecha_ingreso"];

        DB::beginTransaction();
        $empleado = (new EmpleadoService)->editar($empleadoDTO, $id);
        DB::commit();

        return $empleado;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();
        $empleado = (new EmpleadoService)->eliminar($id);
        DB::commit();

        return $empleado;
    }


    public function finalizarContrato(Request $request, int $idEmpleadoContrato)
    {
        $data = $request->validate([
            "fecha_cese"=>"required|date",
            "razon_cese"=>"required|string|max:300",
        ]);

        DB::beginTransaction();
        [$fechaCese, $razonCese] = (new EmpleadoService)->finalizarContrato($idEmpleadoContrato, $data["fecha_cese"], $data["razon_cese"]);
        DB::commit();

        return ["fecha_cese"=>$fechaCese, "razon_cese"=>$razonCese];
    }

    public function procesarMasivo(EmpleadoMasivoRequest $request){
        $dataAlta = $request->getValidRecordsAlta();
        $dataInvalidadosAlta = $request->getInvalidRecordsAlta();

        $dataBaja = $request->getValidRecordsBaja();
        $dataInvalidadosBaja = $request->getInvalidRecordsBaja();

        DB::beginTransaction();
        $resultado = (new EmpleadoService)->upsertMasivo($dataAlta, $dataBaja);
        DB::commit();

        return response()->json([
            "message"=>"Proceso completado",
            "insertados_records_count"=>$resultado["insertados_count"],
            "editados_records_count"=>$resultado["editados_count"],
            "eliminados_records_count"=>$resultado["eliminados_count"],
            "upsertados_records_count"=>$resultado["insertados_count"] + $resultado["editados_count"],
            "correctos_records_count"=>$resultado["insertados_count"] + $resultado["editados_count"] + $resultado["eliminados_count"],
            "invalid_records_count"=>count($dataInvalidadosAlta) + count($dataInvalidadosBaja),
            "alta_invalid_records"=>$dataInvalidadosAlta,
            "baja_invalid_records"=>$dataInvalidadosBaja,
        ]);
    }

}
