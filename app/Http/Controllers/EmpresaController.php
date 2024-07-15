<?php

namespace App\Http\Controllers;

use App\Services\EmpresaService;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        return (new EmpresaService)->listarBean();
    }
}
