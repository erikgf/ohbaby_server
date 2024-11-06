<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntregaRequest extends FormRequest
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
            "id_tipo_entrega"=>"required|integer|exists:tipo_entregas,id",
            "id_empleado_contrato"=>"required|integer|exists:empleado_contratos,id",
            "cuotas"=>"required|array",
            "cuotas.*.monto_cuota"=>"required|numeric|between:0,999999.99",
            "cuotas.*.fecha_cuota"=>"required|date",
            "cuotas.*.motivo_registro"=>"nullable|string|max:300",
        ];
    }
}
