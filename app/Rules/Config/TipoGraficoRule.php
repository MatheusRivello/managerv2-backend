<?php

namespace App\Rules\Config;

use Illuminate\Validation\Rule;

class TipoGraficoRule
{
    public function rules($id, $connection = null)
    {
        return [
            "descricao" => [
                'required',
                'string',
                'max:60',
                Rule::unique('tipo_grafico', 'descricao')->ignore($id, "id")
            ],
            "status" => [
                'required',
                'boolean'
            ]
        ];
    }
}
