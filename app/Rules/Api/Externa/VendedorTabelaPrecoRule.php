<?php

namespace App\Rules\Api\Externa;

class VendedorTabelaPrecoRule
{
    public function rules($id, $connection)
    {
        return [
            'idProtabelaPreco' => [
                'Required',
                'int',
                "exists:{$connection}.protabela_preco,id"
            ],
            'idVendedor' => [
                'Required',
                'int',
                "exists:{$connection}.vendedor,id"
            ]
        ];
    }
}