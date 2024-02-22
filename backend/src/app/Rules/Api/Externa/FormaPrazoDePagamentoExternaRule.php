<?php

namespace App\Rules\Api\Externa;

class FormaPrazoDePagamentoExternaRule
{
    public function rules($id,$conection=null)
    {
        return [
            "idFormaPgto" => [
                "Required",
                "int",
                "exists:{$conection}.forma_pagamento,id"
            ],
            "idPrazoPgto" => [
                "Required",
                "int",
                "exists:{$conection}.prazo_pagamento,id"
            ]
        ];
    }
}
