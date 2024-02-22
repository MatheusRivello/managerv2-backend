<?php

namespace App\Rules\Api\Servico;

class PedidoCabecalhoServiceRule
{
    public function rules($id, $connection = null)
    {
        return [
            "id_filial" => [
                "int",
                "exists:{$connection}.filial,id"
            ],
            "id_cliente" => [
                "int",
                "exists:{$connection}.cliente,id"
            ],
            "id_endereco" => [
                "nullable",
                "int",
                "exists:{$connection}.endereco,id"
            ],
            "id_pedido_dispositivo" => [
                "string",
                "nullable",
                "max:11"
            ],
            "id_pedido_cliente" => [
                "string",
                "nullable",
                "max:11"
            ],
            "id_tabela" => [
                "int",
                "exists:{$connection}.protabela_preco,id"
            ],
            "id_vendedor" => [
                "int",
                "exists:{$connection}.vendedor,id"
            ],
            "id_prazo_pgto" => [
                "int",
                "exists:{$connection}.prazo_pagamento,id"
            ],
            "id_forma_pgto" => [
                "int",
                "exists:{$connection}.forma_pagamento,id"
            ],
            "supervisor" => [
                "int",
                "exists:{$connection}.vendedor,id"
            ],
            "gerente" => [
                "int",
                "exists:{$connection}.vendedor,id"
            ],
            "id_tipo_pedido" => [
                "int",
                "exists:{$connection}.tipo_pedido,id"
            ],
            "origem" => [
                "string",
                "required"
            ],
            "valor_total" => [
                "numeric",
                "required"
            ],
            "qtde_itens" => [
                "numeric",
                "required"
            ],
            "status" => [
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
            "valor_ipi" => [
                "numeric",
                "required"
            ],
            "valorTotalBruto" => [
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
            "observacao_cliente" => [
                "nullable",
                "string"
            ],
            "pedido_original" => [
                "nullable",
                "max:15"
            ],
            "valor_acrescimo" => [
                "numeric",
                "required"
            ],
            "valor_desconto" => [
                "numeric",
                "required"
            ],
            "valorTotalComImpostos" => [
                "numeric",
                "required"
            ],
            "enviarEmail" => [
                "boolean",
                "required"
            ],
            "latitude" => [
                "string",
                "nullable",
                "max:20"
            ],
            "longitude" => [
                "string",
                "nullable",
                "max:20"
            ],
            "precisao" => [
                "string",
                "nullable",
                "max:20"
            ],
            "valorVerba" => [
                "numeric",
                "required"
            ],
            "bonificacaoPorVerba" => [
                "int",
                "required"
            ],
            "tipo_frete" => [
                "int",
            ],
            "mac" => [
                "string",
                "required",
                "max:14"
            ],
            "autorizacaoSenha" => [
                "string",
                "nullable",
                "max:20"
            ],
            "distanciaCliente" => [
                "nullable",
                "max:15"
            ],
            "motivoBloqueio" => [
                "int",
                "nullable"
            ],
            "autorizacaoDataEnviada" => [
                "nullable",
                "date_format:Y-m-d H:i:s"
            ],
            "dt_emissao" => [
                "required",
                "date_format:Y-m-d H:i:s"
            ],
            "autorizacaoDataProcessada" => [
                "nullable",
                "date_format:Y-m-d H:i:s"
            ],
            "dt_inicial" => [
                "required",
                "date_format:Y-m-d H:i:s"
            ],
            "previsao_entrega" => [
                "date_format:Y-m-d H:i:s"
            ],
        ];
    }
}
