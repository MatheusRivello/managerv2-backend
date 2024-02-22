<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class NotaFiscalItemExternaRule
{
    public function rules($id,$conection=null)
    {
        return [
            "idFilial" => [
                "Required",
                "int",
                "exists:{$conection}.filial,id"
            ],
            "pedNum" => [
                "Required",
                "int",
                "exists:{$conection}.nota_fiscal,ped_num"
            ],
            "idProduto" => [
                "Required",
                "int",
                "exists:{$conection}.produto,id"
            ],
            "nfsDoc" => [
                "nullable",
                "string",
                "max:10"
            ],
            "nfsSerie" => [
                "nullable",
                "string",
                "max:6"
            ],
            "nfsStatus"=>[
                "Required",
                Rule::in(0,1,2,3,4,5,6)
            ],
            "nfsQtd" => [
                "Required",
                "numeric"
            ],
            "nfsUnitario" => [
                "Required",
                "numeric"
            ],
            "nfsDesconto" => [
                "nullable",
                "numeric"
            ],
            "nfsDescto" => [
                "nullable",
                "numeric"
            ],
            "nfsTotal" => [
                "nullable",
                "numeric"
            ],
            "pedQtd" => [
                "nullable",
                "numeric"
            ],
            "pedTotal" => [
                "nullable",
                "numeric"
            ],
            "nfsCusto" => [
                "nullable",
                "numeric"
            ]
        ];
    }
}