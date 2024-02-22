<?php

namespace App\Rules\Api\Central;

class AcessoMenuRule
{

    public function rules($id, $connection = null)
    {
        return [
            "fk_tipo_empresa" => [
                'required',
                "exists:tipo_empresa,id"
            ],
            "fk_menu" => [
                'nullable',
                "int",
                "exists:menu,id"
            ],
            "fk_tipo_permissao" => [
                'nullable',
                "int",
                "exists:tipo_permissao,id"
            ],
            "classe" => [
                'nullable',
                'string',
                'max:100'
            ],
            "descricao" => [
                'required',
                'string',
                'max:100'
            ],
            "url" => [
                'required',
                'string',
                'max:65535',
                'unique:menu,url' . ", $id",
            ],
            "personalizado" => [
                'required',
                'boolean'
            ],
            "extra" => [
                'nullable',
                'string',
                'max:65535'
            ],
            "ordem" => [
                'required',
                'int'
            ],
            "exibir_cabecalho" => [
                'required',
                'boolean'
            ],
        ];
    }
}
