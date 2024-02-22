<?php

namespace App\Rules\Api\Externa;

class RastroExternaRule
{

    public function rules($id, $connection = null)
    {

        return [
            'idVendedor' => [
                'Required',
                'int'
            ],
            'data' => [
                'nullable',
                'date'
            ],
            'hora' => [
                'nullable',
                'date_format:H:i'
            ],
            'latitude' => [
                'nullable',
                'string',
                'max:30'
            ],
            'longitude' => [
                'nullable',
                'string',
                'max:30'
            ],
            'velocidade' => [
                'nullable',
                'numeric'
            ],
            'altitude' => [
                'nullable',
                'numeric'
            ],
            'direcao' => [
                'nullable',
                'string',
                'max:30'
            ],
            'mac' => [
                'Required',
                'string',
                'max:12'
            ],
            'provedor' => [
                'nullable',
                'string',
                'max:30'
            ],
            'precisao' => [
                'nullable',
                'string',
                'max:20'
            ],
            'dtCadastro' => [
                'nullable',
                'date'
            ],
            'sincErp' => [
                'nullable',
                'int'
            ]
        ];
    }
}
