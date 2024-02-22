<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class IntegracaoRule
{

    public function rules($id, $connection = null)
    {

        return [
            "id_empresa" => [
                'required',
                'integer',
                "exists:empresa,id"
            ],
            "integrador" => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4, 5, 6])
            ],
            "url_base" => [
                'required',
                'string',
                'max:255'
            ],
            "url_loja" => [
                'string',
                'max:255',
                'nullable'
            ],
            "id_filial" => [
                'integer'
            ],
            "id_tabela_preco" => [
                'integer'
            ],
            "usuario" => [
                'string',
                'max:255'
            ],
            "senha" => [
                'string',
                'max:255'
            ],
            "campo_extra_1" => [
                'string',
                'max:255',
                'nullable'
            ],
            "campo_extra_2" => [
                'string',
                'max:255',
                'nullable'
            ],
            "campo_extra_3" => [
                'string',
                'max:255',
                'nullable'
            ],
            "campo_extra_4" => [
                'string',
                'max:255',
                'nullable'
            ],
            "campo_extra_5" => [
                'string',
                'max:255',
                'nullable'
            ],
            "status" => [
                'required',
                'boolean'
            ],
            "data_ativacao" => [
                'date',
                'nullable'
            ],
            "execucao_inicio" => [
                'date',
                'nullable'
            ],
            "execucao_fim" => [
                'date',
                'nullable'
            ],
            "execucao_status" => [
                'required',
                'boolean'
            ]
        ];
    }
}
