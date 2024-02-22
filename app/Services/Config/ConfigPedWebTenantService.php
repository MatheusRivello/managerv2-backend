<?php

namespace App\Services\Config;

use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Validator;

class ConfigPedWebTenantService extends BaseService
{

    public function rules()
    {
        return [
            "descricao" => [
                'required',
                'string',
                'min:5',
                'max:30'
            ],
            "valor" => [
                'nullable',
                'string',
                'max:100'
            ]
        ];
    }

    public function verificarCamposArray($request)
    {
        $validator = Validator::make($request, $this->rules(), $this->messages());

        $errors = $validator->errors();

        $textoErrors = [];

        foreach ($errors->all() as $message) {
            $textoErrors[] = $message . ' ';
        }

        if ($validator->fails()) {
            throw new Exception(implode($textoErrors), 409);
        } else {
            return $request;
        }
    }
}
