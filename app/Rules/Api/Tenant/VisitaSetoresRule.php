<?php

namespace App\Rules\Api\Tenant;

class VisitaSetoresRule
{
    public function rules($id, $conection = null)
    {
        return [
            'idFilial' => [
                'Required',
                'int',
                "exists:{$conection}.filial,id"
            ],
            'descricao'=>[
                'Required',
                'string',
                'max:255'
            ],
            'cor'=>[
                'Nullable',
                'string',
                'max:255'
            ],
            'status'=>[
                'Required',
                'bool'
            ]

        ];
    }
}
