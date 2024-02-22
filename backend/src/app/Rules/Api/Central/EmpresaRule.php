<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class EmpresaRule
{

    public function rules($id, $connection = null)
    {
        return [
            "razao_social" => [
                'required',
                'string',
                'unique:empresa,razao_social' . ", $id",
                Rule::unique('empresa', 'id')->ignore($id),
                'min:5',
                'max:60'
            ],
            "nome_fantasia" => [
                'string',
                'min:5',
                'max:60'
            ],
            "codigo_autenticacao" => [
                'required',
                'string',
                'unique:empresa,bd_nome' . ", $id",
                Rule::unique('empresa', 'id')->ignore($id),
                'max:100'
            ],
            "cnpj" => [
                'required',
                'string',
                'unique:empresa,cnpj' . ", $id",
                Rule::unique('empresa', 'id')->ignore($id),
                'min:5',
                'max:100'
            ],
            "email" => [
                'required',
                'email',
                'unique:empresa,email' . ", $id",
                Rule::unique('empresa', 'id')->ignore($id),
                'max:100'
            ],
            "qtd_licenca" => [
                'required',
                'integer'
            ],
            "contato" => [
                'string',
                'max:50'
            ],
            "telefone1" => [
                'string',
                'min:10',
                'max:11'
            ],
            "telefone2" => [
                'string',
                'min:10',
                'max:11'
            ],
            "usa_pw" => [
                'boolean',
            ],
            "pw_status" => [
                'boolean',
            ],
            "pw_dominio" => [
                'string',
                'unique:empresa,pw_dominio' . ", $id",
                Rule::unique('empresa', 'id')->ignore($id),
                'min:13',
                'max:100'
            ],
            "bd_host" => [
                'string',
                'max:100'
            ],
            "bd_porta" => [
                'string',
                'max:10'
            ],
            "bd_usuario" => [
                'string',
                'max:100'
            ],
            "bd_senha" => [
                'string',
                'max:100'
            ],
            "bd_nome" => [
                'string',
                'unique:empresa,bd_nome' . ", $id",
                Rule::unique('empresa', 'id')->ignore($id),
                'max:100'
            ],
            "bd_ssl" => [
                'boolean'
            ],
            "ip" => [
                'string',
                'nullable',
                'min:7',
                'max:15'
            ],
            "dt_ultimo_login" => [
                'date'
            ],
            "status" => [
                'boolean',
            ],
            "dt_cadastro" => [
                'date'
            ],
            "dt_modificado" => [
                'date'
            ],
            "atualizar_sincronizador" => [
                'integer'
            ],
            "versao_sincronizador" => [
                'string',
                'max:45'
            ],
            "dt_versao_sincronizador" => [
                'date'
            ],
            "pw_filial" => [
                'integer'
            ],
            "pw_nome" => [
                'string',
                'max:100'
            ],
            "pw_logo" => [
                'string',
                'max:100'
            ],
            "atualizada" => [
                'boolean'
            ]

        ];
    }
}
