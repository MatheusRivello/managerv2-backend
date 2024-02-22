<?php

namespace App\Rules\Api\Servico;

class VisitaServiceRule
{
    public function rules()
    {
        return [
            "id" => [
                "int",
                "min:1"
            ],
            "id_mobile" => [
                "int",
                "min:1"
            ],
            "id_filial" => [
                "int",
                "exists:filial,id"
            ],
            "id_pedido_dispositivo" => [
                "string",
                "nullable",
                "max:11"
            ],
            "id_nuvem" => [
                "string",
                "max:11"
            ],
            "id_motivo" => [
                "int",
                "nullable",
                "exists:motivo,id"
            ],
            "id_vendedor" => [
                "int",
                "exists:vendedor,id"
            ],
            "id_cliente" => [
                "int",
                "exists:cliente,id"
            ],
            "status" => [
                "boolean"
            ],
            "observacao" => [
                "nullable",
                "string",
                "max:255"
            ],
            "latitude" => [
                "nullable",
                "numeric",
                "max:20"
            ],
            "longitude" => [
                "nullable",
                "numeric",
                "max:20"
            ],
            "lat_inicio" => [
                "nullable",
                "numeric",
                "max:20"
            ],
            "lng_inicio" => [
                "nullable",
                "numeric",
                "max:20"
            ],
            "lat_fim" => [
                "nullable",
                "numeric",
                "max:20"
            ],
            "lng_fim" => [
                "nullable",
                "numeric",
                "max:20"
            ],
            "precisao_inicio" => [
                "nullable",
                "numeric"
            ],
            "precisao_fim" => [
                "nullable",
                "numeric"
            ],
            "provedor" => [
                "nullable",
                "string",
                "max:20"
            ],
            "precisao" => [
                "nullable",
                "numeric"
            ],
            "id_pedido_cliente" => [
                "nullable",
                "string",
                "max:15"
            ],
            "hora_marcada" => [
                "required",
                "date_format:H:i:s"
            ],
            "hora_inicio" => [
                "required",
                "date_format:H:i:s"
            ],
            "hora_final" => [
                "required",
                "date_format:H:i:s"
            ],
            "dt_marcada" => [
                "required",
                "date",
                "date_format:Y-m-d"
            ],
        ];
    }
}
