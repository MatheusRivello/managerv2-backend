<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class IntegracaoProgamadaRule
{
    public function rules($id, $connection = null)
    {
        return [
            'tipo' => [
                'Required',
                'int',
                Rule::in(0, 1, 2)
            ],
        ];
    }
}
