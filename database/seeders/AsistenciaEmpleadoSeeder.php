<?php

namespace Database\Seeders;

use App\Models\AsistenciaEmpleado;
use App\Models\EmpleadoContrato;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsistenciaEmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $separador = ",";
    private $lineaInicio = 0;

    public function run(): void
    {
        $this->v_20250201_01();
    }

    private function insertarAsistenciaMasiva(string $archivo): void{
        $csvFile = fopen(base_path("./database/data/{$archivo}"), "r");
        $columnas = ["fecha", "codigo","hora_entrada","hora_salida"];
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
                $horaEntrada = $colValues["hora_entrada"]  == "" ? null : $colValues["hora_entrada"];
                $horaSalida = $colValues["hora_salida"]  == "" ? null : $colValues["hora_salida"];

                $totalHoras = $this->tiempoDecimalPorHora($horaSalida) - $this->tiempoDecimalPorHora($horaEntrada);

                //$empleado = Empleado::with("contratoActivo")->where(["codigo_unico"=>$codigo])->first();
                $empleadoContratoActivo = EmpleadoContrato::whereHas("empleado",
                    function($q) use($codigo){
                        $q->where(["codigo_unico"=>$codigo]);
                    })
                    ->orderBy("fecha_inicio","desc")
                    ->first();

                if (!$empleadoContratoActivo){
                    //throw new \Exception("No existe contrato con el usuario de codigo {$codigo}", 1);
                    continue;
                }

                $fechaAr = explode("/",$fecha);
                $fecha = "{$fechaAr[2]}-{$fechaAr[1]}-{$fechaAr[0]}";

                $idPuntoAcceso  = 1;
                $numeroDiaSemana = date('N', strtotime($fecha));
                $item = AsistenciaEmpleado::create([
                    "id_empleado_contrato"=>$empleadoContratoActivo->id,
                    "fecha"=>$fecha,
                    "numero_dia_semana"=>$numeroDiaSemana,
                    "hora_entrada" => $horaEntrada,
                    "fecha_hora_entrada"=>"{$fecha} {$horaEntrada}",
                    "hora_salida" => $horaSalida,
                    "fecha_hora_salida"=>"{$fecha} {$horaSalida}",
                    "id_punto_acceso"=>$idPuntoAcceso,
                    "total_horas"=>$totalHoras
                ]);

                $this->command->info('OK '.json_encode($item));
            }

            $lineaActual++;
        }

        fclose($csvFile);
    }

    private function tiempoDecimalPorHora($hora){
        if ($hora == null){
            return 0;
        }

        $cadenaHoras = strlen($hora);
        $esCadenaValida = $cadenaHoras === 8 || $cadenaHoras === 5;
        $esConsideraSegundos = $cadenaHoras === 8;

        $arregloHoras = explode(":", $hora);
        $esArregloValido = $esConsideraSegundos
                                ? count($arregloHoras) == 3
                                : count($arregloHoras) == 2;

        $esHoraValida =  $esCadenaValida && $esArregloValido;

        if (!$esHoraValida){
            return 0;
        }

        if ($esConsideraSegundos){
            [$hora, $min, $seg] = $arregloHoras;
        } else {
            [$hora, $min] = $arregloHoras;
            $seg = 0;
        }

        $horasDec = $hora * 1.0;
        $minDec = $min / 60.0;
        $segDec = $seg / 3600.00;

        return $horasDec + $minDec + $segDec;
    }

    private function v_20250201_01(): void{

        DB::beginTransaction();

        $empleadoContratoActivo = EmpleadoContrato::whereHas("empleado",
                                    function($q){
                                        $q->where(["codigo_unico"=>"UP"]);
                                    })
                                    ->orderBy("fecha_inicio","desc")
                                    ->first();
        $archivo = "asistencias_masivas_v_20250201_01.csv";
        AsistenciaEmpleado::where([
            "id_empleado_contrato"=>$empleadoContratoActivo->id
        ])
        ->where("fecha", ">=", "2025-01-01")
        ->forceDelete();

        try {
            $this->insertarAsistenciaMasiva($archivo);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->error("ERROR {$th->getMessage()}");
        }

        DB::commit();
    }
}
