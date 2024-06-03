<?php

namespace App\Http\Controllers;

use App\Models\DepartamentoUbigeo;
use Illuminate\Http\Request;

class DepartamentoUbigeoController extends Controller
{
    public function index()
    {
        return DepartamentoUbigeo::all();
    }
}
