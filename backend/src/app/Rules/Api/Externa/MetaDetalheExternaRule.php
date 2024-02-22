<?php

namespace App\Rules\Api\Externa;

class MetaDetalheExternaRule
{
    public function rules($id, $conection)
    {
        return [
            "idMeta" => [
                "Required",
                "int",
                "exists:{$conection}.meta,id"
            ],
            "ordem" => [
                "Nullable",
                "int"
            ],
            "descricao" => [
                "Required",
                "string",
                "max:100"
            ],
            "totCliCadastrados" => [
                "Nullable",
                "int"
            ],
            "totCliAtendidos" => [
                "Nullable",
                "int"
            ],
            "percemtTotCliAtendidos" => [
                "Nullable",
                "numeric"
            ],
            "totQtdVen" => [
                "Nullable",
                "numeric"
            ],
            "totPesoVen" => [
                "Nullable",
                "numeric"
            ],
            "percentTotPesoVen" => [
                "Nullable",
                "numeric"
            ],
            "totValVen" => [
                "Nullable",
                "numeric"
            ],
            "percentTotValVen" => [
                "Nullable",
                "numeric"
            ],
            "objetivoVendas" => [
                "Nullable",
                "numeric"
            ],
            "percentAtingido" => [
                "Nullable",
                "numeric"
            ],
            "tendenciaVendas" => [
                "Nullable",
                "numeric"
            ],
            "percentTendenciaVen" => [
                "Nullable",
                "numeric"
            ],
            "objetivoClientes" => [
                "Nullable",
                "numeric"
            ],
            "numeroCliFaltaAtender" => [
                "Nullable",
                "numeric"
            ],
            "pedAFaturar" => [
                "Nullable",
                "numeric"
            ],
            "prazoMedio" => [
                "Nullable",
                "numeric"
            ],
            "percentDesconto" => [
                "Nullable",
                "numeric"
            ],
            "totDesconto" => [
                "Nullable",
                "numeric"
            ],

        ];
    }
}
