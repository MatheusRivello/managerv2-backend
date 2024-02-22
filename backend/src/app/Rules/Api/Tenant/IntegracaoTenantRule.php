<?php

namespace App\Rules\Api\Tenant;

use Illuminate\Validation\Rule;

class IntegracaoTenantRule
{
    public function rules($connection)
    {
        return [
            "integrador" => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4, 5, 6])
            ],
            "tipo" => [
                'required',
                'integer',
                Rule::in([1, 2])
            ],
            "filial" => [
                'required',
                'integer',
                "exists:{$connection}.filial,id"
            ],
            "filtro" => [
                'required',
                'integer',
                Rule::in([1, 2])
            ],
            "id_interno" => [
                'required'
            ],
            "id_externo" => [
                'string',
                "nullable",
                'max:30',
            ],
            "campo_extra_1" => [
                'string',
                "nullable",
                'max:45',
            ],
            "campo_extra_2" => [
                'string',
                "nullable",
                'max:45',
            ],
            "campo_extra_3" => [
                'string',
                "nullable",
                'max:45',
            ],
            "ultimo_status" => [
                'string',
                "nullable",
                'max:45',
            ],
        ];
    }
}
