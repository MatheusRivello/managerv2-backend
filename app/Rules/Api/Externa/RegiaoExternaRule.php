<?php

namespace App\Rules\Api\Externa;

class RegiaoExternaRule
{

    public function rules($id, $connection = null)
    {

        return [
            "id"=>[
                "Required",
                "int"
            ],
            "descricao" => [
                'required',
                'string',
                'max:30'
            ]
        ];
    }
}
