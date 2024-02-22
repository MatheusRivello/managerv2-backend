<?php

namespace App\Rules\Api\Tenant\Config;

use Illuminate\Validation\Rule;

class ConfigPedWebTenantRule
{

    public function rules($id, $connection = null)
    {
        return [
            "descricao" => [
                'required',
                'string',
                'min:5',
                'max:30'
            ],
            "valor" => [
                'required',
                'string',
                'max:100'
            ],
            "tipo" => [
                'required',
                'int',
                Rule::in([0, 1, 2])
            ],
            "label" => [
                'required',
                'string',
                'min:5',
                'max:100'
            ],
            "valor_padrao" => [
                'nullable',
                'string',
                'max:200'
            ],
            "campo" => [
                'required',
                'string',
                'max:45',
                Rule::in(['select', 'input', 'checkbox', 'radio'])
            ],
            "tamanho_maximo" => [
                'string',
                'nullable',
                'max:20'
            ],
            "tabela_bd" => [
                'nullable',
                'string',
                'max:60'
            ],
            "info_tabela" => [
                'nullable',
                'string',
                'max:65535'
            ],
        ];
    }
}
