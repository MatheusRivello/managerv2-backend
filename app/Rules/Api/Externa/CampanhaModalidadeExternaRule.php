<?php

namespace App\Rules\Api\Externa;

class CampanhaModalidadeExternaRule
{
    public function rules($id,$conection=null)
    {
        return [
            "idCampanha" => [
                "required",
                "int",
                "exists:{$conection}.campanha,id"
            ],
            "idRetaguarda" => [
                "required",
                "string",
                "min:2",
                "max:2"
            ]
        ];
    }
}