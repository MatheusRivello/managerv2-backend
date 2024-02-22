<?php

namespace App\Rules\Api\Tenant\Config;

use Illuminate\Validation\Rule;

class ConfigFilialTenantRule
{
    public function rules($id, $connection = null)
    {
        return [
            "descricao" => [
                'required',
                'string',
                'max:100',
                "unique:$connection.configuracao_filial,descricao" . ", $id",
            ],
            "valor" => [
                'required',
                'string',
                'max:65535'
            ],
            "tipo" => [
                'required',
                'string',
                'max:10',
                Rule::in(['string', 'boolean', 'int', 'float'])
            ],
        ];
    }
}
