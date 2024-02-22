<?php

namespace App\Rules\Api\Externa;

class IndicadorDeMargemexternaRule
{
    public function rules($id,$connection=null)
    {
        return [
            "idFilial" => [
                "Required",
                "int",
                "exists:{$connection}.filial,id"
            ],
            "nivel" => [
                "Required",
                "int"
            ],
            "de" => [
                "Required",
                "numeric"
            ],
            "ate" => [
                "Required",
                "numeric"
            ],
            "indice" => [
                "Required",
                "numeric"
            ]
        ];
    }
}
