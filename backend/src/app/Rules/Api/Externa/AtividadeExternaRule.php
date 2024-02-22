<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class AtividadeExternaRule
{
    public function rules()
    {
        return [
            "idFilial" => [
                "Required",
                "int",
                "required"
            ],
            "idRetaguarda" => [
                "Required",
                "string",
                "max:15",
            ],
            "descricao" => [
                "Required",
                "string",
                "max:60"
            ],
            "status" => [
                "Required",
                "int",
                Rule::in(0, 1)
            ]

        ];
    }
}
