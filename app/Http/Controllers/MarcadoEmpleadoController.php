<?php

namespace App\Http\Controllers;

use App\Services\MarcadoEmpleadoService;
use Illuminate\Support\Facades\DB;

class MarcadoEmpleadoController extends Controller
{
    public function store(string $codigoUnico)
    {
        DB::beginTransaction();
        $res =  (new MarcadoEmpleadoService)->registrar($codigoUnico);
        DB::commit();

        return $res;
    }
}
