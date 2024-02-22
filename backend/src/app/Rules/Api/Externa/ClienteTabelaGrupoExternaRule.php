<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class ClienteTabelaGrupoExternaRule
{
    public function rules($id, $conection = null)
    {
        return [
            "idCliente" => [
                "Required",
                "int",
                "exists:{$conection}.cliente,id",
                
            ],
            "idTabela" => [
                "Required",
                "string",
                "max:15",
                "exists:{$conection}.protabela_preco,id"
             ],
            "idGrupo" => [
                "Required",
                "string",
                "max:15",
                "exists:{$conection}.grupo,id"
            ]
        ];
    }
}
