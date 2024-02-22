<?php

namespace App\Rules\Api\Servico;

use Illuminate\Validation\Rule;

class EnderecoServiceRule
{
    public function rules()
    {
        return [
            "cep" => [
                "string",
                "max:9"
            ],
            "logradouro" => [
                "string",
                "max:100"
            ],
            "numero" => [
                "string",
                "max:10"
            ],
            "complemento" => [
                "string",
                "max:100"
            ],
            "referencia" => [
                "string",
                "max:120"
            ],
            "bairro" => [
                "string",
                "max:100"
            ],
            "uf" => [
                "string",
                "max:2",
                Rule::in("AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"),
            ],
            "latitude" => [
                "string",
                "max:15"
            ],
            "longitude" => [
                "string",
                "max:15"
            ],
            "tipo" => [
                "string",
                "max:1"
            ],
            "id_cidade" => [
                "max:11"
            ],
        ];
    }
}
