<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class ClienteExternaRule
{
    public function rules($id, $connection = null)
    {
        return [
            "idFilial" => [
                "required",
                "int",
                "exists:{$connection}.filial,id"
            ],
            "idRetaguarda" => [
                "nullable",
                "string",
                "max:15",
            ],
            "idTabelaPreco" => [
                "nullable",
                "string",
                "max:15",
                "exists:{$connection}.Protabela_preco,id_retaguarda"
            ],
            "idPrazoPgto" => [
                "nullable",
                "string",
                "max:15",
                "exists:{$connection}.Prazo_pagamento,id_retaguarda"
            ],
            "idFormaPgto" => [
                "nullable",
                "string",
                "max:15",
                "exists:{$connection}.forma_pagamento,id_retaguarda"
            ],
            "idStatus" => [
                "nullable",
                "int"
            ],
            "razaoSocial" => [
                "Nullable",
                "string",
                "max:60"
            ],
            "nomeFantasia" => [
                "Nullable",
                "string",
                "max:60"
            ],
            "cnpj" => [
                "Required",
                "string",
                "max:14"
            ],
            "senha" => [
                "Nullable",
                "string",
                "max:32"
            ],
            "email" => [
                "Nullable",
                "string",
                "max:100"
            ],
            "codigoTempo" => [
                "Nullable",
                "Datetime"
            ],
            "codigoSenha" => [
                "Nullable",
                "string",
                "max:32"
            ],
            "idAtividade" => [
                "Nullable",
                "string",
                "max:15"
            ],
            "telefone" => [
                "Nullable",
                "string",
                "max:15"
            ],
            "tipo" => [
                "Nullable",
                "string",
                "int",
                Rule::in(0, 1)
            ],
            "tipoContribuinte" => [
                "Nullable",
                "string",
                "max:5"
            ],
            "site" => [
                "Nullable",
                "string",
                "max:100"
            ],
            "emailNfe" => [
                "Nullable",
                "max:100"
            ],
            "limiteCredito" => [
                "nullable",
                "Numeric"
            ],
            "saldo" => [
                "Nullable",
                "Numeric"
            ],
            "saldoCredor" => [
                "Required",
                "Numeric"
            ],
            "sincErp" => [
                "Nullable",
                Rule::in(0, 1)
            ],
            "observacao" => [
                "Nullable",
                "string"
            ],
            "intervaloVisita" => [
                "string",
                "max:1"
            ],
            "dtUltimaVisita" => [
                "Nullable",
                "Datetime"
            ],
            "dtCadastro" => [
                "Nullable",
                "Datetime"
            ],
            "dtModificado" => [
                "Nullable",
                "Datetime"
            ],
            "bloqueiaFormaPgto" => [
                "Nullable",
                "bool"
            ],
            "bloqueiaPrazoPgto" => [
                "Nullable",
                "bool"
            ],
            "bloqueiaTabela" => [
                "Nullable",
                "bool"
            ],
            "idMobile" => [
                "Nullable",
                "int"
            ],
            "inscricaoMunicipal" => [
                "Nullable",
                "string",
                "max:45"
            ],
            "inscricaoRg" => [
                "string",
                "max:45"
            ],
            "venCod" => [
                "Nullable",
                "int"
            ],
            "integraWeb" => [
                "Nullable",
                "int"
            ],
            "atrasoTot" => [
                "Required",
                "Numeric"
            ],
            "avencer" => [
                "Required",
                "numeric"
            ],
            "mediaDiasAtraso" => [
                "Nullable",
                "int"
            ],
            "dtUltimaCompra" => [
                "Nullable",
                "Date"
            ]

        ];
    }
}
