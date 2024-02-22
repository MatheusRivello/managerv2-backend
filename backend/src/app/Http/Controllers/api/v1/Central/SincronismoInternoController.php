<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\ConfiguracaoEmpresa;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class SincronismoInternoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
        $this->idEmpresa = $this->service->usuarioLogado()->fk_empresa;
        $this->model = ConfiguracaoEmpresa::class;
    }

    public function store(Request $request)
    {

        try {
            $this->service->verificarCamposRequest($request->all(), RULE_INTEGRACAO_INTERNA_AFV);
            $configs = $request->opcoes;
            $tipo = $request->tipo;

            foreach ($configs as $config => $valor) {
                $this->model::where([
                    'fk_empresa' => $this->idEmpresa,
                    'fk_tipo_configuracao_empresa' => $config,
                    'tipo' => $tipo,
                ])->delete();

                $registro = new $this->model;
                $registro->fk_empresa = $this->idEmpresa;
                $registro->fk_tipo_configuracao_empresa = $config;
                $registro->tipo = $tipo;
                $registro->valor = $valor;
                $registro->save();
            }
            return response()->json(['message:' => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function storeSincronismo(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request->all(), RULE_INTEGRACAO_INTERNA_AFV);
            $configs = $request->opcoes;
            $tipo = $request->tipo;
            $configuracoes = $this->model::where([
                'fk_empresa' => $this->idEmpresa,
                'tipo' => $tipo,
            ])
                ->orderBy("fk_tipo_configuracao_empresa")
                ->get();

            foreach ($configuracoes as $key => $config) {
               
                $oldConfig = $this->model::where([
                    'fk_empresa' => $this->idEmpresa,
                    'fk_tipo_configuracao_empresa' => $config->fk_tipo_configuracao_empresa,
                    'tipo' => $config->tipo
                ])->delete();

                if ($oldConfig) {
                    $registro = new $this->model;
                    $registro->fk_empresa = $this->idEmpresa;
                    $registro->fk_tipo_configuracao_empresa = $config->fk_tipo_configuracao_empresa;
                    $registro->tipo = $config->tipo;
                    $registro->valor = in_array($config->fk_tipo_configuracao_empresa, $configs, true) ? STATUS_ATIVO : STATUS_INATIVO;
                    $registro->save();
                }
            }

            return response()->json(['message:' => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function index()
    {
        try {
            $registro = $this->model::select(
                'fk_empresa',
                'fk_tipo_configuracao_empresa',
                'tipo',
                'valor',
                'grupo'
            )
                ->where('fk_empresa', '=', $this->service->usuarioLogado()->fk_empresa)
                ->with('tipoConfigSimplificada')
                ->get();

            return $this->service->verificarErro($registro);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
