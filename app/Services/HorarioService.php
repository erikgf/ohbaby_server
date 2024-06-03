<?php

namespace App\Services;

use App\DTO\HorarioDTO;
use App\Http\Resources\HorarioResource;
use App\Models\Horario;
use App\Models\HorarioDetalle;
use App\Traits\HorarioUtilTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class HorarioService{

    use HorarioUtilTrait;

    public function registrar(HorarioDTO $horarioDTO) : HorarioResource {

        $horario = Horario::create([
            "descripcion"=>$horarioDTO->descripcion,
        ]);

        if (count($horarioDTO->horarioDetalles) <= 0){
            throw new \Exception("No se ha enviado detalle de horarios", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $horarioDetalles = array_map(function($item){
            $dias = $item["dias"];
            /*
            if (!$this->validarSoloSemanaDias($dias)){
                throw new \Exception("Se está enviando un horario detalle con días no válidos. Lunes a Sábado (1 - 6) ", Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            */

            return new HorarioDetalle([
                "hora_inicio"=>$item["hora_inicio"],
                "hora_fin"=>$item["hora_fin"],
                "dias"=>$dias,
            ]);
        }, $horarioDTO->horarioDetalles);

        $horario->horarioDetalles()->saveMany($horarioDetalles);
        return new HorarioResource($horario);
    }

    public function editar(HorarioDTO $horarioDTO, int $id) : HorarioResource{

        $horarioEditado = Horario::findOrFail($id);

        $horarioEditado->fill([
            "descripcion"=>$horarioDTO->descripcion,
        ]);

        $horarioEditado->save();

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
