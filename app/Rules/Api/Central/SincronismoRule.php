<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class SincronismoRule
{

    public function rules($id, $connection = null)
    {

        return [
            "valor" => [
                'required',
                'boolean'
            ],
            "tipo" => [
                'required',
                'integer',
                Rule::in([0, 1])
            ],
            "configuracao_empresa" => [
                'integer',
                "exists:tipo_configuracao_empresa,id"
            ]
        ];
    }
}
