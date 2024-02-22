<?php

namespace App\Rules\Api\Servico;

class ReferenciaServiceRule
{
    public function rules()
    {
        return [
            "sequencia" => [
                "int",
                "min:0"
            ],
            "fornecedor" => [
                "int",
                "exists:fornecedor,id"
            ],
            "telefone" => [
                "string",
                "nullable",
                "max:20"
            ],
            "desde" => [
                "string",
                "max:20"
            ],
            "conceito" => [
                "string",
                "max:45"
            ],
            "limite" => [
                "numeric",
                "min:0"
            ],
            "pontual" => [
                "int",
                "min:0"
            ],
            "ultima_fatura_valor" => [
                "numeric"
            ],
            "ultima_fatura_data" => [
                "date"
            ],
            "maior_fatura_valor" => [
                "numeric"
            ],
            "maior_fatura_data" => [
                "date"
            ],
            "maior_acumulo_valor" => [
                "numeric"
            ],
            "maior_acumulo_data" => [
                "date"
            ],
        ];
    }
}
