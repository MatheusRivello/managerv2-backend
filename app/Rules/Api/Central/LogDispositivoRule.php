<?php

namespace App\Rules\Api\Central;

class LogDispositivoRule
{
    public function rules($id, $connection = null)
    {
        return [
            "mac" => [
                'string',
                'max:12',
                'required',
            ],
            "fk_empresa" => [
                'int',
                'required',
            ],
            "descricao" => [
                'string',
                'required',
            ],
            "contexto" => [
                'string',
            ],
            "codigoErro" => [
                'string',
                'max:100',
            ],
            "status" => [
                'string',
                'max:100',
            ],
            "versaoApp" => [
                'string',
                'max:100',
                'required'
            ],
            "tipo" => [
                'string',
                'max:100',
            ],
            "dt_resolvido" => [
                'date',
                'required',
            ],
            "resolvido" => [
                'boolean',
            ]
        ];
    }
}
