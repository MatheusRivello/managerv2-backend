<?php

namespace App\Rules\Api\Externa;

class SeguroExternaRule
{

    public function rules($id, $connection = null)
    {

        return [
            "valor" => [
                "Required",
                "numeric"
            ],
            "uf" => [
                "Required",
                "string",
                "max:2"
            ],
            "dtModificado" => [
                "Required",
                "Date"
            ]
        ];
    }
}
