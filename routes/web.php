<?php

use App\Http\Resources\EntregaListaResource;
use App\Services\AsistenciaRegistroEmpleadoService;
use App\Services\EntregaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

Route::get('/pdf/control-asistencia/{fecha}', function (Request $request, string $fecha) {
    $data = $request->validate([
        "key"=>"nullable|string"
    ]);

    $fileName = "control.asistencia.pdf";
    $tipo = "";
    if (isset($data["key"])){
        if ($data["key"] == "varios"){
            $tipo = "varios";
            $fileName = "control.asistencia.varios.pdf";
        } else {
            $tipo = "no-varios";
            $fileName = "control.asistencia.novarios.pdf";
        }
    }

    $data = (new AsistenciaRegistroEmpleadoService)->getDataControlSeguridad($fecha, tipo: $tipo);
    $pdf = Pdf::loadView('control_asistencia', $data);
    return $pdf->download($fileName);
});

Route::get('/test', function () {
    $r = [];
    $fecha = "2024-05-05";
    $fecha1 = Carbon::parse($fecha)->addMonthsNoOverflow(1)->startOfMonth()->format("Y-m-d");
    $fecha2 = Carbon::parse($fecha1)->addMonthsNoOverflow(1)->startOfMonth()->format("Y-m-d");
    $fecha3 = Carbon::parse($fecha2)->addMonthsNoOverflow(1)->startOfMonth()->format("Y-m-d");

    $r = [
        $fecha, $fecha1, $fecha2, $fecha3
    ];

    return $r;
});


Route::get('/', function () {
    EntregaListaResource::collection((new EntregaService)->listar([]));
    return view('welcome');
});
