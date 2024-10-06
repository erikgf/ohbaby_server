<?php

namespace Database\Seeders;

use App\DTO\EmpleadoDTO;
use App\Models\AsistenciaRegistroEmpleado;
use App\Models\Empleado;
use App\Models\EmpleadoContrato;
use App\Models\Empresa;
use App\Models\Entrega;
use App\Models\EntregaCuota;
use App\Models\MarcadoEmpleado;
use App\Services\EmpleadoService;
use App\Services\HorarioEmpleadoContratoService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpleadoProduccionSeeder extends Seeder
{
    private $separador = ",";
    private $lineaInicio = 0;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        MarcadoEmpleado::truncate();
        EntregaCuota::truncate();
        Entrega::query()->forceDelete();
        AsistenciaRegistroEmpleado::query()->forceDelete();
        DB::table('empleado_contrato_horario')->delete();
        EmpleadoContrato::query()->forceDelete();
        Empleado::query()->forceDelete();

        $this->insertarEmpleados("empleados_production_v1.csv");
    }

    private function insertarEmpleados(string $archivo): void{
        $csvFile = fopen(base_path("./database/data/{$archivo}"), "r");
        $columnas = ["id","id_tipo_documento","numero_documento","apellido_paterno","apellido_materno","nombres","codigo_unico","INFO",
                    "direccion","distrito","departamento","provincia","distrito_ubigeo","pais","fecha_nacimiento","id_empresa","numero_orden",
                    "celular","sexo","estado_civil","puesto","telefono_referencia","nombre_familiar","observaciones","id_horario",
                    "total_horas_semanales","sueldo","dias_trabajo","dias_trabajo_semana","costo_semanal","costo_hora","horas_dia"
        ];
        $lineaActual = 0;

        DB::beginTransaction();

        while (($data = fgetcsv($csvFile, 2000, $this->separador)) !== FALSE) {
            if ($lineaActual > $this->lineaInicio){
                $colValues = [];

                foreach ($columnas as $key => $value) {
                    if (!$value) continue;
                    $colValues[$value] = $data[$key];
                }

                /*
                1. registrar en Empleado,
                2. Registrar su contrato activo (usando los datos asoiados)
                3. Registrarlos en ese horario
                */
                //1.
                $empleadoDTO = new EmpleadoDTO;
                $empleadoDTO->id_tipo_documento = $colValues["id_tipo_documento"];
                $empleadoDTO->numero_documento = $colValues["numero_documento"];
                $empleadoDTO->apellido_paterno = $colValues["apellido_paterno"];
                $empleadoDTO->apellido_materno = $colValues["apellido_materno"];
                $empleadoDTO->nombres = $colValues["nombres"];
                $empleadoDTO->codigo_unico = $colValues["codigo_unico"];
                $empleadoDTO->direccion = $colValues["direccion"];

                if ($colValues["fecha_nacimiento"] != ""){
                    [$dia,$mes,$ano] = explode("/",$colValues["fecha_nacimiento"]);
                    $empleadoDTO->fecha_nacimiento = "{$ano}/{$mes}/{$dia}";
                } else {
                    $empleadoDTO->fecha_nacimiento = null;
                }

                $empleadoDTO->distrito_ubigeo = $colValues["distrito_ubigeo"];

                $empleadoDTO->pais = $colValues["pais"];
                $empresa = Empresa::where(["razon_social" => $colValues["id_empresa"]])->first();
                $empleadoDTO->id_empresa = $empresa->id;

                $empleadoDTO->numero_orden = $colValues["numero_orden"];
                $empleadoDTO->celular = $colValues["celular"];
                $empleadoDTO->sexo = $colValues["sexo"];
                $empleadoDTO->nombre_familiar = $colValues["nombre_familiar"];
                $empleadoDTO->telefono_referencia = $colValues["telefono_referencia"];
                $empleadoDTO->puesto = $colValues["puesto"];
                $empleadoDTO->estado_civil = $colValues["estado_civil"];

                //2.
                $contrato = [
                    "dias_trabajo"=>(int) $colValues["dias_trabajo"],
                    "horas_dia"=>(float)  $colValues["horas_dia"],
                    "salario"=>(float) str_replace(",","",$colValues["sueldo"]),
                    "fecha_inicio"=>"2024/09/01"
                ];

                $empleadoDTO->contratos = [$contrato];
                $empleado = (new EmpleadoService())->registrar($empleadoDTO);

                $this->command->info('OK '.json_encode($empleado));
            }

            $lineaActual++;
        }

        $empleadoContratos = EmpleadoContrato::query()->get()->pluck("id");
        (new HorarioEmpleadoContratoService())->registrar((int) $colValues["id_horario"], $empleadoContratos->toArray());

        DB::commit();


        fclose($csvFile);
    }
}
