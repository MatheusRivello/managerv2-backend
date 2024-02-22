<?php

namespace App\Rules\Api\Externa;

class ProdutoTabelaExternaRule
{
    public function rules($id, $conection = Null)
    {
        return [
            "idProduto" => [
                "Required",
                "int",
                "exists:{$conection}.produto,id"
            ],
            "idProtabelaPreco" => [
                "Required",
                "int",
                "exists:{$conection}.protabela_preco,id"
            ],
            "unitario" => [
                "nullable",
                "numeric"
            ],
            "status" => [
                "Required",
                "int"
            ],
            "qevendamax" => [
                "nullable",
                "numeric"
            ],
            "qevendamin" => [
                "nullable",
                "numeric"
            ],
            "desconto" => [
                "nullable",
                "numeric"
            ],
            "desconto2" => [
                "nullable",
                "numeric"
            ],
            "desconto3" => [
                "nullable",
                "numeric"
            ]

        ];
    }
}
