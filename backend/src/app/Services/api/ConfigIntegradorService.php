<?php

namespace App\Services\api;

use App\Models\Central\ConfigIntegrador;
use App\Services\BaseService;
use Exception;

class ConfigIntegradorService extends BaseService
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService();
    }
    public function index()
    {
        try {
            return ConfigIntegrador::select()
                ->get();
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function post($parametros)
    {
        try {
            $this->service->verificarCamposRequest($parametros, RULE_CONFIG_INTEGRADOR);

            ConfigIntegrador::create([
                'id' => $parametros->id,
                'name' => $parametros->name,
                'fk_empresa' => $parametros->fkEmpresa,
                'value' => $parametros->value
            ]);
            return response()->json(["message:" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function put($parametros)
    {
        try {
            $update = new ConfigIntegrador;
           
            $update->where('id',$parametros->id)
            ->update([
                'id' => $parametros->id,
                'name' => $parametros->name,
                'fk_empresa' => $parametros->fkEmpresa,
                'value' => $parametros->value
            ]);
           
            return response()->json(["message:" => "Registro modificado com sucesso."], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
