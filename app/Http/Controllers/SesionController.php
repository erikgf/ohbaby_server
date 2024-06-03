<?php

namespace App\Http\Controllers;

use App\Http\Requests\SesionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class SesionController extends Controller
{
    public function iniciarSesion(SesionRequest $request){
        $data = $request->validated();

        $user = User::where("username", $data["username"])->first();
        if (!$user){
            throw new \Exception("Usuario no existe.", Response::HTTP_UNAUTHORIZED);
        }

        if (!Hash::check($data["password"], $user->password)){
            throw new \Exception("Contraseña incorrecta.", Response::HTTP_UNAUTHORIZED);
        }

        if ($user->estado_acceso === "I"){
            throw new \Exception("Usuario inactivo.", Response::HTTP_UNAUTHORIZED);
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

    public function cerrarSesion(Request $request){
        $tokens = $request->user()?->tokens();

        if ($tokens){
            $tokens->delete();
        }

        return [
            "message"=>"Sesión cerrada"
        ];
    }

}
