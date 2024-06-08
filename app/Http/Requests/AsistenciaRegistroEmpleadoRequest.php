<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules(): array
    {
        return [
            "codigo_unico"=>"required|string|size:3",
            "fecha"=>"required|date",
            "hora_entrada_mañana"=>"required|string|size:5",
            "hora_salida_mañana"=>"required|string|size:5",
            "hora_entrada_tarde"=>"required|string|size:5",
            "hora_salida_tarde"=>"required|string|size:5",
        ];
    }
}
