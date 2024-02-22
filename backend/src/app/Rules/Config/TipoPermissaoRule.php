<?php

namespace App\Rules\Config;

use Illuminate\Validation\Rule;

class TipoPermissaoRule
{
    public function rules($id, $connection = null)
    {
        return [
            "descricao" => [
                'required',
                'string',
                'max:60',
                Rule::unique('tipo_permissao', 'descricao')->ignore($id)
            ]
        ];
    }
}
