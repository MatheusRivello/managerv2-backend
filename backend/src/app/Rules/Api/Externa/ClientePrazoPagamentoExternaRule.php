<?php

namespace App\Rules\Api\Externa;

class ClientePrazoPagamentoExternaRule
{
    public function rules($id, $connection = null)
    {
        return [
            "idCliente" => [
                "Required",
                "int",
                "exists:{$connection}.cliente,id"
            ],
            "idPrazoPgto" => [
                "Required",
                "int",
                "exists:{$connection}.prazo_pagamento,id"
            ]
        ];
    }
}
