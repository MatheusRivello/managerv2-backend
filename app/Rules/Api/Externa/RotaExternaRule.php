<?php

namespace App\Rules\Api\Externa;

class RotaExternaRule
{

    public function rules($id, $connection = null)
    {

        return [
            "idFilial" => [
                'required',
                'int',
                "exists:{$connection}.filial,id"
            ],
            "idRetaguarda" => [
                'required',
                'int'
            ],
            'descricao' => [
                'required',
                'string',
                'max:255'
            ],
            'rotaFrete' => [
                'required',
                'numeric'
            ],
            'rotaTipoFrete' => [
                'required',
                'int'
            ]
        ];
    }
}
