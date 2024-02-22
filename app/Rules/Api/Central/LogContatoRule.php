<?php

namespace App\Rules\Api\Central;

class LogContatoRule
{
    public function rules($id, $connection = null)
    {
        return [
            "fk_empresa" => [
                'int',
                'required',
            ],
            "id_cliente" => [
                'int',
                'required',
            ],
            "nome" => [
                'string',
                'max:100',
                'required',
            ],
            "email" => [
                'email',
                'max:100',
                'required',
            ],
            "telefone" => [
                'string',
                'max:50',
            ],
            "mensagem" => [
                'string',
                'max:200',
            ],
            "dt_enviado" => [
                'date',
                'required',
            ],
            "status" => [
                'boolean',
            ]
        ];
    }
}
