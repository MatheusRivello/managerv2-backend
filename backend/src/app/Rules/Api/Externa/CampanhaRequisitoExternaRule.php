<?php

namespace App\Rules\Api\Externa;

class CampanhaRequisitoExternaRule
{
    public function rules()
    {
        return [
            "idCampanha" => [
                "required",
                "int"
            ],
            "idRetaguarda" => [
                "required",
                "string",
                "max:15"
            ],
            "tipo" => [
                "nullable",
                "int",
                "max:6"
            ],
            "codigo" => [
                "nullable",
                "string",
                "max:20"
            ],
            "quantidade" => [
                "nullable",
                "numeric"
            ],
            "quantidadeMax" => [
                "nullable",
                "numeric"
            ],
            "percentualDesconto" => [
                "nullable",
                "numeric"
            ],
            "obrigatorio" => [
                "nullable",
                "int",
                "max:4"
            ],
            "status" => [
                "nullable",
                "int",
                "max:4"
            ],
        ];
    }
}
