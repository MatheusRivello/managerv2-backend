<?php

namespace App\Rules\Api\Externa;

class CidadeExternaRule
{

    public function rules()
    {

        return [
            "id" => [
                "required",
                "int"
            ],
            "uf" => [
                "Required",
                "string",
                "max:2"
            ],
            "descricao" => [
                "string",
                "max:100",
                "nullable"
            ],
            "codigoIbge" => [
                "string",
                "max:45",
                "nullable"
            ],

            "DDD" => [
                "string",
                "max:3",
                "nullable"
            ]
        ];
    }
}
