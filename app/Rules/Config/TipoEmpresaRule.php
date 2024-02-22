<?php

namespace App\Rules\Config;

use Illuminate\Validation\Rule;

class TipoEmpresaRule
{
    public function rules($id, $connection = null)
    {
        return [
            "descricao" => [
                'required',
                'string',
                'max:45',
                Rule::unique('tipo_empresa', 'descricao')->ignore($id)
            ]
        ];
    }
}
