<?php

namespace App\Rules\Api\Tenant;

use Illuminate\Validation\Rule;

class LogTenantRule
{

    public function rules($id, $connection = null)
    {
        return [
            "tipo" => [
                'required',
                'string',
                Rule::in([1, 2, 3, 10, 11, 12, 20, 21]),
                'max:50',
            ],
            "mac" => [
                'string',
                'max:12',
                'required',
            ],
            "id_cliente" => [
                'int',
                'nullable',
                "exists:{$connection}.cliente,id",
            ],
            "id_filial" => [
                'int',
                'nullable',
                "exists:{$connection}.filial,id",
            ],
            "tabela" => [
                'string',
                'max:100',
            ],
            "conteudo" => [
                'string',
                'required',
                'max:400000000',
            ],
            "mensagem" => [
                Rule::in(['inserido', 'falha', 'falha_inserir', 'atualizado', 'falha_atualizar', 'deletado', 'falha_deletar', 'replace']),
                'string',
                'required',
            ]
        ];
    }
}
