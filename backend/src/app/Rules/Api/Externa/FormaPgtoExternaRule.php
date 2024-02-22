<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class FormaPgtoExternaRule
{
    public function rules()
    {
        return [
            "idRetaguarda" => [
                "required",
                "string",
                "max:15"
            ],
            "descricao" => [
                "string",
                "required",
                "max:100"
            ],
            "valorMin" => [
                "nullable",
                "numeric"
            ],
            "situacao" => [
                "nullable",
                "int"
            ],
            "status" => [
                "int",
                Rule::in([0, 1])
            ]

        ];
    }
}
