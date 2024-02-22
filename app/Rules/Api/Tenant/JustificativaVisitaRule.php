<?php

namespace App\Rules\Api\Tenant;

class JustificativaVisitaRule
{
    public function rules($id, $connection = null)
    {
        return [
            "motivo" => [
                'required',
                'int',
                "exists:justificativa_vendedor,id"
            ],
            "idVendedor" => [
                'Required',
                'int',
                "exists:{$connection}.vendedor,id"
            ],
            'inicio' => [
                'required',
                'date_format:H:i',
                'before_or_equal:horaFinal'
            ],
            'fim' => [
                'required',
                'date_format:H:i',
                'after_or_equal:horaInicial'
            ],
            'diaTodo' => [
                'Required',
                'bool'
            ]
        ];
    }
}
