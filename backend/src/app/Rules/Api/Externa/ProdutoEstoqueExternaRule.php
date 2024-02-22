<?php

namespace App\Rules\Api\Externa;

class ProdutoEstoqueExternaRule
{
    public function rules()
    {
        return [
            'idProduto' => [
                "Required",
                "int"
            ],
            'unidade' => [
                "Required",
                "string",
                "max:100"
            ],
            'quantidade' => [
                "Required",
                "numeric"
            ],
            'dtModificado' => [
                "Required",
                "Date"
            ]

        ];
    }
}
