<?php

namespace App\Services;

use App\DTO\UsuarioDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioService{

    public function listar(){
        return User::all();
    }

    public function leer(int $id){
        return User::findOrFail($id);
    }

    public function registrar(UsuarioDTO $usuarioDTO) : User{
        $usuario = new User;
        $usuario->name = $usuarioDTO->name;
        $usuario->id_rol = $usuarioDTO->id_rol;
        $usuario->username = $usuarioDTO->username;
        $usuario->password = $usuarioDTO->password;
        $usuario->estado_acceso = $usuarioDTO->estado_acceso;
        $usuario->save();
        return $usuario;
    }

    public function editar(UsuarioDTO $usuarioDTO, int $id) : User{
        $usuario = User::findOrFail($id);

        $usuario->name = $usuarioDTO->name;
        $usuario->id_rol = $usuarioDTO->id_rol;
        $usuario->estado_acceso = $usuarioDTO->estado_acceso;
        $usuario->save();

        if ($usuario->estado_acceso == "I"){
            //Elimina todas las sesiones asociadas.
            $usuario->tokens()->delete();
        }

        return $usuario;
    }

    public function darBajaAlta(int $id, string $estado_acceso = "I") : User{
        $existedUsuario = User::findOrFail($id);
        $existedUsuario->estado_acceso = $estado_acceso;
        $existedUsuario->save();

        return $existedUsuario;
    }

    public function anular(int $id) : User{
        $existedUsuario = User::findOrFail($id);
        $existedUsuario->tokens()->delete();
        $existedUsuario->delete();

        return $existedUsuario;
    }

    public function cambiarClave(User $user, string $clave) : User{
        $user->password = Hash::make($clave);
        $user->save();
        $user->tokens()->delete();

        return $user;
    }

}
