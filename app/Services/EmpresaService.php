<?php
namespace App\Services;

use App\Models\Empresa;

class EmpresaService{

    public function listarBean(){
        $empresas = Empresa::query()->get(["id","razon_social as label"]);
        return $empresas;
    }

}
