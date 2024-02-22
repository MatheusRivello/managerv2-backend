<?php

namespace App\Rules\Config;

use Illuminate\Validation\Rule;

class GrupoRelatorioRule
{
    public function rules($id, $connection = null)
    {
        return [
            "descricao" => [
                'required',
                'string',
                'max:45',
                Rule::unique('grupo_relatorio', 'descricao')->ignore($id, "id")
            ],
            "empresa" => [
                'required',
                'int',
                "exists:empresa,id"
            ],
            "status" => [
                'required',
                'boolean'
            ]
        ];
    }
}
