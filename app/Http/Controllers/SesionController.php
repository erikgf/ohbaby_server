<?php

namespace App\Http\Controllers;

use App\Http\Requests\SesionRequest;
use App\Services\SesionService;

class SesionController extends Controller
{
    private SesionService $sesionService;

    public function __construct(){
        $this->sesionService = new SesionService;
    }

    public function iniciarSesion(SesionRequest $request){
        $data = $request->validated();
        return $this->sesionService->iniciarSesion(username: $data["username"], password: $data["password"]);
    }

    public function cerrarSesion(){
        return $this->sesionService->cerrarSesion(request()?->user());
    }

}
