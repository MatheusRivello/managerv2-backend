<?php

namespace App\Rules\Api\Externa;

use Illuminate\Validation\Rule;

class FilialExternaRule
{
    public function rules($id,$connection=null)
    {
        return [
            "id"=>[
                "Required",
                "int",
                "unique:{$connection}.filial,id" . ", $id",
                Rule::unique("{$connection}.filial", 'id')->ignore($id),
            ],
            "empCgc" => [
                "Required",
                "string",
                "max:18"
            ],
            "empRaz" => [
                "Nullable",
                "string",
                "max:60"
            ],
            "empFan" => [
                "Nullable",
                "string",
                "max:60"
            ],
            "empAtiva" => [
                "Required",
                "int"
            ],
            "empUf" => [
                "Required",
                "string",
                "max:2"
            ],
            "empCaminhoImg" => [
                "Nullable",
                "string"
            ],
            "empUrlImg" => [
                "Nullable",
                "string"
            ],
            "empEmail" => [
                "Required",
                "string",
                "max:100"
            ]
        ];
    }
}
