<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class EnderecoExternaRule
{

    public function rules($id, $conection = null)
    {
        return [
            "idRetaguarda" => [
                "Required",
                "string",
                "max:25"
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
                "Nullable",
                "int"
            ],
            "cep" => [
                "Nullable",
                "string",
                "max:9"
            ],
            "logradouro" => [
                "Nullable",
                "string",
                "max:40"
            ],
            "numero" => [
                "Nullable",
                "string",
                "max:10"
            ],
            "complemento" => [
                "Nullable",
                "string",
                "max:20"
            ],
            "bairro" => [
                "Nullable",
                "string",
                "max:20"
            ],
            "uf" => [
                "Nullable",
                "string",
                "max:2"
            ],
            "latitude" => [
                "Nullable",
                "string",
                "max:15"
            ],
            "longitude" => [
                "Nullable",
                "string",
                "max:15"
            ],
            "referencia" => [
                "Nullable",
                "string",
                "max:120"
            ]
        ];
    }
}
