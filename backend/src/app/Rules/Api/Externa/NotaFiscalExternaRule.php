<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class NotaFiscalExternaRule
{
  public function rules($id, $conection = null)
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
        "exists:{$conection}.pedido,id"
      ],
      "idCliente" => [
        "Required",
        "int",
        "exists:{$conection}.cliente,id"
      ],
      "idVendedor" => [
        "Required",
        "int",
        "exists:{$conection}.vendedor,id"
      ],
      "nfsDoc" => [
        "Nullable",
        "string",
        "max:10"
      ],
      "nfsSerie" => [
        "Nullable",
        "string",
        "max:6"
      ],
      "nfsStatus" => [
        "Required",
        "int",
        Rule::in(1, 2, 3, 4, 5, 6)
      ],
      "nfsEmissao" => [
        "Nullable",
        "Date"
      ],
      "nfsValbrut" => [
        "Nullable",
        "numeric"
      ],
      "nfsTipo" => [
        "Required",
        "string",
         Rule::in("0","4", "D", "F", "E")
      ],
      "pedEntrega"=>[
        "Nullable",
        "Date"
      ],
      "prazoPag" => [
        "Nullable",
        "string",
        "max:8",
        "exists:{$conection}.prazo_pagamento,id_retaguarda"
      ],
      "formaPag" => [
        "Nullable",
        "string",
        "max:8",
        "exists:{$conection}.forma_pagamento,id"
      ],
      "pedEmissao" => [
        "Nullable",
        "Date"
      ],
      "pedTotal" => [
        "Nullable",
        "numeric"
      ],
      "nfsCusto" => [
        "Nullable",
        "numeric"
      ],
      "observacao" => [
        "Nullable",
        "string",
        "max:255"
      ]

    ];
  }
}
