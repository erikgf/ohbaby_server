<?php

use App\Http\Controllers\AsistenciaRegistroEmpleadoController;
use App\Http\Controllers\DepartamentoUbigeoController;
use App\Http\Controllers\DistritoUbigeoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\HorarioEmpleadoContratoController;
use App\Http\Controllers\MarcadoEmpleadoController;
use App\Http\Controllers\ProvinciaUbigeoController;
use App\Http\Controllers\SesionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post("sesion/iniciar", [SesionController::class, "iniciarSesion"]);

Route::group(["middleware"=>['auth:sanctum']], function(){
    Route::post("sesion/cerrar", [SesionController::class, "cerrarSesion"]);

    Route::apiResource("empleados", EmpleadoController::class);
    Route::apiResource("horarios", HorarioController::class);

    Route::get("ubigeo-departamentos", [DepartamentoUbigeoController::class, "index"]);
    Route::get("ubigeo-provincias/{idDepartamento}", [ProvinciaUbigeoController::class, "index"]);
    Route::get("ubigeo-distritos/{idProvincia}", [DistritoUbigeoController::class, "index"]);

    Route::get("horarios-empleados-contrato", [HorarioEmpleadoContratoController::class, "index"]);
    Route::get("horarios-empleados-contrato-libres", [HorarioEmpleadoContratoController::class, "indexLibres"]);
    Route::get("horario-empleados-contrato/{idHorario}", [HorarioEmpleadoContratoController::class, "show"]);
    Route::post("horarios-empleados-contrato/{idHorario}", [HorarioEmpleadoContratoController::class, "store"]);

    Route::post("/empleados/finalizar-contrato/{idEmpleadoContrato}", [EmpleadoController::class, "finalizarContrato"]);
    Route::post("/asistencia/marcar/{codigoUnico}", [MarcadoEmpleadoController::class, "store"]);

    Route::post("/asistencia-registro-empleado", [AsistenciaRegistroEmpleadoController::class, "store"]);
    Route::get("/asistencia-registro-empleado", [AsistenciaRegistroEmpleadoController::class, "consultar"]);
    Route::get("/asistencia-registro-empleado/{fecha}", [AsistenciaRegistroEmpleadoController::class, "getDataControlSeguridad"]);

});
