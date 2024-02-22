<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;


class ClienteAtividadeExternaRule
{

    public function rules($id,$connection=null)
    {

        return [
            "idFilial"=>[
                "Required",
                "int"
            ],
            "idRetaguarda"=>[
                "Required",
                "string",
                "max:15",
                "unique:{$connection}.atividade,id_retaguarda" . ", $id",
                Rule::unique("{$connection}.atividade", 'id')->ignore($id),
            ],
            "descricao"=>[
                "Required",
                "string",
                "max:60"
            ],
            "status"=>[
                "Required",
                "int",
                 Rule::in(0,1)
            ]
        ];
    }
}
