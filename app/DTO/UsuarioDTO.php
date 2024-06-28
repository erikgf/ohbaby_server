<?php

namespace App\DTO;

class UsuarioDTO{
    public int $id_usuario;
    public ?string $name;
    public int $id_rol;
    public ?string $nombre_rol;
    public string $username;
    public string $password;
    public string $estado_acceso;
}
