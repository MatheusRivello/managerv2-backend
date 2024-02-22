<?php

namespace App\Rules\Api\Externa;

class MixProdutoExternaRule
{
    public function rules($id, $conection = null)
    {
        return [
            "idProduto" => [
                "Required",
                "int",
                "exists:{$conection}.produto,id"
            ],
            "idCliente" => [
                "Required",
                "int",
                "exists:{$conection}.cliente,id"
            ],
            "qtdMinima" => [
                "Nullable",
                "numeric"
            ],
            "qtdFaturada" => [
                "Nullable",
                "numeric"
            ]
        ];
    }
}
