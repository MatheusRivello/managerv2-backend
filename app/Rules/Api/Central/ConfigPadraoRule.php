<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class ConfigPadraoRule
{

    public function rules($id, $connection = null)
    {
        return  [
            "tipo_configuracao" => [
                'required',
                'integer',
                "exists:tipo_configuracao,id"
            ],
            "valor" => [
                'required',
                'string'
            ]
        ];
    }
}
