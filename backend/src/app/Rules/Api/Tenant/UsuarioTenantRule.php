<?php

namespace App\Rules\Api\Tenant;

use Illuminate\Validation\Rule;

class UsuarioTenantRule
{

    public function rules($id, $connection = null)
    {
        return [
            "password" => [
                'string',
                'max:30',
                "confirmed"
            ],
            "token" => [
                'string',
                'size:16',
                "nullable"
            ]
        ];
    }
}
