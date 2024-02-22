<?php

namespace App\Http\Controllers\api\v1\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

use App\Services\Config\ConfigService;

use App\Models\Central\TipoEmpresa;
use App\Models\Central\TipoPermissao;
use App\Models\Tenant\Cliente;

class ConfigManagerController extends Controller
{
    private $service;
    private $connection;

    public function __construct()
    {
        $this->service = new ConfigService;
        $this->connection = $this->service->connection('tenant');
    }

    public function index222()
    {
        try {
            $teste = Cliente::on($this->connection)->limit(5)->get();
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function tipoEmpresa()
    {
        return $this->service->verificarErro(TipoEmpresa::all());
    }

    public function storeTipoEmpresa(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_TIPO_EMPRESA, $request->id);

            $tipoEmpresa = TipoEmpresa::where('id', $request->id)->firstOrNew();
            $tipoEmpresa->descricao = $request->descricao;
            $tipoEmpresa->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function destroyTipoEmpresa($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $tipoEmpresa = TipoEmpresa::find($id);

            if (!isset($tipoEmpresa)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($tipoEmpresa->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
   
    public function tipoPermissao()
    {
        return $this->service->verificarErro(TipoPermissao::all());
    }

    public function storeTipoPermissao(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_TIPO_PERMISSAO, $request->id);

            $tipoPermissao = TipoPermissao::where('id', $request->id)->firstOrNew();
            $tipoPermissao->descricao = $request->descricao;
            $tipoPermissao->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function destroyTipoPermissao($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $tipoPermissao = TipoPermissao::find($id);

            if (!isset($tipoPermissao)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($tipoPermissao->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }
}
