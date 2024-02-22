<?php

namespace App\Rules\Api\Relatorios;

use Illuminate\Validation\Rule;

class Pedido
{
    public function rules($id, $conection)
    {
        return [
            "idVendedor" => [
                "Nullable",
                "array",
                "exists:{$conection}.vendedor,id"
            ],
            "idPedido" => [
                "Nullable",
                "array",
                "exists:{$conection}.pedido,id"
            ],
            "idCliente" => [
                "Nullable",
                "array",
                "exists:{$conection}.cliente,id"
            ],
            "idPrazoPgto" => [
                "Nullable",
                "array",
                "exists:{$conection}.prazo_pagamento,id"
            ],
            "idFormaPgto" => [
                "Nullable",
                "array",
                "exists:{$conection}.forma_pagamento,id"
            ],
            "idTabelaPreco" => [
                "Nullable",
                "array",
                "exists:{$conection}.protabela_preco,id"
            ],
            "idFilial" => [
                "Nullable",
                "array",
                "exists:{$conection}.filial,id"
            ],
            "idTipoPedido" => [
                "Nullable",
                "array",
                "exists:{$conection}.tipo_pedido,id"
            ],
            "dataInicial" => [
                "Required",
                "date",
                "before_or_equal:dataFinal"
            ],
            "dataFinal" => [
                "Required",
                "date",
                "after_or_equal:dataInicial"
            ],
            "status" => [
                'Required',
                'array'
            ],
            "exibirItem" => [
                "Required",
                "int",
                Rule::in([0, 1])
            ],
            "paginate" => [
                "Nullable",
                "int"
            ]

        ];
    }
}
