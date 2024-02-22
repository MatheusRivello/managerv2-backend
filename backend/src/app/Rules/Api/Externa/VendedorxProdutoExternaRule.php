<?php

namespace App\Rules\Api\Externa;

class VendedorxProdutoExternaRule
{
    public function rules($id, $connection)
    {
        return [
            'idProduto' => [
                'Required',
                'int'
            ],
            'idVendedor' => [
                'Required',
                'int'
            ]
        ];
    }
}
