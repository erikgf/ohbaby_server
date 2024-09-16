<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpleadoRequest extends FormRequest
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
            "id_tipo_documento"=>"required|string|size:1",
            "codigo_unico"=>"required|string|max:3",
            "numero_documento"=>"required|string|max:15",
            "apellido_paterno"=>"required|string|max:300",
            "apellido_materno"=>"required|string|max:300",
            "fecha_nacimiento"=>"nullable|date|max:300",
            "nombres"=>"required|string|max:300",
            "direccion"=>"nullable|string|max:300",
            "distrito_ubigeo"=>"nullable|string|size:6",
            "pais"=>"nullable|string|size:2",
            "contratos"=>"nullable|array",
            "contratos.*.id"=>"nullable|integer",
            "contratos.*.fecha_inicio"=>"required|date",
            "contratos.*.salario"=>"required|numeric|min:0",
            "contratos.*.dias_trabajo"=>"required|numeric|min:1",
            "contratos.*.horas_dia"=>"required|numeric|min:1",
            "id_empresa"=>"required|integer",
            "numero_orden"=>"required|integer",
            "celular"=>"nullable|string|max:15|unique:empleados,celular,except,id",
            "telefono_referencia"=>"nullable|string|max:15",
            "nombre_familiar"=>"nullable|string|max:200",
            "puesto"=>"required|string|max:200",
            "sexo"=>"required|string|size:1|in:M,F",
            "estado_civil"=>"required|string|size:1|in:S,C,V,D",
        ];
    }
}
