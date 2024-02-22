<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class ContatoExternaRule
{

    public function rules($id, $conection = null)
    {

        return [
            "idCliente" => [
                "Required",
                "int",
                "exists:{$conection}.cliente,id"
            ],
            "conCod" => [
                "Required",
                "int",
                Rule::unique("{$conection}.contato", "con_cod")->where(function ($query) use ($id) {
                    return $query->where([['id_cliente', $id['id_cliente']], ['con_cod', $id['con_cod']]]);
                })
            ],
            "telefone" => [
                "Nullable",
                "String",
                "max:15"
            ],
            "email" => [
                "Nullable",
                "string",
                "max:100"
            ],
            "nome" => [
                "Nullable",
                "string",
                "max:100"
            ],
            "aniversario" => [
                "Nullable",
                "string",
                "max:10"
            ],
            "hobby" => [
                "Nullable",
                "string",
                "max:100"
            ],
            "sincErp" => [
                "Nullable",
                "int",
                Rule::in(0, 1)
            ]
        ];
    }
}
