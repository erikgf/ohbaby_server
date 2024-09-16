<?php

namespace Database\Seeders;

use App\Models\AsistenciaRegistroEmpleado;
use App\Models\Empleado;
use App\Models\EmpleadoContrato;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsistenciaRegistroEmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $separador = ",";
    private $lineaInicio = 0;

    public function run(): void
    {
        //
        $this->v_20240808_01();
    }

    private function insertarAsistenciaMasiva(string $archivo): void{
        $csvFile = fopen(base_path("./database/data/{$archivo}"), "r");
        $columnas = ["fecha", "codigo","hora_entrada_ma","hora_salida_ma","hora_entrada_ta","hora_salida_ta"];
        $lineaActual = 0;

        while (($data = fgetcsv($csvFile, 2000, $this->separador)) !== FALSE) {
            if ($lineaActual > $this->lineaInicio){
                $colValues = [];

                foreach ($columnas as $key => $value) {
                    if (!$value) continue;
                    $colValues[$value] = $data[$key];
                }

                $fecha = $colValues["fecha"];
                $codigo = $colValues["codigo"];
                $horaEntradaMañana = $colValues["hora_entrada_ma"]  == "" ? null : $colValues["hora_entrada_ma"];
                $horaSalidaMañana = $colValues["hora_salida_ma"]  == "" ? null : $colValues["hora_salida_ma"];
                $horaEntradaTarde = $colValues["hora_entrada_ta"]  == "" ? null : $colValues["hora_entrada_ta"];
                $horaSalidaTarde = $colValues["hora_salida_ta"] == "" ? null : $colValues["hora_salida_ta"];

                //$empleado = Empleado::with("contratoActivo")->where(["codigo_unico"=>$codigo])->first();
                $empleadoContratoActivo = EmpleadoContrato::whereHas("empleado",
                    function($q) use($codigo){
                        $q->where(["codigo_unico"=>$codigo]);
                    })
                    ->orderBy("fecha_inicio","desc")
                    ->first();

                if (!$empleadoContratoActivo){
                    throw new \Exception("No existe contrato con el usuario de codigo {$codigo}", 1);
                }

                $idPuntoAcceso  = 1;
                $numeroDiaSemana = date('N', strtotime($fecha));
                $item = AsistenciaRegistroEmpleado::create([
                    "id_empleado_contrato"=>$empleadoContratoActivo->id,
                    "fecha"=>$fecha,
                    "numero_dia_semana"=>$numeroDiaSemana,
                    "hora_entrada_mañana" => $horaEntradaMañana,
                    "hora_salida_mañana" => $horaSalidaMañana,
                    "hora_entrada_tarde" => $horaEntradaTarde,
                    "hora_salida_tarde" => $horaSalidaTarde,
                    "id_punto_acceso"=>$idPuntoAcceso,
                ]);

                $this->command->info('OK '.json_encode($item));
            }

            $lineaActual++;
        }

        fclose($csvFile);
    }

    private function v_20240808_01(): void{

        DB::beginTransaction();

        $archivo = "asistencias_masivas_v_20240808_01.csv";

        try {
            $this->insertarAsistenciaMasiva($archivo);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->error("ERROR {$th->getMessage()}");
        }

        DB::commit();
    }
}
