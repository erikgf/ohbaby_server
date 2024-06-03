<?php

namespace App\DTO;

class EmpleadoContratoDTO {
    public int $id;
    public int $id_empleado;
    public string $fecha_inicio;
    public string $fecha_fin;
    public float $salario;
    public float $costo_hora;
    public int $dias_trabajo;
    public int $horas_dia;

    public ?array $horarios;
}
