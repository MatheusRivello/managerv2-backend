<?php

namespace App\Rules\Api\Servico;

class ContatoServiceRule
{
    public function rules()
    {
        return [
            "email" => [
                "string",
                "max:100",
                "email",
                "regex:/(.*)@c4kurd|.com/i"
            ],
            "contato" => [
                "string",
                "max:100"
            ],
            "nome" => [
                "string",
                "max:100"
            ],
            "aniversario" => [
                "nullable",
                "date"
            ],
            "hobby" => [
                "nullable",
                "string",
                "max:100"
            ],
            "telefone" => [
                "nullable",
                "string",
                "max:15"
            ],
            "con_cod" => [
                "int",
                "max:9",
                "min:1"
            ],
        ];
    }
}
