<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class PedidoItemExternaRule
{
    public function rules($id, $conection = null)
    {
        return [
            "idPedido" => [
                "Required",
                "int",
                "exists:{$conection}.pedido,id"
            ],
            "numeroItem" => [
                "Required",
                "int"
            ],
            "idProduto" => [
                "Required",
                "int",
                "exists:{$conection}.produto,id"
            ],
            "idTabela" => [
                "Required",
                "int",
                "exists:{$conection}.protabela_preco,id"
            ],
            "embalagem" => [
                "nullable",
                "string",
                "max:20"
            ],
            "quantidade" => [
                "nullable",
                "numeric"
            ],
            "valorTotal" => [
                "nullable",
                "numeric"
            ],
            "valorSt" => [
                "nullable",
                "numeric"
            ],
            "valorIpi" => [
                "nullable",
                "numeric"
            ],
            "valorTabela" => [
                "nullable",
                "numeric"
            ],
            "valorUnitario" => [
                "nullable",
                "numeric"
            ],
            "valorDesconto" => [
                "nullable",
                "numeric"
            ],
            "cashback" => [
                "Required",
                "numeric"
            ],
            "unitarioCashback" => [
                "Required",
                "numeric"
            ],
            "valorFrete" => [
                "nullable",
                "numeric"
            ],
            "valorSeguro" => [
                "nullable",
                "numeric"
            ],
            "valorVerba" => [
                "nullable",
                "numeric"
            ],
            "valorTotalComImpostos" => [
                "nullable",
                "numeric"
            ],
            "valorIcms" => [
                "nullable",
                "numeric"
            ],
            "pedDesqtd" => [
                "nullable",
                "numeric"
            ],
            "percentualVerba" => [
                "nullable",
                "numeric"
            ],
            "baseSt" => [
                "nullable",
                "numeric"
            ],
            "percentualDesconto" => [
                "nullable",
                "numeric"
            ],
            "tipoAcrescimoDesconto" => [
                "nullable",
                "int"
            ],
            "status" => [
                "nullable",
                "bool"
            ],
            "dtCadastro" => [
                "nullable",
                "date"
            ],
            "unidVenda" => [
                "nullable",
                "string",
                "max:8"
            ],
            "custo" => [
                "nullable",
                "numeric"
            ],
            "margem" => [
                "nullable",
                "numeric"
            ],
            "pesBru" => [
                "nullable",
                "numeric"
            ],
            "pesLiq" => [
                "nullable",
                "numeric"
            ]
        ];
    }
}
