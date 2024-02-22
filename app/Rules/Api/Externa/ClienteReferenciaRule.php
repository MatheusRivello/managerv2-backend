<?php

namespace App\Rules\Api\Externa;

class ClienteReferenciaRule
{
    public function rules($id, $connection = null)
    {
        return [
            "idCliente" => [
                "Required",
                "int",
                "exists:{$connection}.cliente,id"
            ],
            "idReferencia" => [
                "Required",
                "int",
                "exists:{$connection}.referencia,id"
            ]
        ];
    }
}
