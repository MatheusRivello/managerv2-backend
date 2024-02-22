<?php

namespace App\Rules\Api\Externa;

class MetaExternaRule
{
    public function rules($id, $conection = null)
    {
        return [
            "idFilial" => [
                "Required",
                "int",
                "exists:{$conection}.filial,id"
            ],
            "idVendedor" => [
                "Required",
                "int",
                "exists:{$conection}.vendedor,id"
            ],
            "idRetaguarda" => [
                "Required",
                "string",
                "max:50"
            ],
            "descricao" => [
                "Required",
                "string",
                "max:100"
            ],
            "totQtdVen" => [
                "Nullable",
                "numeric"
            ],
            "totPesoVen" => [
                "Nullable",
                "numeric"
            ],
            "objetivoVendas" => [
                "Nullable",
                "numeric"
            ],
            "totalValVen" => [
                "Nullable",
                "numeric"
            ],
            "percentAtingido" => [
                "Nullable",
                "numeric"
            ]
        ];
    }
}
