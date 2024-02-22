<?php

namespace App\Rules\Api\Externa;

class ClienteEnderecoExternaRule
{
    public function rules()
    {
        return [
            "idRetaguarda" => [
                'Required',
                'string',
                'max:25'
            ],
            "idCliente" => [
                "Required",
                "int"
            ],
            "titCod" => [
                "Required",
                "int"
            ],
            "idCidade" => [
                "Required",
                "int"
            ],
            "sincErp" => [
                "nullable",
                "int"
            ],
            "cep" => [
                "nullable",
                "string",
                "max:9"
            ],
            "logradouro" => [
                "nullable",
                "string",
                "max:40"
            ],
            "numero" => [
                "nullable",
                "string",
                "max:10"
            ],
            "complemento" => [
                "nullable",
                "string",
                "max:20"
            ],
            "bairro" => [
                "nullable",
                "string",
                "max:20"
            ],
            "uf" => [
                "string",
                "max:2"
            ],
            "latitude" => [
                "nullable",
                "string",
                "max:15"
            ],
            "longitude" => [
                "nullable",
                "string",
                "max:15"
            ],
            "referencia" => [
                "nullable",
                "string",
                "max:120"
            ]


        ];
    }
}
