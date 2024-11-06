<?php

namespace App\DTO;

class EntregaDTO {
    public int $id;
    public int $id_empleado_contrato;
    public int $id_tipo_entrega;
    public ?string $motivo;
    public string $fecha_registro;
    public float $monto_registrado;
    public array $cuotas;
}
