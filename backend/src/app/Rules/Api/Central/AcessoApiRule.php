<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class AcessoApiRule
{

    public function rules($id, $connection = null)
    {
        return [
            "prefix" => [
                'required',
                Rule::in(["local", "geral", "view"])
            ],
            "url" => [
                'required',
                'string',
                'max:255',
                'unique:api,url' . ", $id",
            ],
            "descricao" => [
                'required',
                'string',
                'max:255'
            ]
        ];
    }
}
