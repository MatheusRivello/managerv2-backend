<?php

namespace App\Http\Controllers\api\v1\Tenant\Config;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ConfiguracaoFilial;
use Illuminate\Http\Request;
use App\Services\BaseService;
use Exception;

class ConfigFilialController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new BaseService;
    }

    public function indexEmpresaEspecifica()
    {
        return ConfiguracaoFilial::get();
    }

    public function storeEmpresaEspecifica(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_CONFIG_FILIAL_TENANT, $request->id);

            $configFilial = ConfiguracaoFilial::where(
                [
                    ["id_filial", $request->id_filial],
                    ['descricao', $request->descricao]
                ]
            )->firstOrNew();

            $configFilial->id_filial = $request->id_filial;
            $configFilial->descricao = $request->descricao;
            $configFilial->valor = $request->valor;
            $configFilial->tipo = $request->tipo;
            $configFilial->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function showEmpresaEspecifica($id)
    {
        $aviso = ConfiguracaoFilial::where('id', $id)->get();

        return response()->json([$this->service->verificarErro($aviso)], isset($aviso) ? 200 : 404);
    }

    public function destroyEmpresaEspecifica($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $configFilialTenant = ConfiguracaoFilial::find($id);

            if (!isset($configFilialTenant)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($configFilialTenant->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function indexEmpresas()
    {
        $listaConfigFilial = collect($this->service->listDriversTenants())->map(function ($item, $key) {
            return ConfiguracaoFilial::on($key)->get();
        });

        return $listaConfigFilial;
    }

    public function showEmpresas($idEmpresa)
    {
        try {
            $dadosEmpresa = $this->service->listDriversTenants($idEmpresa);
            return [
                "driver" => $dadosEmpresa[PREFIXO_ON_CONTROLLER],
                "database" => $dadosEmpresa['database'],
                "registros" => ConfiguracaoFilial::on($dadosEmpresa[PREFIXO_ON_CONTROLLER])->get()
            ];
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function destroyEmpresas(Request $request)
    {
        try {
            collect($this->service->listDriversTenants())->map(function ($item, $key) use ($request) {
                ConfiguracaoFilial::on($key)->where('descricao', $request->descricao)->delete();
            });

            return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
