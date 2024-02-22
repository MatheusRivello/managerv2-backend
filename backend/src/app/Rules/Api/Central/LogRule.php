<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class LogRule
{
    public function rules($id, $connection = null)
    {
        return [
            "tipo" => [
                'int',
                'required',
                Rule::in([1, 2, 3, 10, 11, 12, 20, 21]),
            ],
            "tipo_acesso" => [
                'string',
                'max:15',
                'required',
            ],
            "fk_empresa" => [
                'int',
                'required',
            ],
            "fk_usuario" => [
                'int',
                'required',
            ],
            "id_cliente" => [
                'int',
            ],
            "tabela" => [
                'required',
                'string',
                'max:100',
            ],
            "mensagem" => [
                'string',
                'required',
            ],
            "conteudo" => [
                'string',
                'required',
                'max:400000000',
            ]
        ];
    }
}
