<?php

namespace App\Rules\Api\Externa;

class VendaPlanoxVendedorRule
{
    public function rules($id, $connection)
    {
        return [
            "id" => [
                'Required',
                'int'
            ],
            "idFilial" => [
                'Nullable',
                'int'
            ],
            "idCliente" => [
                'Required',
                'int'
            ],
            "idProduto" => [
                'Required',
                'int'
            ],
            "nfsNum" => [
                'Required',
                'string',
                'max:16'
            ],
            "qtdContratada" => [
                'Required',
                'numeric'
            ],
            'qtdEntregue' => [
                'Required',
                'numeric'
            ],
            'qtdDisponivel' => [
                'Required',
                'numeric'
            ],
            'valorUnitario' => [
                'Required',
                'numeric'
            ],
            'unidade' => [
                'required',
                'string',
                'max:10'
            ]
        ];
    }
}
