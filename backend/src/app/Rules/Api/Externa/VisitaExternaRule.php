<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class VisitaExternaRule
{
    public function rules($id, $conection = null)
    {
        return [
            'idFilial' => [
                "Required",
                "int",
                "exists:{$conection}.filial,id"
            ],
            "idMotivo" => [
                "nullable",
                "int",
                "exists:{$conection}.motivo,id"
            ],
            "idVendedor" => [
                "Required",
                "int",
                "exists:{$conection}.vendedor,id"
            ],
            "idCliente" => [
                "Required",
                "int",
                "exists:{$conection}.cliente,id"
            ],
            "idPedidoDispositivo" => [
                "Nullable",
                "int"
            ],
            "status" => [
                'required',
                'int',
                Rule::in(0, 1, 2, 3, 5, 6, 7)
            ],
            "sincErp" => [
                'required',
                'int'
            ],
            "dtMarcada" => [
                'nullable',
                'date'
            ],
            "horaMarcada" => [
                'nullable',
                'date_format:H:i'
            ],
            "observacao" => [
                'nullable',
                'string',
                'max:255'
            ],
            "ordem" => [
                'nullable',
                'int'
            ],
            "latitude" => [
                'nullable',
                'string',
                'max:20'
            ],
            "longitude" => [
                'nullable',
                'string',
                'max:20'
            ],
            "precisao" => [
                'nullable',
                'string',
                'max:20'
            ],
            "provedor" => [
                'nullable',
                'string',
                'max:20'
            ],
            "latInicio" => [
                'nullable',
                'string',
                'max:20'
            ],
            "lngInicio" => [
                'nullable',
                'string',
                'max:20'
            ],
            "latFinal" => [
                'nullable',
                'string',
                'max:20'
            ],
            "lngFinal" => [
                'nullable',
                'string',
                'max:20'
            ],
            "precisaoInicio" => [
                'nullable',
                'string',
                'max:20'
            ],
            "precisaoFinal" => [
                'nullable',
                'string',
                'max:20'
            ],
            "horaInicio" => [
                'nullable',
                'date_format:H:i'
            ],
            "horaFinal" => [
                'nullable',
                'date_format:H:i'
            ],
            "dtCadastro" => [
                'nullable',
                'date'
            ],
            "enderecoExtensoGoogle" => [
                'nullable',
                'string',
                'max:255'
            ],

        ];
    }
}
