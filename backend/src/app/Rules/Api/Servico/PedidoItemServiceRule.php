<?php

namespace App\Rules\Api\Servico;

use Illuminate\Validation\Rule;

class PedidoItemServiceRule
{
    public function rules($id, $connection = null)
    {
        return [
            "numero_item" => [
                "required",
                "int"
            ],
            "id_produto" => [
                "required",
                "int",
                "exists:{$connection}.produto,id"
            ],
            "id_tabela" => [
                "required",
                "int",
                "exists:{$connection}.protabela_preco,id"
            ],
            "embalagem" => [
                "string",
                "max:11"
            ],
            "unidvenda" => [
                "required",
                "string",
                "max:8"
            ],
            "quantidade" => [
                "required",
                "numeric"
            ],
            "status" => [
                "required",
                "int"
            ],
            "valor_total" => [
                "numeric",
                "required"
            ],
            "status" => [
                "required",
                "int"
            ],
            "observacao" => [
                "nullable",
                "string"
            ],
            "valor_st" => [
                "numeric",
                "required"
            ],
            "valor_tabela" => [
                "numeric",
                "required"
            ],
            "valor_ipi" => [
                "numeric",
                "required"
            ],
            "valor_frete" => [
                "numeric",
                "required"
            ],
            "valor_seguro" => [
                "numeric",
                "required"
            ],
            "base_st" => [
                "numeric",
                "required"
            ],
            "percentualDesconto" => [
                "numeric",
                "required"
            ],
            "tipoAcrescimoDesconto" => [
                "numeric",
                "required"
            ],
            "ped_desqtd" => [
                "numeric",
                "required"
            ],
            "valorVerba" => [
                "numeric",
                "required"
            ],
            "percentualVerba" => [
                "numeric",
                "required"
            ],
            "valorTotalComImpostos" => [
                "numeric",
            ],
        ];
    }
}
