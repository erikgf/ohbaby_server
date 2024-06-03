<?php

namespace App\DTO;

class MarcadoEmpleadoDTO {
    public int $id;
    public int $id_empleado_contrato;
    public ?string $hora;
    public ?string $fecha;
    public ?string $numero_dia_semana;
    public int $id_punto_acceso;
}
