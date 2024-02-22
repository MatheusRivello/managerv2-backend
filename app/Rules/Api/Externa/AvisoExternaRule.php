<?php

namespace App\Rules\Api\Externa;

class AvisoExternaRule
{
    public function rules($id, $conection)
    {
        return [
            "idFilial" => [
                "Required",
                "int",
                "exists:{$conection}.filial,id"
            ],
            "descricao" => [
                "Required",
                "string"
            ],
            "dtInicio" => [
                "nullable",
                "Date",
                "before_or_equal:dtFim"
            ],
            "dtFim" => [
                "Nullable",
                "Date",
                "after_or_equal:dtInicio"
            ],
            "tipo" => [
                "Nullable",
                "int"
            ]
        ];
    }
}
