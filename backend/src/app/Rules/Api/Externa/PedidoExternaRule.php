<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class PedidoExternaRule
{
    public function rules($id, $conection = null)
    {
        return [
            "idFilial" => [
                "Required",
                "int",
                "exists:{$conection}.filial,id"
            ],
            "idRetaguarda" => [
                "Nullable",
                "string",
                "max:15"
            ],
            "idEndereco" => [
                "Nullable",
                "string",
                "max:25",
                "exists:{$conection}.endereco,id"
            ],
            "idCliente" => [
                "Required",
                "int",
                "exists:{$conection}.cliente,id"
            ],
            "idPedidoDispositivo" => [
                "nullable",
                "int"
            ],
            "idTabela" => [
                "Required",
                "int",
                "exists:{$conection}.protabela_preco,id"
            ],
            "idVendedor" => [
                "nullable",
                "int",
                "exists:{$conection}.vendedor,id"
            ],
            "idPrazoPgto" => [
                "Required",
                "int",
                "exists:{$conection}.prazo_pagamento,id"
            ],
            "idFormaPgto" => [
                "Required",
                "int",
                "exists:{$conection}.forma_pagamento,id"
            ],
            "idTipoPedido" => [
                "Required",
                "int",
                "exists:{$conection}.tipo_pedido,id"
            ],
            "supervisor" => [
                "nullable",
                "int"
            ],
            "gerente" => [
                "nullable",
                "int"
            ],
            "valorTotal" => [
                "nullable",
                "numeric"
            ],
            "qtdeItens" => [
                "nullable",
                "int"
            ],
            "notaFiscal" => [
                "nullable",
                "string",
                "max:45"
            ],
            "status" => [
                "nullable",
                "int",
                Rule::in(0, 1)
            ],
            "statusEntrega" => [
                "nullable",
                "int",
                Rule::in(0, 1)
            ],
            "placa" => [
                "nullable",
                "string",
                "max:8"
            ],
            "valorSt" => [
                "nullable",
                "numeric"
            ],
            "valorIpi" => [
                "nullable",
                "numeric"
            ],
            "valorAcrescimo" => [
                "nullable",
                "numeric"
            ],
            "valorDesconto" => [
                "nullable",
                "numeric"
            ],
            "valorTotalComImpostos" => [
                "nullable",
                "numeric"
            ],
            "valorTotalBruto" => [
                "nullable",
                "numeric"
            ],
            "valorVerba" => [
                "nullable",
                "numeric"
            ],
            "bonificacaoPorVerba" => [
                "Required",
                "int"
            ],
            "valorFrete" => [
                "nullable",
                "numeric"
            ],
            "valorSeguro" => [
                "nullable",
                "numeric"
            ],
            "margem" => [
                "nullable",
                "numeric"
            ],
            "observacao" => [
                "nullable",
                "text"
            ],
            "observacaoCliente" => [
                "nullable",
                "string"
            ],
            "previsaoEntrega" => [
                "nullable",
                "Date"
            ],
            "pedidoOriginal" => [
                "nullable",
                "string",
                "max:15"
            ],
            "latitude" => [
                "nullable",
                "string",
                "max:20"
            ],
            "logitude" => [
                "nullable",
                "string",
                "max:20"
            ],
            "precisao" => [
                "nullable",
                "string",
                "max:20"
            ],
            "dtEntrega" => [
                "nullable",
                "Date"
            ],
            "dtInicial" => [
                "nullable",
                "Date"
            ],
            "dtEmissao" => [
                "nullable",
                "Date"
            ],
            "dtSincErp" => [
                "nullable",
                "Date"
            ],
            "dtCadastro" => [
                "nullable",
                "Date"
            ],
            "origem" => [
                "nullable",
                "string",
                "max:12"
            ],
            "notificacaoAfvManager" => [
                "nullable",
                "int"
            ],
            "enviarEmail" => [
                "nullable",
                "int"
            ],
            "ip" => [
                "nullable",
                "string",
                "max:32"
            ],
            "mac" => [
                "nullable",
                "string",
                "max:14"
            ],
            "autorizacaoDataEnviada" => [
                "nullable",
                "Date"
            ],
            "autorizacaoSenha" => [
                "nullable",
                "string",
                "max:20"
            ],
            "autorizacaoDataProcessada" => [
                "Nullable",
                "Date"
            ],
            "distanciaCliente" => [
                "nullable",
                "string",
                "max:15"
            ],
            "motivoBloqueio" => [
                "nullable",
                "int"
            ],
            "pesBru" => [
                "nullable",
                "numeric"
            ],
            "pesLiq" => [
                "nullable",
                "numeric"
            ],
            "nfsNum" => [
                "nullable",
                "numeric"
            ]
        ];
    }
}
