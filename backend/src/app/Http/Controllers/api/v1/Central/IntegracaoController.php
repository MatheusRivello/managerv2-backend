<?php

namespace App\Http\Controllers\api\v1\Central;

use App\Http\Controllers\Controller;
use App\Models\Central\Integracao;
use App\Models\Tenant\Filial;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;

class IntegracaoController extends Controller
{
    private $service;
    

    public function __construct()
    {
         $this->service = new BaseService;
    }

    public function index()
    {
        $integraçao = Integracao::join('empresa', 'integracao.id_empresa', 'empresa.id')
            ->get(["integracao.*", "empresa.razao_social", "empresa.nome_fantasia"]);

        collect($integraçao)->map(function ($integracao) {

            $filial = Filial::on('empresa' . $integracao->id_empresa)
                ->where('id', $integracao->id_filial)
                ->first();

            $integracao->infoFilial = [
                "filial_razao_social" => isset($filial->emp_raz) ? $filial->emp_raz : null,
                "filial_nome_fantasia" => isset($filial->emp_fan) ? $filial->emp_fan : null
            ];

            return $integracao;
        });

        return $this->service->verificarErro($integraçao);
    }


    public function store(Request $request)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_INTEGRACAO_CENTRAL);

            $integracao = new Integracao;
            $integracao->id_empresa = $request->id_empresa;
            $integracao->integrador = $request->integrador;
            $integracao->url_base = $request->url_base;
            $integracao->url_loja = $request->url_loja;
            $integracao->id_filial = $request->id_filial;
            $integracao->id_tabela_preco = $request->id_tabela_preco;
            $integracao->usuario = $request->usuario;
            $integracao->senha = $request->senha;
            $integracao->campo_extra_1 = $request->campo_extra_1;
            $integracao->campo_extra_2 = $request->campo_extra_2;
            $integracao->campo_extra_3 = $request->campo_extra_3;
            $integracao->campo_extra_4 = $request->campo_extra_4;
            $integracao->campo_extra_5 = $request->campo_extra_5;
            $integracao->data_cadastro = $request->data_cadastro;
            $integracao->data_modificado = $request->data_modificado;
            $integracao->data_ativacao = $request->data_ativacao;
            $integracao->status = $request->status;
            $integracao->execucao_inicio = $request->execucao_inicio;
            $integracao->execucao_fim = $request->execucao_fim;
            $integracao->execucao_status = $request->execucao_status;

            $integracao->save();

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function show($id)
    {
        $integraçao = Integracao::where('id', $id)->get();

        collect($integraçao)->map(function ($integracao) {

            $filial = Filial::on('empresa' . $integracao->id_empresa)
                ->where('id', $integracao->id_filial)
                ->first();

            $integracao->infoFilial = [
                "filial_razao_social" => isset($filial->emp_raz) ? $filial->emp_raz : null,
                "filial_nome_fantasia" => isset($filial->emp_fan) ? $filial->emp_fan : null
            ];

            return $integracao;
        });

        return $this->service->verificarErro($integraçao);
    }

    public function update(Request $request, $id)
    {
        try {
            $this->service->verificarCamposRequest($request, RULE_INTEGRACAO_CENTRAL, $id);

            $integracao = Integracao::find($id);
            if (!$integracao) {
                throw new Exception(REGISTRO_NAO_ENCONTRADO, 409);
            }

            $integracao->update($request->all());

            return response()->json(["message" => REGISTRO_SALVO], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            if (empty($id)) {
                throw new Exception(ID_NAO_INFORMADO, 400);
            }

            $integracao = Integracao::find($id);

            if (!isset($integracao)) {
                throw new Exception(ID_NAO_ENCONTRADO, 403);
            }

            if ($integracao->delete()) {
                return response()->json(["message" => REGISTRO_EXCLUIDO], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getCode());
        }
    }

    public function getFilial($idEmpresa)
    {
        try {
            return $this->service->verificarErro(
                Filial::on('empresa' . $idEmpresa)
                    ->with('protabela_precos')
                    ->get()
            );
        } catch (Exception $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], 400);
        }
    }
}
