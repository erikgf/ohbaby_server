<?php

namespace App\Http\Controllers;

use App\Models\ProvinciaUbigeo;
use Illuminate\Http\Request;

class ProvinciaUbigeoController extends Controller
{
    public function index(string $idDepartamento)
    {
        return ProvinciaUbigeo::where(["id_departamento_ubigeo"=>$idDepartamento])->get(["id", "name"]);
    }
}
