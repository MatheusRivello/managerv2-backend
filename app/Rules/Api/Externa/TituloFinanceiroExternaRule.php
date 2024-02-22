<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class TituloFinanceiroExternaRule
{
    public function rules($id, $connection = null)
    {
        return [
            "idCliente" => [
                'required',
                'int',
                "exists:{$connection}.cliente,id"
            ],
            "idFormaPgto" => [
                'required',
                'int',
                "exists:{$connection}.forma_pagamento,id"
            ],
            "idVendedor" => [
                'int',
                'nullable',
                "exists:{$connection}.vendedor,id"
            ],
            "descricao" => [
                'string',
                'max:30'
            ],
            "idRetaguarda" => [
                'string',
                'max:25'
            ],
            "numeroDoc" => [
                'string',
                'max:20'
            ],
            "tipoTitulo" => [
                'string',
                'max:20'
            ],
            "parcela" => [
                'string',
                'max:5'
            ],
            "dtVencimento" => [
                'Date',
                'nullable',
            ],
            "dtPagamento" => [
                'Date',
                'nullable'
            ],
            "dtCompetencia" => [
                "Date",
                "nullable"
            ],
            "dtEmissao" => [
                "Date",
                "nullable"
            ],
            "valor" => [
                'numeric'
            ],
            "multaJuros" => [
                "nullable",
                "numeric"
            ],
            "status" => [
                "int",
                Rule::in([0,1])
            ],
            "valorOriginal" => [
                "numeric",
                "nullable"
            ],
            "linhaDigitavel" => [
                "string",
                "nullable",
                "max:100"
            ]
        ];
    }
}
