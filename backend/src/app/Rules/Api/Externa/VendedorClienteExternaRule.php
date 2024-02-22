<?php

namespace App\Rules\Api\Externa;


class VendedorClienteExternaRule
{
    public function rules($id, $connection)
    {
        return [
            "idVendedor" => [
                "Required",
                "int"
            ],
            "idCliente" => [
                "Required",
                "int"
            ]

        ];
    }
}
