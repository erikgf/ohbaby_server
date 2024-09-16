<?php

namespace App\Http\Controllers;

use App\Services\EmpleadoContratoService;
use Illuminate\Http\Request;

class EmpleadoContratoController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            "searchTerm" => "nullable|string|max:300"
        ]);

        if (isset($data["searchTerm"])){
            return (new EmpleadoContratoService)->buscarTerm($data["searchTerm"]);
        }

       // return (new EmpleadoContratoService)->listar();
    }
}
