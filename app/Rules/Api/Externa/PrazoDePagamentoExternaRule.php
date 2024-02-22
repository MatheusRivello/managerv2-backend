<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class PrazoDePagamentoExternaRule
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
            "variacao" => [
                "Required",
                "numeric"
            ],
            "valorMin" => [
                "Nullable",
                "Numeric"
            ],
            "status" => [
                "Required",
                "int",
                Rule::in(0, 1)
            ]

        ];
    }
}
