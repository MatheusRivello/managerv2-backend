<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class CampanhaBeneficioExternaRule
{
    public function rules($id, $conection)
    {
        return [
            "idCampanha" => [
                "Required",
                "int",
                "exists:{$conection}.campanha,id"
            ],
            "idRetaguarda" => [
                "Required",
                "string",
                "max:15"
            ],
            "tipo" => [
                "nullable",
                "int",
            ],
            "codigo" => [
                "Nullable",
                "String",
                "max:30"
            ],
            "quantidade" => [
                "Nullable",
                "numeric"
            ],
            "percentualDesconto" => [
                "nullable",
                "numeric"
            ],
            "descontoAutomatico" => [
                "nullable",
                "int"
            ],
            "bonificaoAutomatica" => [
                "Nullable",
                "int"
            ],
            'status' => [
                "nullable",
                "int",
                Rule::in(0, 1)
            ]
        ];
    }
}
