<?php

namespace App\Rules\Api\Externa;

class LogExternaRule
{
    public function rules($id, $conection)
    {
        return [
            'tipo' => [
                "Required",
                "int"
            ],
            'idEmpresa' => [
                'nullable',
                'int'
            ],
            'mac' => [
                'nullable',
                'string',
                'max:12'
            ],
            'idCliente' => [
                'nullable',
                'int',
                "exists:{$conection}.cliente,id"
            ],
            'idFilial' => [
                'nullable',
                'int',
                "exists:{$conection}.filial,id"
            ],
            'tabela' => [
                'nullable',
                'string',
                'max:100'
            ],
            'conteudo' => [
                'nullable',
                'string'
            ],
            'mensagem' => [
                'nullable',
                'string'
            ],
            'ip' => [
                'nullable',
                'string',
                'max:35'
            ],
            'dtCadastro' => [
                "Required",
                "date"
            ]
        ];
    }
}
