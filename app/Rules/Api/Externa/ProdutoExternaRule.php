<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class ProdutoExternaRule
{
    public function rules($id, $connection = null)
    {
        return [
            "idFilial" => [
                "Required",
                "int",
                "exists:{$connection}.filial,id"
            ],
            "idFornecedor" => [
                "Nullable",
                "int",
                "exists:{$connection}.fornecedor,id"
            ],
            "idRetaguarda" => [
                "Nullable",
                "string",
                "max:20"
            ],
            "idGrupo" => [
                "Required",
                "string",
                "max:15"
            ],
            "idSubgrupo" => [
                "Required",
                "string",
                "max:15"
            ],
            "descricao" => [
                "Required",
                "string",
                "max:100"
            ],
            "codBarras" => [
                "Nullable",
                "string",
                "max:20"
            ],
            "dun" => [
                "Nullable",
                "string",
                "max:20"
            ],
            "ncm" => [
                "Nullable",
                "string",
                "max:10"
            ],
            "metaTitle" => [
                "Nullable",
                "string",
                "max:255"
            ],
            "metaKeywords" => [
                "Nullable",
                "string",
                "max:255"
            ],
            "metaDescription" => [
                "Nullable",
                "string",
                "max:255"
            ],
            "descricaoCurta" => [
                "Nullable",
                "string",
                "max:255"
            ],
            "freteGratis" => [
                "Nullable",
                "int",
                Rule::in(0, 1)
            ],
            "status" => [
                "Required",
                "int",
                Rule::in(0, 1)
            ],
            "proInicio" => [
                "Nullable",
                "Date",
                "before_or_equal:proFinal"
            ],
            "proFinal" => [
                "Nullable",
                "Date",
                "after_or_equal:proFinal"
            ],
            "unidVenda" => [
                "Nullable",
                "string",
                "max:8"
            ],
            "embalagem" => [
                "Nullable",
                "string",
                "max:10"
            ],
            "qtdEmbalagem" => [
                "Required",
                "int"
            ],
            "proQtdEstoque" => [
                "Nullable",
                "numeric"
            ],
            "pesBrus" => [
                "Nullable",
                "numeric"
            ],
            "pesLiq" => [
                "Nullable",
                "numeric"
            ],
            "comprimento" => [
                "Required",
                "numeric"
            ],
            "largura" => [
                "Required",
                "numeric"
            ],
            "espessura" => [
                "Required",
                "numeric"
            ],
            "ultOrigem" => [
                "Nullable",
                "int",
                "max:1"
            ],
            "ultiUf" => [
                "Nullable",
                "string",
                "max:2"
            ],
            "custo" => [
                "Nullable",
                "numeric"
            ],
            "desctoVerba" => [
                "Nullable",
                "numeric"
            ],
            "aplicacao" => [
                "Nullable",
                "string"
            ],
            "referencia" => [
                "Nullable",
                "string",
                "max:20"
            ],
            "tipoEstoque" => [
                "Nullable",
                "int"
            ],
            "dtValidade" => [
                "Nullable",
                "date"
            ],
            "multiplo" => [
                "Nullable",
                "numeric"
            ],
            "integraWeb" => [
                "Nullable",
                "int"
            ],
            "dtAlteracao" => [
                'Nullable',
                'Date'
            ],
            "pwFilial" => [
                "Nullable",
                "int"
            ],
            "APRESENTA_VENDA" => [
                "Required",
                "int",
                Rule::in(0, 1)
            ]
        ];
    }
}
