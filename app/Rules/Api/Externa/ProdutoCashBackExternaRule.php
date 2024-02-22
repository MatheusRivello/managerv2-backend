<?php

namespace App\Rules\Api\Externa;

class ProdutoCashBackExternaRule
{
    public function rules($id, $conection = null)
    {
        return [
            'idIntegrador'=>[
                'Required',
                'int',
                "exists:{$conection}.integracao,id"
            ],
            'idProduto'=>[
                'Required',
                'int',
                "exists:{$conection}.produto,id"
            ],
            'cashback'=>[
                'Required',
                'numeric'
            ],
            'dtModificado'=>[
                'Required',
                'Date'
            ]
        ];
    }
}