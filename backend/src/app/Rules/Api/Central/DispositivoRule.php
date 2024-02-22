<?php

namespace App\Rules\Api\Central;

use Illuminate\Validation\Rule;

class DispositivoRule
{

    public function rules($id, $connection = null)
    {
        return  [
            "codigo" => [
                'required',
                'string',
                'unique:dispositivo,mac' . ", $id",
                Rule::unique('dispositivo', 'id')->ignore($id),
                'size:12'
            ],
            "id_vendedor" => [
                'required',
                'integer',
                "exists:{$connection}.vendedor,id"
            ],
            "imei" => [
                'string',
                'unique:dispositivo,imei' . ", $id",
                Rule::unique('dispositivo', 'id')->ignore($id),
                'min:5',
                'max:100',
                'nullable'
            ],
            'status' => [
                'integer'
            ],
            'licenca' => [
                'integer'
            ]

        ];
    }
}
