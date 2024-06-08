<?php

use App\Services\AsistenciaRegistroEmpleadoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/pdf/control-asistencia/{fecha}', function (string $fecha) {
    $data = (new AsistenciaRegistroEmpleadoService)->getDataControlSeguridad($fecha);
    $pdf = Pdf::loadView('control_asistencia', $data);
    return $pdf->download('control.asistencia.pdf');
});

Route::get('/', function () {
    return view('welcome');
});
