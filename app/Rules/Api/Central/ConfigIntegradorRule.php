<?php

namespace App\Rules\Api\Central;

class ConfigIntegradorRule
{

    public function rules($id, $connection = null)
    {
        return [
            "id" => [
                'required',
                'integer'
            ],
            "name" => [
                'nullable',
                'string',
            ],
            "fkEmpresa" => [
                'nullable',
                'exists:empresa,id'
            ],
            "value" => [
                'nullable',
                'string'
            ]
        ];
    }
}
