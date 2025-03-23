<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SesionService{

    public function iniciarSesion(string $username, string $password){
        $user = User::where("username", $username)->first();
        if (!$user){
            throw ValidationException::withMessages(["Usuario no existe."]);
        }

        if (!Hash::check($password, $user->password)){
            throw ValidationException::withMessages(["Contraseña incorrecta."]);
        }

        if ($user->estado_acceso === "I"){
            throw ValidationException::withMessages(["Usuario inactivo."]);
        }

        $url_main = "./main";
        $token = $user->createToken(Config::get("session.session_name"), ['*'], now()->addYear())->plainTextToken;
        $response = [
            'user'=>[
                "id"=>$user->id,
                "name"=>$user->name,
                "username">=$user->username,
                "id_rol"=>$user->id_rol,
                "url_main"=>$url_main
            ],
            'token'=>$token
        ];

        return $response;
    }

    public function cerrarSesion(mixed $user){
        $tokens = $user?->tokens();

        if ($tokens){
            $tokens->delete();
        }

        return [
            "message"=>"Sesión cerrada"
        ];
    }



}