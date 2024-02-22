<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class FornecedorExternaRule
{
    public function rules()
    {
        return [
            "idFilial" => [
                "Required",
                "int"
            ],
            "idRetaguarda" => [
                "Required",
                "string",
                "max:15"
            ],
            "razaoSocial" => [
                "Nullable",
                "string",
                "max:60"
            ],
            "nomeFantasia" => [
                "Nullable",
                "string",
                "max:60"
            ],
            "status" => [
                "Required",
                Rule::in([0, 1])
            ]
        ];
    }
}
