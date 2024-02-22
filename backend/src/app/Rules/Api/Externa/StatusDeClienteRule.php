<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class StatusDeClienteRule
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
                "max:60"
            ],
            "status" => [
                "Required",
                "int",
                Rule::in(0, 1)
            ],
            "bloqueia" => [
                "Required",
                "int",
                Rule::in(0, 1)
            ]

        ];
    }
}
