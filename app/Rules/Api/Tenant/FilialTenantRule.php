<?php

namespace App\Rules\Api\Tenant;

class FilialTenantRule
{
    public function rules($id, $connection = null)
    {
        return [
            "emp_cgc" => [
                'required',
                'string',
                'max:18',
            ],
            "emp_raz" => [
                'required',
                'string',
                'max:60',
            ],
            "emp_fan" => [
                'string',
                'required',
                'max:60',
            ],
            "emp_ativa" => [
                'boolean',
                'required',
            ],
            "emp_uf" => [
                'string',
                'max:2',
                'required',
            ],
            "emp_caminho_img" => [
                'string',
                'nullable',
            ],
            "emp_url_img" => [
                'string',
                'nullable',
            ],
            "emp_email" => [
                'string',
                'max:100',
                'email',
                'required',
            ]
        ];
    }
}
