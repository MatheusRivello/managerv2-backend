<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class UsuarioRule
{

    public function rules($id, $connection = null)
    {
        return [
            "email" => [
                'required',
                'email',
                'unique:usuario,email' . ", $id",
                Rule::unique('usuario', 'id')->ignore($id),
                'min:6',
                'max:100'
            ],
            "usuario" => [
                'required',
                'unique:usuario,usuario' . ", $id",
                Rule::unique('usuario', 'id')->ignore($id),
                'min:5',
                'max:30'
            ],
            "password" => [
                'string',
                'max:30'
            ],
            "senha" => [
                'string',
                'nullable',
                'max:30'
            ],
            "telefone" => [
                'string',
                'nullable',
                'max:11'
            ]
        ];
    }
}
