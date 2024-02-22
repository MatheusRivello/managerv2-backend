<?php

namespace App\Rules\Api\Externa;

class IntegracaoExternaRule
{
    public function rules($id, $conection)
    {
        return [
            "id" => [
                "Required",
                "int"
            ],
            "integrador" => [
                "Required",
                "int"
            ],
            "tipo" => [
                "Nullable",
                "int"
            ],
            "idInterno" => [
                "Required",
                "int"
            ],
            "idExterno" => [
                "Nullable",
                "string",
                "max:30"
            ],
            "campoExtra1" => [
                "Nullable",
                "string",
                "max:45"
            ],
            "campoExtra2" => [
                "Nullable",
                "string",
                "max:45"
            ],
            "campoExtra3" => [
                "Nullable",
                "string",
                "max:45"
            ],
            "ultimoStatus" => [
                "Nullable",
                "string",
                "max:45"
            ],
            "dtModificado" => [
                "Nullable",
                "Date"
            ]
        ];
    }
}
