<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
        $id = $this->route("usuario");
        $editando = $id != "";

        if ($editando){
            return [
                "name"=>"required|string|max:350",
                "id_rol"=>"required|integer",
                "estado_acceso"=>"nullable|string|size:1"
            ];
        }

        return [
            "name"=>"required|string|max:350",
            "id_rol"=>"required|integer",
            "username"=>"required|string|max:50|unique:users,username",
            "password"=>"required|string|max:50",
            "estado_acceso"=>"required|string|size:1"
        ];
    }
}
