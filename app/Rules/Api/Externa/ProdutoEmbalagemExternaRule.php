<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class ProdutoEmbalagemExternaRule
{
    public function rules($id, $conection = null)
    {
        return [
            "idProduto" => [
                "Required",
                "int",
                "exists:{$conection}.produto,id"
            ],
            "unidade" => [
                "Required",
                "string",
                "max:100"
            ],
            "embalagem" => [
                "Nullable",
                "string",
                "max:100"
            ],
            "fator" => [
                "Required",
                "int"
            ],
            "status" => [
                "Required",
                "int",
                Rule::in(0, 1)
            ]
        ];
    }
}
