<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class VendedorExternaRule
{
    public function rules($id, $connection)
    {
        return [
            "id" =>[
                'Required',
                'int'
            ],
            "nome" => [
                "Required",
                "string",
                "max:100"
            ],
            "status" => [
                "Required",
                "int",
                Rule::in(0,1,true,false)
            ],
            "usuario" => [
                "nullable",
                "string",
                "max:45"
            ],
            "senha" => [
                "nullable",
                "string",
                "max:45"
            ],
            "supervisor" => [
                "nullable",
                "int"
            ],
            "gerente" => [
                "nullable",
                "int"
            ],
            "sequenciaPedido" => [
                "nullable",
                "int"
            ],
            "tipo" => [
                "nullable",
                "int"
            ],
            "saldoVerba" => [
                "nullable",
                "numeric"
            ]

        ];
    }
}
