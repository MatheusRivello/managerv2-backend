<?php

namespace App\Services\Config;

use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConfigService extends BaseService
{
    public function verificarCamposTipoEmpresa($request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            "descricao" => [
                'required',
                'string',
                'max:45',
                Rule::unique('tipo_empresa', 'descricao')->ignore($id)
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
            return $request->all();
        }
    }

    public function verificarCamposTipoPermissao($request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            "descricao" => [
                'required',
                'string',
                'max:60',
                Rule::unique('tipo_permissao', 'descricao')->ignore($id)
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
            return $request->all();
        }
    }
}
