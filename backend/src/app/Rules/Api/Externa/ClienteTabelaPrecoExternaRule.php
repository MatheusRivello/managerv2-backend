<?php

namespace App\Rules\Api\Externa;

class ClienteTabelaPrecoExternaRule
{
    public function rules($id, $conection = null)
    {
        return [
            "idCliente" => [
                "Required",
                "int",
                "exists:{$conection}.cliente,id"
            ],
            "idTabela" => [
                "Required",
                "int",
                "exists:{$conection}.protabela_preco,id"
            ]
        ];
    }
}
