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
        //$this->v_20240808_01();
        //$this->v_20241002_01();
        //$this->v_20241004_01();
        $this->v_20241031_01();
        $this->v_20241031_02();
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

                $horasMañana = $this->tiempoDecimalPorHora($horaSalidaMañana) - $this->tiempoDecimalPorHora($horaEntradaMañana);
                $horasTarde = $this->tiempoDecimalPorHora($horaSalidaTarde) - $this->tiempoDecimalPorHora($horaEntradaTarde);
                $totalHoras = $horasMañana + $horasTarde;

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

    private function v_20240808_01(): void{

        DB::beginTransaction();

        $archivo = "asistencias_masivas_v_20240808_01.csv";
        AsistenciaRegistroEmpleado::where("fecha",">=","2024-07-01")->forceDelete();

        try {
            $this->insertarAsistenciaMasiva($archivo);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->error("ERROR {$th->getMessage()}");
        }

        DB::commit();
    }

    private function v_20241002_01(): void{

        DB::beginTransaction();

        $archivo = "asistencias_masivas_v_202401002_01.csv";
        AsistenciaRegistroEmpleado::where("fecha",">=","2024-09-01")->forceDelete();

        try {
            $this->insertarAsistenciaMasiva($archivo);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->error("ERROR {$th->getMessage()}");
        }

        DB::commit();
    }

    private function v_20241004_01(): void{

        DB::beginTransaction();

        $archivo = "asistencias_masivas_v_20241004_01.csv";
        AsistenciaRegistroEmpleado::where("fecha",">=","2024-09-01")->forceDelete();

        try {
            $this->insertarAsistenciaMasiva($archivo);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->error("ERROR {$th->getMessage()}");
        }

        DB::commit();
    }


    private function v_20241031_01(): void{

        DB::beginTransaction();

        $archivo = "asistencias_masivas_v_20241031_01.csv";
        AsistenciaRegistroEmpleado::where("fecha",">=","2024-10-01")->forceDelete();

        try {
            $this->insertarAsistenciaMasiva($archivo);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->error("ERROR {$th->getMessage()}");
        }

        DB::commit();
    }

    private function v_20241031_02(): void{

        DB::beginTransaction();

        $archivo = "asistencias_masivas_v_20241031_02.csv";
        AsistenciaRegistroEmpleado::where("fecha",">=","2024-10-30")->forceDelete();

        try {
            $this->insertarAsistenciaMasiva($archivo);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->error("ERROR {$th->getMessage()}");
        }

        DB::commit();
    }

}
