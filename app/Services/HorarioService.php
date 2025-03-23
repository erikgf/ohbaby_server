<?php

namespace App\Services;

use App\DTO\HorarioDTO;
use App\Http\Resources\HorarioResource;
use App\Models\Horario;
use App\Models\HorarioDetalle;
use App\Traits\HorarioUtilTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\ValidationException;

class HorarioService{

    use HorarioUtilTrait;

    public function registrar(HorarioDTO $horarioDTO) : HorarioResource {
        if (count($horarioDTO->horarioDetalles) <= 0){
            throw ValidationException::withMessages(["No se ha enviado detalle de horarios."]);
        }

        $horarioDetalles = [];
        $totalHorasSemana = 0;
        for ($i=0; $i < count($horarioDTO->horarioDetalles); $i++) {
            $item = $horarioDTO->horarioDetalles[$i];
            $dias = $item["dias"];
            $hora_inicio = $item["hora_inicio"];
            $hora_fin = $item["hora_fin"];
            /*
            if (!$this->validarSoloSemanaDias($dias)){
                throw new \Exception("Se está enviando un horario detalle con días no válidos. Lunes a Sábado (1 - 6) ", Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            */
            $totalHorasSemana += $this->obtenerHorasSemanaHorarioDetalle($dias, $hora_inicio, $hora_fin);
            $horarioDetalles[] = new HorarioDetalle([
                "hora_inicio"=>$hora_inicio,
                "hora_fin"=>$hora_fin,
                "dias"=>$dias,
            ]);
        }


        $horario = Horario::create([
            "descripcion"=>$horarioDTO->descripcion,
            "total_horas_semana"=>$totalHorasSemana
        ]);

        $horario->horarioDetalles()->saveMany($horarioDetalles);
        return new HorarioResource($horario);
    }

    public function editar(HorarioDTO $horarioDTO, int $id) : HorarioResource{

        $horarioEditado = Horario::findOrFail($id);

        $horarioDetalles = $horarioDTO->horarioDetalles;
        $idDetalles = [];

        foreach ($horarioDetalles as $value) {
            if ($value["id"] != null){
                array_push($idDetalles, $value["id"]);
            }
        }

        $detallesBorrar = HorarioDetalle::whereNotIn("id", $idDetalles)->where(["id_horario"=>$id])->get(["id"]);

        foreach ($detallesBorrar as $detalleBorrar) {
            $detalleBorrar->delete();
        }

        foreach ($horarioDetalles as $item) {
            $id = $item["id"];
            $hora_inicio = $item["hora_inicio"];
            $hora_fin = $item["hora_fin"];
            $dias = $item["dias"];

            if (!$id){
                $horarioDetalle = new HorarioDetalle();
                $horarioDetalle->create([
                    "id_horario"=>$horarioEditado->id,
                    "hora_inicio"=>$hora_inicio,
                    "hora_fin"=>$hora_fin,
                    "dias"=>$dias
                ]);
            } else {
                $horarioDetalle = HorarioDetalle::findOrFail($id);
                $horarioDetalle->update([
                    "hora_inicio"=>$hora_inicio,
                    "hora_fin"=>$hora_fin,
                    "dias"=>$dias
                ]);
            }
        }

        $horarioEditado->load("horarioDetalles");

        $totalHorasSemana = 0;
        for ($i=0; $i < count($horarioEditado->horarioDetalles); $i++) {
            $item = $horarioDTO->horarioDetalles[$i];
            $dias = $item["dias"];
            $hora_inicio = $item["hora_inicio"];
            $hora_fin = $item["hora_fin"];
            $totalHorasSemana += $this->obtenerHorasSemanaHorarioDetalle($dias, $hora_inicio, $hora_fin);
        }

        $horarioEditado->fill([
            "descripcion"=>$horarioDTO->descripcion,
            "total_horas_semana"=>$totalHorasSemana
        ]);

        $horarioEditado->save();

        return new HorarioResource($horarioEditado);
    }

    public function eliminar(int $id) : int{
        $horario = Horario::findOrFail($id);
        $horario->horarioDetalles()->delete();
        $horario->delete();

        return $horario->id;
    }

    public function listar() : ResourceCollection{
        $horarios = Horario::with("horarioDetalles")->get();
        return HorarioResource::collection($horarios);
    }

    public function leer(int $id) : HorarioResource{
        $horario = Horario::findOrFail($id);
        return new HorarioResource($horario);
    }

}
