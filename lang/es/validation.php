<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'El campo :attribute debe ser aceptado.',
    'accepted_if' => 'El campo campo :attribute debe ser aceptado cuando el campo :other es :value.',
    'active_url' => 'El campo :attribute debe ser una URL válida.',
    'after' => 'El campo :attribute debe tener una fecha después de :date.',
    'after_or_equal' => 'El campo :attribute debe ser una fecha mayor o igual a :date.',
    'alpha' => 'El campo :attribute debe solo tener letras.',
    'alpha_dash' => 'El campo :attribute debe solo tener letras, números, guiones y subguiones.',
    'alpha_num' => 'El campo :attribute debe solo tener letras y números.',
    'array' => 'El campo :attribute debe solo un arreglo.',
    'ascii' => 'El campo :attribute debe solo contener caracteres que sean byte únicos alfanumérico y símbolos (ASCII).',
    'before' => 'El campo :attribute debe tener una fecha antes de :date.',
    'before_or_equal' => 'El campo :attribute debe tener una fecha antes o igual a :date.',
    'between' => [
        'array' => 'El campo :attribute debe tener entre :min y :max ítems.',
        'file' => 'El campo :attribute debe pesar entre :min y :max kilobytes.',
        'numeric' => 'El campo :attribute debe estar entre :min and :max.',
        'string' => 'El campo :attribute debe tener entrega :min and :max caracteres.',
    ],
    'boolean' => 'El campo :attribute debe es verdadero o falso.',
    'can' => 'El campo :attribute debe contener un valor autorizado.',
    'confirmed' => 'El campo :attribute de confirmado no es válido.',
    'current_password' => 'La clave es incorrecta.',
    'date' => 'El campo :attribute debe ser una fecha válida.',
    'date_equals' => 'El campo :attribute debe ser una fecha igual a :date.',
    'date_format' => 'El campo :attribute debe coincidir con el formato de :format.',
    'decimal' => 'El campo :attribute debe de tener :decimal decimales.',
    'declined' => 'El campo :attribute debe estar rechazado.',
    'declined_if' => 'El campo :attribute debe estar rechazado cuando :other es el :value.',
    'different' => 'El campo :attribute y :other debe ser diferente.',
    'digits' => 'El campo :attribute debe tener :digits dígitos.',
    'digits_between' => 'El campo :attribute debe tener entre :min y :max dígitos.',
    'dimensions' => 'El campo :attribute de imagen tiene dimensiones inválidos.',
    'distinct' => 'El campo :attribute tiene un valor duplicado.',
    'doesnt_end_with' => 'El campo :attribute no debe terminar con uno los siguientes valores: :values.',
    'doesnt_start_with' => 'El campo :attribute debe iniciar con uno los siguientes valores :values.',
    'email' => 'El campo :attribute debe ser una dirección válida.',
    'ends_with' => 'El campo :attribute debe must end with one of the following: :values.',
    'enum' => 'El valor ingresado en :attribute es inválido.',
    'exists' => 'El valor ingresado en :attribute no existe.',
    'extensions' => 'El campo :attribute debe tener una de las siguientes extensiones: :values.',
    'file' => 'El campo :attribute debe ser un archivo.',
    'filled' => 'El campo :attribute debe tener un valor.',
    'gt' => [
        'array' => 'El campo :attribute debe tener más de :value ítems.',
        'file' => 'El campo :attribute debe pesar más de :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser mayor a :value.',
        'string' => 'El campo :attribute debe tener más de :value caracteres.',
    ],
    'gte' => [
        'array' => 'El campo :attribute debe tener :value ítems o más.',
        'file' => 'El campo :attribute debe pesar más de :value kilobytes o más.',
        'numeric' => 'El campo :attribute debe ser mayor igual a :value.',
        'string' => 'El campo :attribute debe tener :value caracteres o más.',
    ],
    'hex_color' => 'El campo :attribute debe ser color hexagesimal válido.',
    'image' => 'El campo :attribute debe ser una imagen.',
    'in' => 'El valor ingresado en :attribute es inválido.',
    'in_array' => 'El valor :attribute debe estar en :other.',
    'integer' => 'El valor :attribute debe ser un entero.',
    'ip' => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4' => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6' => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json' => 'El campo :attribute deber ser una cadena JSON válida.',
    'lowercase' => 'El campo :attribute debe estar minúsculas.',
    'lt' => [
        'array' => 'El campo :attribute debe must have less than :value items.',
        'file' => 'El campo :attribute debe must be less than :value kilobytes.',
        'numeric' => 'El campo :attribute debe must be less than :value.',
        'string' => 'El campo :attribute debe must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'El campo :attribute debe must not have more than :value items.',
        'file' => 'El campo :attribute debe must be less than or equal to :value kilobytes.',
        'numeric' => 'El campo :attribute debe must be less than or equal to :value.',
        'string' => 'El campo :attribute debe must be less than or equal to :value characters.',
    ],
    'mac_address' => 'El campo :attribute debe must be a valid MAC address.',
    'max' => [
        'array' => 'El campo :attribute debe must not have more than :max items.',
        'file' => 'El campo :attribute debe must not be greater than :max kilobytes.',
        'numeric' => 'El campo :attribute debe must not be greater than :max.',
        'string' => 'El campo :attribute debe must not be greater than :max characters.',
    ],
    'max_digits' => 'El campo :attribute debe must not have more than :max digits.',
    'mimes' => 'El campo :attribute debe must be a file of type: :values.',
    'mimetypes' => 'El campo :attribute debe must be a file of type: :values.',
    'min' => [
        'array' => 'El campo :attribute debe must have at least :min items.',
        'file' => 'El campo :attribute debe must be at least :min kilobytes.',
        'numeric' => 'El campo :attribute debe must be at least :min.',
        'string' => 'El campo :attribute debe must be at least :min characters.',
    ],
    'min_digits' => 'El campo :attribute debe must have at least :min digits.',
    'missing' => 'El campo :attribute debe must be missing.',
    'missing_if' => 'El campo :attribute debe must be missing when :other is :value.',
    'missing_unless' => 'El campo :attribute debe must be missing unless :other is :value.',
    'missing_with' => 'El campo :attribute debe must be missing when :values is present.',
    'missing_with_all' => 'El campo :attribute debe must be missing when :values are present.',
    'multiple_of' => 'El campo :attribute deber ser múltiplo de :value.',
    'not_in' => 'Lo ingresado en el campo :attribute es inválido.',
    'not_regex' => 'El formato del campo :attribute es inválido.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'password' => [
        'letters' => 'El campo :attribute debe contener al menos una letra.',
        'mixed' => 'El campo :attribute debe contener al menos una letra en mayúscula y una en minúscula.',
        'numbers' => 'El campo :attribute debe contener al menos un número.',
        'symbols' => 'El campo :attribute debe contener al menos un símbolo.',
        'uncompromised' => 'El valor ingresado en :attribute ha aparecido en un robo de información. Ingrese un valor para :attribute distinto.',
    ],
    'present' => 'El campo :attribute debe estar presente.',
    'present_if' => 'El campo :attribute debe estar presente cuando el valor de :other es :value.',
    'present_unless' => 'El campo :attribute debe estar presente a menos que el valor de :other es :value.',
    'present_with' => 'El campo :attribute debe must be present when :values is present.',
    'present_with_all' => 'El campo :attribute debe must be present when :values are present.',
    'prohibited' => 'El campo :attribute está prohibido.',
    'prohibited_if' => 'El campo :attribute está prohibido cuando el campo :other es :value.',
    'prohibited_unless' => 'El campo :attribute está prohibido a menos que en el campo :other tenga los valores :values.',
    'prohibits' => 'El campo :attribute prohíbe al campo :other de estar presente.',
    'regex' => 'El formato del campo :attribute es inválido.',
    'required' => 'El campo :attribute es obligatorio.',
    'required_array_keys' => 'El campo :attribute debe must contain entries for: :values.',
    'required_if' => 'El campo :attribute debe is required when :other is :value.',
    'required_if_accepted' => 'El campo :attribute debe is required when :other is accepted.',
    'required_unless' => 'El campo :attribute debe is required unless :other is in :values.',
    'required_with' => 'El campo :attribute debe is required when :values is present.',
    'required_with_all' => 'El campo :attribute debe is required when :values are present.',
    'required_without' => 'El campo :attribute debe is required when :values is not present.',
    'required_without_all' => 'El campo :attribute debe is required when none of :values are present.',
    'same' => 'El campo :attribute debe must match :other.',
    'size' => [
        'array' => 'El campo :attribute debe must contain :size items.',
        'file' => 'El campo :attribute debe must be :size kilobytes.',
        'numeric' => 'El campo :attribute debe must be :size.',
        'string' => 'El campo :attribute debe must be :size characters.',
    ],
    'starts_with' => 'El campo :attribute debe must start with one of the following: :values.',
    'string' => 'El campo :attribute debe must be a string.',
    'timezone' => 'El campo :attribute debe must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'uppercase' => 'El campo :attribute debe must be uppercase.',
    'url' => 'El campo :attribute debe must be a valid URL.',
    'ulid' => 'El campo :attribute debe must be a valid ULID.',
    'uuid' => 'El campo :attribute debe must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
