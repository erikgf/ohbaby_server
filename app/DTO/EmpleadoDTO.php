<?php

namespace App\DTO;

class EmpleadoDTO {
    public int $id;
    public string $id_tipo_documento;
    public string $numero_documento;
    public string $apellido_paterno;
    public string $apellido_materno;
    public string $nombres;
    public ?string $codigo_unico;
    public ?string $direccion;
    public ?string $distrito_ubigeo;
    public ?string $pais;
    public ?string $fecha_nacimiento;
    public array $contratos;
    public ?int $id_empresa;
    public ?int $numero_orden;
    public ?string $celular;
    public string $sexo;
    public ?string $telefono_referencia;
    public ?string $nombre_familiar;
    public string $puesto;
    public string $estado_civil;
    public string $fecha_ingreso;

}
