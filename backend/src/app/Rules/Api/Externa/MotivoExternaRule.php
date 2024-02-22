<?php

namespace App\Rules\Api\Externa;

class MotivoExternaRule
{
    public function rules($id,$conection=null)
    {
        return [
            "idFilial" => [
                "required",
                "int",
                "exists:{$conection}.filial,id"
            ],
            "idRetaguarda" => [
                "required",
                "string",
                "max:15"
            ],
            "descricao" => [
                "string",
                "max:100",
                "nullable"
            ],
            "tipo" => [
                "boolean",
                "required"
            ],
            "status" => [
                "boolean",
                "required"
            ],
        ];
    }
}
