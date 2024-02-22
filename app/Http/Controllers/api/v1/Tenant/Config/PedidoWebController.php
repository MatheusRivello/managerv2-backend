<?php

namespace App\Http\Controllers\api\v1\Tenant\Config;

use App\Http\Controllers\Controller;
use App\Models\Central\Empresa;
use App\Models\Tenant\ConfiguracaoPedidoweb;
use Illuminate\Http\Request;
use App\Services\Config\ConfigPedWebTenantService;
use Exception;
use Illuminate\Support\Facades\DB;

class PedidoWebController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new ConfigPedWebTenantService;
    }

    public function indexEmpresaEspecifica()
    {
        return collect(ConfiguracaoPedidoweb::get())->map(function ($elemento) {

            $infoTabela = json_decode($elemento->info_tabela);

            if (isset($infoTabela)) {
                $select = [
                    $infoTabela->campo_id,
                    DB::raw("CONCAT($infoTabela->campo_id, ' - ', $infoTabela->campo_descricao) as descricao")
                ];

                $selecDinamico = DB::table($infoTabela->tabela)->select($select)->get();

                $valorPadrao = $infoTabela->campo_filial == "" || is_null($infoTabela->campo_filial) ? $selecDinamico : $selecDinamico->where('id_filial', $infoTabela->campo_filial);

                $elemento->valor_padrao = $valorPadrao;
            }

            return $elemento;
        });
    }

    public function storeEmpresaEspecifica(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_CONFIG_PW_TENANT, $request->id);

            $configPedidoWeb = ConfiguracaoPedidoweb::where('descricao', $request->descricao)->firstOrNew();
            $configPedidoWeb->descricao = $request->descricao;
            $configPedidoWeb->valor = $request->valor;
            $configPedidoWeb->tipo = $request->tipo;
            $configPedidoWeb->label = $request->label;
            $configPedidoWeb->valor_padrao = $request->valor_padrao;
            $configPedidoWeb->campo = $request->campo;
            $configPedidoWeb->tamanho_maximo = $request->tamanho_maximo;
            $configPedidoWeb->tabela_bd = $request->tabela_bd;
            $configPedidoWeb->info_tabela = $request->info_tabela;
            $configPedidoWeb->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function storeArrayEmpresaEspecifica(Request $request)
    {
        try {
            foreach ($request->data as $config) {
                $this->service->verificarCamposArray($config);

                $configPedidoWeb = ConfiguracaoPedidoweb::where('descricao', $config["descricao"])->firstOrNew();
                $configPedidoWeb->descricao = $config["descricao"];
                $configPedidoWeb->valor = $config["valor"];
                $configPedidoWeb->update();
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function showEmpresaEspecifica($id)
    {
        $aviso = ConfiguracaoPedidoweb::where('id', $id)->get();

        return response()->json([$this->service->verificarErro($aviso)], isset($aviso) ? 200 : 404);
    }

    public function destroyEmpresaEspecifica($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $configPwTenant = ConfiguracaoPedidoweb::find($id);

            if (!isset($configPwTenant)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($configPwTenant->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function indexEmpresas()
    {
        $listaConfigPW = collect($this->service->listDriversTenants())->map(function ($item, $key) {
            return ConfiguracaoPedidoweb::on($key)->get();
        });

        return $listaConfigPW;
    }

    public function showEmpresas($idEmpresa)
    {
        try {
            $dadosEmpresa = $this->service->listDriversTenants($idEmpresa);
            return [
                "driver" => $dadosEmpresa[PREFIXO_ON_CONTROLLER],
                "database" => $dadosEmpresa['database'],
                "registros" => ConfiguracaoPedidoweb::on($dadosEmpresa[PREFIXO_ON_CONTROLLER])->get()
            ];
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function storeEmpresas(Request $request)
    {
        try {

            $tenants = $this->service->listDriversTenants();

            $this->service->verificarCamposRequest($request, RULE_CONFIG_PW_TENANT);

            foreach ($tenants as $key => $value) {
                $verificaConfig = ConfiguracaoPedidoweb::on($key)->where('descricao', $request->descricao)->first();

                if (is_null($verificaConfig)) {
                    $configPedidoWeb = ConfiguracaoPedidoweb::on($key)->where('descricao', $request->descricao)->create();
                    $configPedidoWeb->descricao = $request->descricao;
                    $configPedidoWeb->valor = $request->valor;
                    $configPedidoWeb->tipo = $request->tipo;
                    $configPedidoWeb->label = $request->label;
                    $configPedidoWeb->valor_padrao = $request->valor_padrao;
                    $configPedidoWeb->campo = $request->campo;
                    $configPedidoWeb->tamanho_maximo = $request->tamanho_maximo;
                    $configPedidoWeb->tabela_bd = $request->tabela_bd;
                    $configPedidoWeb->info_tabela = $request->info_tabela;
                    $configPedidoWeb->save();
                }
            }

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function destroyEmpresas(Request $request)
    {
        try {
            collect($this->service->listDriversTenants())->map(function ($item, $key) use ($request) {
                ConfiguracaoPedidoweb::on($key)->where('descricao', $request->descricao)->delete();
            });

            return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function indexTermoPW()
    {
        try {
            $empUserLogado = $this->service->usuarioLogado()->fk_empresa;
            $query = Empresa::select("id", "pw_termo as data");

            if (isset($empUserLogado)) {
                $query->where('id',  $empUserLogado);
            }

            return $this->service->verificarErro($query->get());
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function storeTermoPW(Request $request)
    {
        try {
            $empUserLogado = $this->service->usuarioLogado()->fk_empresa;
           
            $this->service->verificarCamposRequest($request, RULE_TERMO_PW_TENANT);

            $termo = Empresa::find(isset($empUserLogado) ? $empUserLogado : $request->empresa);
            $termo->pw_termo = $request->pw_termo;
            $termo->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
