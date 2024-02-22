<?php

namespace App\Http\Controllers\api\v1\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Integracao;
use App\Services\api\Tenant\IntegracaoTenantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IntegracaoController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new IntegracaoTenantService();
    }

    public function index($idEmpresa)
    {
        try {

            $resultadoCliente = DB::connection($this->service->connectionTenant($idEmpresa))->table('integracao')
                ->select(
                    "integracao.id",
                    "integracao.integrador",
                    DB::raw("cliente.id_filial as filial"),
                    "integracao.tipo",
                    DB::raw("1 as filtro"),
                    DB::raw("cliente.id_retaguarda as id_interno"),
                    "integracao.id_externo",
                    "integracao.campo_extra_1",
                    "integracao.campo_extra_2",
                    "integracao.campo_extra_3",
                    "integracao.ultimo_status",
                    "integracao.dt_modificado"
                )
                ->join("cliente", "integracao.id_interno", "cliente.id")
                ->where('integracao.tipo', 1);

            $resultado = DB::connection($this->service->connectionTenant($idEmpresa))->table('integracao')
                ->select(
                    "integracao.id",
                    "integracao.integrador",
                    DB::raw("produto.id_filial as filial"),
                    "integracao.tipo",
                    DB::raw("2 as filtro"),
                    DB::raw("produto.id_retaguarda as id_interno"),
                    "integracao.id_externo",
                    "integracao.campo_extra_1",
                    "integracao.campo_extra_2",
                    "integracao.campo_extra_3",
                    "integracao.ultimo_status",
                    "integracao.dt_modificado"
                )
                ->join("produto", "integracao.id_interno", "produto.id", "inner")
                ->where('integracao.tipo', 2)
                ->union($resultadoCliente);

            return $this->service->verificarErro($resultado->paginate(10));
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function show($idEmpresa, $tipo, $idElemento)
    {
        try {

            if ($tipo == 1) {
                $classe = "cliente";
                $filtro = 1;
            } else {
                $classe = "produto";
                $filtro = 3;
            }

            $resultado = DB::connection($this->service->connectionTenant($idEmpresa))
                ->table('integracao')
                ->select(
                    "integracao.id",
                    "integracao.integrador",
                    DB::raw("$classe.id_filial as filial"),
                    "integracao.tipo",
                    DB::raw("$filtro as filtro"),
                    DB::raw("$classe.id_retaguarda as id_interno"),
                    "integracao.id_externo",
                    "integracao.campo_extra_1",
                    "integracao.campo_extra_2",
                    "integracao.campo_extra_3",
                    "integracao.ultimo_status",
                    "integracao.dt_modificado"
                )
                ->join("$classe", "integracao.id_interno", "$classe.id")
                ->where('integracao.tipo', $tipo)
                ->where('integracao.id', $idElemento)
                ->get();


            return $this->service->verificarErro($resultado);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function storeUpdate($idEmpresa, Request $request)
    {
        try {
            $conexao = $this->service->connectionTenant($idEmpresa);

            $this->service->verificarDadosIntegracao($request, $idEmpresa);

            $elemento = Integracao::on($conexao)->firstOrNew(['id' => $request->id]);
            $elemento->integrador = $request->integrador;
            $elemento->tipo = $request->tipo;
            $elemento->id_interno = $request->id_interno;
            $elemento->id_externo = $request->id_externo;
            $elemento->campo_extra_1 = $request->campo_extra_1;
            $elemento->campo_extra_2 = $request->campo_extra_2;
            $elemento->campo_extra_3 = $request->campo_extra_3;
            $elemento->ultimo_status = $request->ultimo_status;
            $elemento->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function destroy($idEmpresa, $idElemento)
    {
        try {
            if (empty($idElemento)) {
                throw new Exception(ID_NAO_INFORMADO, 409);
            }

            $conexao = $this->service->connectionTenant($idEmpresa);

            $elemento = Integracao::on($conexao)->find($idElemento);

            if (!isset($elemento)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($elemento->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
