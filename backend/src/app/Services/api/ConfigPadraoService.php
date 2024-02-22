<?php

namespace App\Services\api;

use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Validator;

class ConfigPadraoService extends BaseService
{
   
    
    public function verificarHorario($configuracao, $id = null)
    {
        $validator = Validator::make($configuracao, [
            "tipo_configuracao" => [
                'required',
                'integer',
                "exists:tipo_configuracao,id"
            ],
            "valor" => [
                'required',
                'string'
            ]
        ], $this->messages());

        $errors = $validator->errors();

        $textoErrors = [];

        foreach ($errors->all() as $message) {
            $textoErrors[] = $message . ' ';
        }

        if ($validator->fails()) {
            throw new Exception(implode($textoErrors), 409);
        } else {
            return $configuracao;
        }
    }
}
