<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AsistenciaRegistroEmpleadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            "fecha"=>"required|date",
            "asistencias"=>"required|array",
            "asistencias.*.id"=>"required|integer",
            "asistencias.*.turno_uno_entrada"=>"required|date_format:H:i",
            "asistencias.*.turno_uno_salida"=>"required|date_format:H:i",
            "asistencias.*.turno_dos_entrada"=>"nullable|date_format:H:i",
            "asistencias.*.turno_dos_entrada"=>"nullable|date_format:H:i",
            "asistencias.*.falto"=>"required|integer"
        ];
    }
}
