<?php

namespace App\Rules\Api\Externa;

class CampanhaParticipanteExternaRule
{
    public function rules()
    {
        return [
            "idCampanha" => [
                "int",
                "required"
            ],
            "idRetaguarda" => [
                "string",
                "max:15",
                "required"
            ]
        ];
    }
}