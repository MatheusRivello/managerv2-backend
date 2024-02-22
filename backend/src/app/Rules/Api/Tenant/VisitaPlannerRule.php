<?php

namespace App\Rules\Api\Tenant;

class VisitaPlannerRule
{
    public function rules($id, $conection = null)
    {
        return [
            'idCliente' => [
                'Required',
                'int',
                "exists:{$conection}.cliente,id"
            ],
            'idVendedor' => [
                'Required',
                'int',
                "exists:{$conection}.vendedor,id"
            ],
            'prioridade' => [
                'Required',
                'int'
            ],
            'ordem' => [
                'Required',
                'int'
            ],
            'dias' => [
                'Required',
                'string',
                'max:255'
            ],
            'idSetor' => [
                'Required',
                'int',
                "exists:{$conection}.visita_setores,id"
            ]

        ];
    }
}
