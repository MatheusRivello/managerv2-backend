<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class ProdutoIpiExternaRule
{
    public function rules()
    {
        return [
            "idProduto" => [
                "Required",
                "int"
            ],
            "tipiMva" => [
                "nullable",
                "numeric"
            ],
            "tipeMvaSimples" => [
                "nullable",
                "numeric"
            ],
            "tipeMvaFeNac" => [
                "nullable",
                "numeric"
            ],
            "tipeMvaFeImp" => [
                "nullable",
                "numeric"
            ],
            "tipiTpcalc" => [
                "nullable",
                "int"
            ],
            "tipiAliquota" => [
                "nullable",
                "numeric"
            ],
            "tipiPauta" => [
                "nullable",
                "numeric"
            ],
            "calculaIpi" => [
                "nullable",
                "int",
                Rule::in(0,1)
            ]

        ];
    }
}
