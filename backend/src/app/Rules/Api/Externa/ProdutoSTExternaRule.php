<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class ProdutoSTExternaRule
{
    public function rules($id, $connection = null)
    {
        return [
            'idProduto' => [
                "Required",
                "int",
                "exists:{$connection}.produto_st,id"
            ],
            "tipoContribuinte" => [
                "Required",
                "string",
                "max:15"
            ],
            "uf" => [
                "Required",
                "string",
                "max:2"
            ],
            "aliqutaIcms" => [
                "nullable",
                "numeric"
            ],
            "aliquotaIcmsSt" => [
                "nullable",
                "numeric"
            ],
            "valorReferencia" => [
                "nullable",
                "numeric"
            ],
            "classPautaMva" => [
                "Required",
                "int",
                Rule::in(0, 1)
            ],
            "pauta" => [
                "nullable",
                "numeric"
            ],
            "tipoMva" => [
                "Required",
                "int"
            ],
            "mva" => [
                "nullable",
                "numeric"
            ],
            "reducaoIcms" => [
                "nullable",
                "Numeric"
            ],
            "reducaoIcmsSt" => [
                "nullable",
                "numeric"
            ],
            "modoCalculo" => [
                "nullable",
                "string",
                "max:3"
            ],
            "calculaIpi" => [
                "nullable",
                "int",
                Rule::in(0, 1)
            ],
            "freteIcms" => [
                "nullable",
                "int",
                Rule::in(0, 1)
            ],
            "freteIpi" => [
                "nullable",
                "int",
                Rule::in(0, 1)
            ],
            "incideIpiBase" => [
                "nullable",
                "int",
                Rule::in(0, 1)
            ]
        ];
    }
}
