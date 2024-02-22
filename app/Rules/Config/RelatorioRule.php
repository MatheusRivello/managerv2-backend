<?php

namespace App\Rules\Config;

class RelatorioRule
{
    public function rules($id, $connection = null)
    {
        return [
            "grupo" => [
                'required',
                'int',
                'exists:grupo_relatorio,id'
            ],
            "upload" => [
                'mimes:jpg,jpeg,bmp,png',
                'nullable',
                'file',
                'max:3072'
            ],
            "titulo" => [
                'required',
                'string',
                'max:255'
            ],
            "tipo_grafico" => [
                'required',
                'int',
                'exists:tipo_grafico,id'
            ],
            "status" => [
                'required',
                'boolean',
            ],
            "campo_query" => [
                'required',
                'string',
                'max:10000'
            ],
            "datakey" => [
                'required',
                'string',
                'max:600'
            ]
        ];
    }
}
