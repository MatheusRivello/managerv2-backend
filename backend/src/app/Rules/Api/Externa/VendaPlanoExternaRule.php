<?php

namespace App\Rules\Api\Externa;

class VendaPlanoExternaRule
{
    public function rules($id, $connection)
    {
        return [
            "idFilial" => [
                "Required",
                'int'
            ],
            "idCliente" => [
                'Required',
                'int'
            ],
            "nfsNum" => [
                'Required',
                'string',
                'max:16'
            ],
            "nfsSerie" => [
                'Nullable',
                'string',
                'max:20'
            ],
            "nfsDoc" => [
                'Required',
                'string',
                'max:16'
            ],
            "nfsEmissao" => [
                'Nullable',
                'Date'
            ],
            "tipoSaida" => [
                'Required',
                'string',
                'max:16'
            ]
        ];
    }
}
