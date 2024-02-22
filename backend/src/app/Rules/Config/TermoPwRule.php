<?php

namespace App\Rules\Config;

class TermoPwRule
{
    public function rules($id, $connection = null)
    {
        return [
            "pw_termo" => [
                'nullable',
                'string',
                'max:6000'
            ]
        ];
    }
}
