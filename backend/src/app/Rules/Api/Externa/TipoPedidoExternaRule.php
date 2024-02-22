<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class TipoPedidoExternaRule{
    public function rules($id,$connection){
        return[
            "idRetaguarda"=>[
                "Required",
                "string",
                "max:15",
                "unique:{$connection}.tipo_pedido,id_retaguarda" . ", $id",
                Rule::unique("{$connection}.tipo_pedido", 'id')->ignore($id),
            ],
            "descricao"=>[
                "Required",
                "string",
                "max:30"
            ],
            "status"=>[
                "Required",
                Rule::in(0,1)
            ]
        ];
    }
}