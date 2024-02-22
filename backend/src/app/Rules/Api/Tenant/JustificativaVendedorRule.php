<?php

namespace App\Rules\Api\Tenant;

class JustificativaVendedorRule
{
    public function rules()
    {
        return [
            'idEmpresa' => [
                'Nullable',
                'int',
                'exists:empresa,id'
            ],
            'descricao' => [
                'Required',
                'string',
                'max:255'
            ]
        ];
    }
}
