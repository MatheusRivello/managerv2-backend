<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class SubGrupoExternaRule
{
    public function rules($id, $connection = null)
    {
        return [
            "idFilial" => [
                "Required",
                "int",
                "exists:{$connection}.filial,id"
            ],
            "idRetaguarda" => [
                "Required",
                "string",
                "max:15"
            ],
            "idGrupo" => [
                "Required",
                "int",
                "exists:{$connection}.grupo,id"
            ],
            "subGrupoDesc" => [
                "Required",
                "string",
                "max:60"
            ],
            "desctoMax" => [
                "Nullable",
                "Numeric"
            ],
            "status" => [
                "Required",
                "int",
                Rule::in(0, 1)
            ]

        ];
    }
}
