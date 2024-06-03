<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HorarioRequest extends FormRequest
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
            "descripcion"=>"required|string|max:300",
            "horario_detalles"=>"required|array",
            "horario_detalles.*.id"=>"nullable|integer",
            "horario_detalles.*.hora_inicio"=>"required|date_format:H:i",
            "horario_detalles.*.hora_fin"=>"required|date_format:H:i",
            "horario_detalles.*.dias"=>"required|string|max:30",
        ];
    }
}
