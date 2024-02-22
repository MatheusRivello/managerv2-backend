<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class CampanhaExternaRule
{
    public function rules($id, $conection = null)
    {
        return [
            "idFilial" => [
                "int",
                "required",
                "exists:{$conection}.filial,id"
            ],
            "idRetaguarda" => [
                "string",
                "required",
                "max:15"
            ],
            "descricao" => [
                "string",
                "required",
                "max:120"
            ],
            "tipoModalidade" => [
                "int",
                "required",
                "max:6"
            ],
            "dataInicial" => [
                "date",
                "required"
            ],
            "dataFinal" => [
                "date",
                "required"
            ],
            "permiteAcumularDesconto" => [
                "int",
                "required",
                "max:1"
            ],
            "mixMinimo" => [
                "int",
                "required"
            ],
            "valorMinimo" => [
                "numeric",
                "required",
                "max:19"
            ],
            "valorMaximo" => [
                "numeric",
                "required",
                "max:19"
            ],
            "volumeMinimo" => [
                "numeric",
                "required",
                "max:19"
            ],
            "volumeMaximo" => [
                "numeric",
                "required",
                "max:19"
            ],
            "qtdMaxBonificacao" => [
                "int",
                "required",
                "max:11"
            ],
            "status" => [
                "int",
                "required",
                "max:4",
                Rule::in(0, 1)
            ]
        ];
    }
}
