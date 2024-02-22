<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class AvisoRule
{
    public function rules($id, $connection = null)
    {
        return [
            "titulo" => [
                'string',
                'unique:aviso,titulo' . ", $id",
                Rule::unique('perfil', 'id')->ignore($id),
                'min:5',
                'max:100'
            ],
            "status" => [
                'integer'
            ],
            "dt_inicio" => [
                'date'
            ],
            "dt_fim" => [
                'date'
            ],
            "dt_cadastro" => [
                'date'
            ],
            "dt_modificado" => [
                'date'
            ],
            "obrigatorio" => [
                'boolean'
            ],
            "exibir_titulo" => [
                'boolean'
            ],
            "descricao" => [
                'string',
                'nullable',
                'unique:aviso,descricao' . ", $id",
                Rule::unique('perfil', 'id')->ignore($id),
                'min:5',
            ]
        ];
    }
}
