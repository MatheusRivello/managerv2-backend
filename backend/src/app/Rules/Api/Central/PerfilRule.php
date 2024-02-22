<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class PerfilRule
{

    public function rules($id, $connection = null)
    {

        return [
            "descricao" => [
                'required',
                'string',
                'unique:perfil,descricao' . ", $id",
                Rule::unique('perfil', 'id')->ignore($id),
                'min:5',
                'max:45'
            ],
            'fk_tipo_perfil' => [
                'Required',
                'int',
                "exists:tipo_perfil,id"
            ],
            'fk_tipo_empresa' => [
                'Required',
                "exists:tipo_empresa,id"
            ],
            'status' =>[
                'Required'
            ]

        ];
    }
}
