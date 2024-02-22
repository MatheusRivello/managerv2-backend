<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class VersaoRule
{

    public function rules($id, $connection = null)
    {
        return [
            "versao" => [
                'string',
                Rule::unique('versao_app', 'versao')->ignore($id, "codigo_versao")
            ],
            "obrigatorio" => [
                'boolean'
            ],
            "observacao" => [
                'string',
                "nullable",
                "max:5000"
            ]
        ];
    }
}
