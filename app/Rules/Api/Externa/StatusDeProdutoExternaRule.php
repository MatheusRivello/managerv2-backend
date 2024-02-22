<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class StatusDeProdutoExternaRule
{
    public function rules()
    {
        return [
            "idRetaguarda" => [
                "Required",
                "string",
                "max:15"
            ],
            "descricao" => [
                "Required",
                "string",
                "max:100"
            ],
            "cor" => [
                "Nullable",
                "string",
                "max:50"
            ],
            "status" => [
                "Required",
                "int",
                Rule::in(0,1)
            ]

        ];
    }
}
