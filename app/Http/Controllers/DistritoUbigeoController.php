<?php

namespace App\Http\Controllers;

use App\Models\DistritoUbigeo;

class DistritoUbigeoController extends Controller
{
    public function index(string $idProvincia)
    {
        return DistritoUbigeo::where(["id_provincia_ubigeo"=>$idProvincia])->get(["id", "name"]);
    }
}
