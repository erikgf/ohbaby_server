<?php

namespace App\Http\Controllers;

use App\DTO\UsuarioDTO;
use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use App\Services\UsuarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{

    public function index()
    {
        return (new UsuarioService)->listar();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UsuarioRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        $usuarioDTO = new UsuarioDTO();
        $usuarioDTO->id_rol =  $data["id_rol"];
        $usuarioDTO->name =  $data["name"];
        $usuarioDTO->username = $data["username"];
        $usuarioDTO->password = Hash::make($data["password"]);
        $usuarioDTO->estado_acceso = $data["estado_acceso"];

        $usuario = (new UsuarioService)->registrar($usuarioDTO);

        DB::commit();

        return $usuario;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return (new UsuarioService)->leer($id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(UsuarioRequest $request, int $id)
    {
        $data = $request->validated();

        DB::beginTransaction();

        $usuarioDTO = new UsuarioDTO();
        $usuarioDTO->id_rol =  $data["id_rol"];
        $usuarioDTO->name =  $data["name"];
        $usuarioDTO->estado_acceso = $data["estado_acceso"];

        $usuario = (new UsuarioService)->editar($usuarioDTO, $id);

        DB::commit();

        return $usuario;
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();
        (new UsuarioService)->anular($id);
        DB::commit();
        return $id;
    }

    public function cambiarClave(Request $request, int $idUsuario)
    {
        $data = $request->validate([
            "clave"=>"required|string|max:32"
        ]);

        $user = User::findOrFail($idUsuario);
        $user = (new UsuarioService)->cambiarClave($user, $data["clave"]);
        return $user;
    }
}
