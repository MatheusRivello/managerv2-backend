<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class LogSincronismoRule
{
    public function rules($id, $connection = null)
    {
        return [
            "id_empresa" => [
                'int',
                'required',
            ],
        ];
    }
}
