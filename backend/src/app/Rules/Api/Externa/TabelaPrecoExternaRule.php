<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class TabelaPrecoExternaRule
{

    public function rules($id, $connection = null)
    {
        return [
            "idFilial" => [
                "required",
                "int",
                "exists:{$connection}.filial,id"
            ],
            "idRetaguarda" => [
                "Required",
                "string",
                "max:15"
            ],
            "tabDesc" => [
                "Required",
                "string",
                "max:100"
            ],
            "tabIni" => [
                "Date",
                "Nullable",
                "before_or_equal:tabFim"
            ],
            "tabFim" => [
                "Date",
                "Nullable",
                "after_or_equal:tabIni"
            ],
            "gerarVerba" => [
                "int",
                "max:1",
                Rule::in([0, 1]),
                "nullable"
            ]

        ];
    }
}
