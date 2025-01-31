<?php

namespace App\Http\Requests;

use App\Rules\UniqueAttributeInArray;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmpleadoMasivoRequest extends FormRequest
{
    const NUMERO_DOCUMENTO_OLD_KEY_REQUEST = "numero_de_documento_antiguo";
    const ID_TIPO_DOCUMENTO_KEY_REQUEST = "tipo_de_documento";
    const NUMERO_DOCUMENTO_KEY_REQUEST = "numero_de_documento";
    const A_PATERNO_KEY_REQUEST = "apellido_paterno";
    const A_MATERNO_KEY_REQUEST = "apellido_materno";
    const NOMBRES_KEY_REQUEST = "nombres";
    const DIRECCION_KEY_REQUEST = "direccion";
    const DISTRITO_UBIGEO_KEY_REQUEST = "distrito_ubigeo";
    const PAIS_KEY_REQUEST = "pais";
    const FECHA_NACIMIENTO_KEY_REQUEST = "fecha_de_nacimiento";
    const EMPRESA_KEY_REQUEST = "empresa";
    const CELULAR_KEY_REQUEST = "celular";
    const SEXO_KEY_REQUEST = "sexo";
    const ESTADO_CIVIL_KEY_REQUEST = "estado_civil";
    const PUESTO_KEY_REQUEST = "puesto";
    const TELEFONO_REFERENCIA_KEY_REQUEST = "telefono_de_referencia";
    const NOMBRE_FAMILIAR_KEY_REQUEST = "nombre_de_familiar";
    const FECHA_INGRESO_KEY_REQUEST = "fecha_de_ingreso";
    const CONTRATO_FECHA_INICIO_KEY_REQUEST = "fecha_inicio_de_contrato";
    const CONTRATO_DESCUENTO_KEY_REQUEST = "descuento_de_contrato";
    const CONTRATO_HORARIO_KEY_REQUEST = "horario_de_contrato";
    const CONTRATO_SALARIO_KEY_REQUEST = "salario_de_contrato";

    const NUMERO_DOCUMENTO_OLD_RULE = "nullable|string|max:15|exists:empleados,numero_documento,deleted_at,NULL";
    const NUMERO_DOCUMENTO_RULE = "required|string|max:15|unique:empleados,numero_documento,,,deleted_at,NULL";
    const NUMERO_DOCUMENTO_EDIT_RULE = "required|string|max:15|unique:empleados,numero_documento,*replaceable_id*,numero_documento,deleted_at,NULL";
    const NUMERO_DOCUMENTO_ELIMINAR_RULE = "required|string|max:15|exists:empleados,numero_documento,deleted_at,NULL";

    const ID_TIPO_DOCUMENTO_RULE = "required|string|size:1|in:D,C";
    const A_PATERNO_RULE = "required|string|max:300";
    const A_MATERNO_RULE = "required|string|max:300";
    const NOMBRES_RULE = "required|string|max:300";
    const DIRECCION_RULE = "nullable|string|max:300";
    const DISTRITO_UBIGEO_RULE = "nullable|string|size:6|exists:distrito_ubigeos,id";
    const PAIS_RULE = "nullable|string|size:2|in:PE,EC,VE";
    const FECHA_NACIMIENTO_RULE = "nullable|date";
    const FECHA_INGRESO_RULE = "nullable|date";
    const EMPRESA_RULE = "required|string|max:300|exists:empresas,razon_social,deleted_at,NULL";
    const CELULAR_RULE = "nullable|string|max:15|min:9";
    const SEXO_RULE = "required|string|size:1|in:F,M";
    const ESTADO_CIVIL_RULE = "nullable|string|size:1|in:S,C,D,V";
    const PUESTO_RULE = "nullable|string|max:180";
    const TELEFONO_REFERENCIA_RULE = "nullable|string|max:20|min:9";
    const NOMBRE_FAMILIAR_RULE = "nullable|string|max:180";
    const CONTRATO_FECHA_INICIO_RULE = "nullable|date";
    const CONTRATO_DESCUENTO_RULE = "nullable|numeric|between:0,999999.99";
    const CONTRATO_HORARIO_RULE = "nullable|integer|exists:horarios,id,deleted_at,NULL";
    const CONTRATO_SALARIO_RULE = "nullable|numeric|between:0,999999.99";

    const ID_EMPRESA_KEY_POST_REQUEST = "id_empresa";

    private $invalidRecordsAlta = [];
    private $invalidRecordsBaja = [];
    private $validRecordsAlta = [];
    private $validRecordsBaja = [];
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::hasUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "data_alta" => ['nullable', 'array', new UniqueAttributeInArray(self::NUMERO_DOCUMENTO_OLD_KEY_REQUEST)],
            "data_baja" => ['nullable', 'array', new UniqueAttributeInArray(self::NUMERO_DOCUMENTO_OLD_KEY_REQUEST)],
        ];
    }

    protected function prepareForValidation()
    {
        $dataAlta = $this->input('data_alta', []);

        $validRecordsAlta = [];
        $invalidRecordsAlta = [];

        foreach ($dataAlta as $item) {
            $itemValidator = Validator::make($item, [
                self::NUMERO_DOCUMENTO_OLD_KEY_REQUEST=>self::NUMERO_DOCUMENTO_OLD_RULE,
                self::NUMERO_DOCUMENTO_KEY_REQUEST=>    @$item[self::NUMERO_DOCUMENTO_OLD_KEY_REQUEST] == null
                                                        ? self::NUMERO_DOCUMENTO_RULE
                                                        : str_replace("*replaceable_id*",$item[self::NUMERO_DOCUMENTO_OLD_KEY_REQUEST], self::NUMERO_DOCUMENTO_EDIT_RULE),
                self::A_PATERNO_KEY_REQUEST=>self::A_PATERNO_RULE,
                self::A_MATERNO_KEY_REQUEST=>self::A_MATERNO_RULE,
                self::NOMBRES_KEY_REQUEST=>self::NOMBRES_RULE,
                self::DIRECCION_KEY_REQUEST=>self::DIRECCION_RULE,
                self::DISTRITO_UBIGEO_KEY_REQUEST=>self::DISTRITO_UBIGEO_RULE,
                self::PAIS_KEY_REQUEST=>self::PAIS_RULE,
                self::EMPRESA_KEY_REQUEST=>self::EMPRESA_RULE,
                self::CELULAR_KEY_REQUEST=>self::CELULAR_RULE,
                self::PUESTO_KEY_REQUEST=>self::PUESTO_RULE,
                self::TELEFONO_REFERENCIA_KEY_REQUEST=>self::TELEFONO_REFERENCIA_RULE,
                self::NOMBRE_FAMILIAR_KEY_REQUEST=>self::NOMBRE_FAMILIAR_RULE,
                self::FECHA_NACIMIENTO_KEY_REQUEST=>self::FECHA_NACIMIENTO_RULE,
                self::FECHA_INGRESO_KEY_REQUEST=>self::FECHA_INGRESO_RULE,
                self::SEXO_KEY_REQUEST=>self::SEXO_RULE,
                self::ESTADO_CIVIL_KEY_REQUEST=>self::ESTADO_CIVIL_RULE,
                self::CONTRATO_FECHA_INICIO_KEY_REQUEST=>self::CONTRATO_FECHA_INICIO_RULE,
                self::CONTRATO_DESCUENTO_KEY_REQUEST=>self::CONTRATO_DESCUENTO_RULE,
                self::CONTRATO_HORARIO_KEY_REQUEST=>self::CONTRATO_HORARIO_RULE,
                self::CONTRATO_SALARIO_KEY_REQUEST=>self::CONTRATO_SALARIO_RULE
            ]);

            if ($itemValidator->passes()) {
                $empresa = DB::table("empresas")
                                    ->whereNull("deleted_at")
                                    ->where(["razon_social"=>$item[self::EMPRESA_KEY_REQUEST]])
                                    ->first(["id"]);

                $item[self::ID_EMPRESA_KEY_POST_REQUEST]= $empresa->id;
                $validRecordsAlta[] = $item;
            } else {
                $invalidRecordsAlta[] = [
                    'record' => $item,
                    'errors' => $itemValidator->errors()->toArray(),
                ];
            }
        }

        // Attach valid and invalid records to the request for controller access
        $this->validRecordsAlta = $this->mapArrayRequestedAlta($validRecordsAlta);
        $this->invalidRecordsAlta = $invalidRecordsAlta;

        $dataBaja = $this->input('data_baja', []);

        $validRecordsBaja = [];
        $invalidRecordsBaja = [];

        foreach ($dataBaja as $item) {
            $itemValidator = Validator::make($item, [
                self::NUMERO_DOCUMENTO_OLD_KEY_REQUEST=>self::NUMERO_DOCUMENTO_OLD_RULE,
            ]);

            if ($itemValidator->passes()) {
                $validRecordsBaja[] = $item;
            } else {
                $invalidRecordsBaja[] = [
                    'record' => $item,
                    'errors' => $itemValidator->errors()->toArray(),
                ];
            }
        }

        // Attach valid and invalid records to the request for controller access
        $this->validRecordsBaja = $this->mapArrayRequestedBaja($validRecordsBaja);
        $this->invalidRecordsBaja = $invalidRecordsBaja;

        // Skip default behavior (no 422 response)
    }

    /**
     * Get valid records.
     */
    public function getValidRecordsAlta()
    {
        return $this->validRecordsAlta ?? [];
    }

    /**
     * Get invalid records.
     */
    public function getInvalidRecordsAlta()
    {
        return $this->invalidRecordsAlta ?? [];
    }

    /**
     * Get valid records.
     */
    public function getValidRecordsBaja()
    {
        return $this->validRecordsBaja ?? [];
    }

    /**
     * Get invalid records.
     */
    public function getInvalidRecordsBaja()
    {
        return $this->invalidRecordsBaja ?? [];
    }

     /**
     * Map the incoming request data to the desired attributes.
     *
     * @return mixed
     */
    private function mapArrayRequestedAlta(array $dataRequested) : mixed{
        return array_map(function ($item) {
            return [
                'id_numero_documento' => @$item[self::NUMERO_DOCUMENTO_OLD_KEY_REQUEST],
                'id_tipo_documento' => $item[self::ID_TIPO_DOCUMENTO_KEY_REQUEST],
                'numero_documento' => $item[self::NUMERO_DOCUMENTO_KEY_REQUEST],
                'apellido_paterno' => $item[self::A_PATERNO_KEY_REQUEST],
                'apellido_materno' => $item[self::A_MATERNO_KEY_REQUEST],
                'nombres' => $item[self::NOMBRES_KEY_REQUEST],
                'direccion' => @$item[self::DIRECCION_KEY_REQUEST],
                'distrito_ubigeo' => @$item[self::DISTRITO_UBIGEO_KEY_REQUEST],
                'pais' => @$item[self::PAIS_KEY_REQUEST],
                'id_empresa' => @$item[self::ID_EMPRESA_KEY_POST_REQUEST],
                'celular' => @$item[self::CELULAR_KEY_REQUEST],
                'puesto' => @$item[self::PUESTO_KEY_REQUEST],
                'telefono_referencia' => @$item[self::TELEFONO_REFERENCIA_KEY_REQUEST],
                'nombre_familiar' => @$item[self::NOMBRE_FAMILIAR_KEY_REQUEST],
                'fecha_ingreso' => @$item[self::FECHA_INGRESO_KEY_REQUEST],
                'fecha_nacimiento' => @$item[self::FECHA_NACIMIENTO_KEY_REQUEST],
                'sexo' => $item[self::SEXO_KEY_REQUEST],
                'estado_civil' => $item[self::ESTADO_CIVIL_KEY_REQUEST],
                'contrato_fecha_inicio' => @$item[self::CONTRATO_FECHA_INICIO_KEY_REQUEST],
                'contrato_descuento' => @$item[self::CONTRATO_DESCUENTO_KEY_REQUEST],
                'contrato_horario' => @$item[self::CONTRATO_HORARIO_KEY_REQUEST],
                'contrato_salario' => @$item[self::CONTRATO_SALARIO_KEY_REQUEST]
            ];
        }, $dataRequested);
    }

    private function mapArrayRequestedBaja(array $dataRequested) : mixed{
        return array_map(function ($item) {
            return [
                'id_numero_documento' => @$item[self::NUMERO_DOCUMENTO_OLD_KEY_REQUEST] ?? null,
            ];
        }, $dataRequested);
    }

}
