<?php

namespace App\Rules\Api\Tenant;

use Illuminate\Validation\Rule;

class SeguroTenantRule
{
    public function rules($id, $connection = null)
    {
        return [
            "valor" => [
                'required',
                'numeric',
                'between:0,999999999.999999',
            ],
            "uf" => [
                'required',
                'string',
                'max:2',
                "unique:{$connection}.seguro,uf" . ", $id",
                Rule::in('AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO')
            ]
        ];
    }
}
