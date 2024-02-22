<?php

namespace App\Rules\Api\Servico;

class ClienteServiceRule
{
    public function rules()
    {
        return [
            "id_mobile" => [
                "Required",
                "max:11"
            ],
            "id_tabela_preco" => [
                "string",
                "max:15"
            ],
            "id_prazo_pgto" => [
                "string",
                "max:15"
            ],
            "id_forma_pgto" => [
                "string",
                "max:15"
            ],
            "razao_social" => [
                "string",
                "max:100"
            ],
            "nome_fantasia" => [
                "string",
                "max:100"
            ],
            "cnpj" => [
                "Required",
                "string",
                "max:14",
                "min:11"
            ],
            "senha" => [
                "string",
                "max:32"
            ],
            "email" => [
                "string",
                "max:100",
                "email",
                "regex:/(.*)@c4kurd|.com/i"
            ],
            "codigo_senha" => [
                "string",
                "max:40"
            ],
            "id_atividade" => [
                "max:15"
            ],
            "telefone" => [
                "string",
                "max:15"
            ],
            "tipo" => [
                "string",
                "max:1"
            ],
            "tipo_contribuinte" => [
                "string",
                "max:5"
            ],
            "site" => [
                "string",
                "max:100"
            ],
            "email_financeiro" => [
                "string",
                "max:100"
            ],
            "email_nfe" => [
                "string",
                "max:100"
            ],
            "limite_credito" => [
                "numeric",
                "max:20"
            ],
            "saldo" => [
                "numeric",
                "max:20"
            ],
            "sinc_erp" => [
                "boolean",
            ],
            "prospecto" => [
                "string",
                "max:5"
            ],
            "uf" => [
                "string",
                "max:2"
            ],
            "observacao" => [
                "string"
            ],
            "intervalo_visita" => [
                "numeric",
                "max:11"
            ],
            "bloqueia_forma_pgto" => [
                "boolean",
            ],
            "bloqueia_prazo_pgto" => [
                "boolean",
            ],
            "bloqueia_tabela" => [
                "boolean"
            ],
            "inscricao_municipal" => [
                "string",
                "max:45"
            ],
            "inscricao_rg" => [
                "string",
                "max:45"
            ],
            "integra_web" => [
                "boolean"
            ],
            "codigo_tempo" => [
                "date"
            ],
            "dt_ultima_visita" => [
                "date"
            ],
            "dt_cadastro" => [
                "date"
            ],
            "dt_modificado" => [
                "date"
            ]
        ];
    }
}
